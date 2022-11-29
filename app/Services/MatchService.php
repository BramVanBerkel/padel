<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

/**
 * A match is a timeslot where 4 or more people are available
 */
class MatchService
{
    public function getMatches()
    {
        return DB::table('user_availabilities')
            ->select([
                'users.name as user',
                'user_availabilities.time',
                'user_availabilities.date',
                'locations.name as location',
            ])
            ->joinSub(function(Builder $query) {
                $query->select([
                    'user_availabilities.time',
                    'user_availabilities.date',
                    'user_availabilities.location_id',
                ])
                    ->from('user_availabilities')
                    ->groupBy([
                        'user_availabilities.time',
                        'user_availabilities.date',
                        'user_availabilities.location_id',
                    ])
                    ->having(DB::raw('count(*)'), '>=', 4);
            }, 'matches', function(JoinClause $join) {
                $join->on('matches.time', '=', 'user_availabilities.time')
                    ->whereColumn('matches.date', '=', 'user_availabilities.date')
                    ->whereColumn('matches.location_id', '=', 'user_availabilities.location_id');
            })
            ->join('users', 'user_availabilities.user_id', '=', 'users.id')
            ->join('locations', 'user_availabilities.location_id', '=', 'locations.id')
            ->get()
            ->groupBy([
                'location', 'date', 'time'
            ]);
    }
}
