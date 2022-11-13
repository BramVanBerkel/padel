<?php

namespace App\Services;

use App\Enums\LocationEnum;
use App\Models\Location;
use App\Models\User;
use App\Models\UserAvailability;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

class AvailabilityService
{
    const BASE_URL = 'https://foys-court-booking-prod.azurewebsites.net/public/api/v1/locations/search';

    public function getAvailabilities(LocationEnum $location, Carbon $date)
    {
        $timeSlots = $this->generateTimeSlots($date);

        $availabilities = $this->fetchAvailabilities($location, $date);

        $userAvailabilities = $this->getUserAvailabilities($date, $location);

        $availabilities->get('inventoryItemsTimeSlots')
            ->each(function (Collection $court) use ($userAvailabilities, $timeSlots) {
                $court->get('timeSlots')
                    ->each(function (Collection $timeSlot) use ($userAvailabilities, $timeSlots) {
                        $time = Carbon::parse($timeSlot->get('startTime'))->format('H:i');

                        $timeSlotData = [
                            'available' => $timeSlot->get('isAvailable'),
                        ];

                        $users = $userAvailabilities->where('time', '=', $time)
                            ->pluck('user')
                            ->pluck('name')
                            ->toArray() ?? [];

                        $timeSlotData = array_merge($timeSlotData, [
                            'users' => $users,
                        ]);

                        $timeSlots->put($time, $timeSlotData);
                    });
            });

        return $timeSlots;
    }

    private function generateTimeSlots(Carbon $date): Collection
    {
        if ($date->isWeekday()) {
            $start = now()->setHour(7)->setMinute(0)->setSecond(0);
            $end = now()->setHour(22)->setMinute(30)->setSecond(0);
        } else {
            $start = now()->setHour(8)->setMinute(0)->setSecond(0);
            $end = now()->setHour(21)->setMinute(0)->setSecond(0);
        }

        $intervals = CarbonInterval::minutes(30)->toPeriod($start, $end)->toArray();

        return collect($intervals)
            ->mapWithKeys(function (Carbon $interval) {
                return [
                    $interval->format('H:i') => [
                        'available' => false,
                        'users' => []
                    ],
                ];
            });
    }

    private function fetchAvailabilities(LocationEnum $location, Carbon $date)
    {
        return Http::withHeaders([
            'X-FederationID' => 'df82f4dd-fd87-4af5-9c2f-656fe1a44357',
        ])->get(self::BASE_URL, [
            'locationId' => $location->getUuid(),
            'playingTimes[]' => 60,
            'date' => $date->format('Y-m-d'),
            'types[]' => $location->getType(),
        ])->collect()
            ->recursive()
            ->first();
    }

    private function getUserAvailabilities(Carbon $date, LocationEnum $location): Collection
    {
        return UserAvailability::query()
            ->with('user')
            ->whereDate('date', '=', $date)
            ->whereHas('location', function(Builder $query) use ($location) {
                $query->where('uuid', '=', $location->getUuid());
            })
            ->get();
    }

    public function toggleAvailability(User $user, Location $location, string $timeSlot, Carbon $date)
    {
        $availability = $user->availabilities()
            ->where('location_id', '=', $location->id)
            ->whereDate('date', '=', $date)
            ->where('time', '=', $timeSlot);

        if($availability->exists()) {
            $availability->delete();
            return;
        }

        $user->availabilities()->create([
            'location_id' => $location->id,
            'date' => $date,
            'time' => $timeSlot,
        ]);
    }
}
