<?php

namespace App\Enums;

enum LocationEnum: string
{
    case VECHTSEBANEN = 'vechtsebanen';
    case ZEEHAENKADE = 'zeehaenkade';

    public function getUuid(): string
    {
        return match ($this) {
            self::VECHTSEBANEN => 'd9b0cde9-9ab6-4859-87b3-563c7ba1ccb9',
            self::ZEEHAENKADE => 'f37fb2ae-bf24-44f1-9b81-61e6c0784840',
        };
    }

    public function getType(): int
    {
        return match ($this) {
            self::VECHTSEBANEN => 5,
            self::ZEEHAENKADE => 13,
        };
    }
}
