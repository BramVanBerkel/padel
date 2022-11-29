<?php

namespace App\Http\Controllers\Api;

use App\Enums\LocationEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeSlotRequest;
use App\Models\Location;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\MatchService;
use Illuminate\Support\Carbon;

class TimeSlotController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly MatchService $matchService,
    ) {
    }

    public function index(LocationEnum $location, string $date)
    {
        return $this->availabilityService->getAvailabilities(
            location: $location,
            date: Carbon::parse($date),
        );
    }

    public function toggle(TimeSlotRequest $request)
    {
        $locationEnum = LocationEnum::from($request->location);

        $this->availabilityService->toggleAvailability(
            User::find($request->user_id),
            Location::firstWhere('uuid', $locationEnum->getUuid()),
            $request->timeslot,
            Carbon::parse($request->date),
        );
    }

    public function matches()
    {
        return $this->matchService->getMatches();
    }
}
