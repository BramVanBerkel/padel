<?php

namespace App\Http\Requests;

use App\Enums\LocationEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TimeSlotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'date' => [
                'required',
                'date',
                'string',
            ],
            'location' => [
                'required',
                new Enum(LocationEnum::class),
            ],
            'timeslot' => [
                'required',
            ]
        ];
    }
}
