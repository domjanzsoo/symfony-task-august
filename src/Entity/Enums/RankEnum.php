<?php

namespace App\Entity\Enums;

enum RankEnum: string
{
    case NAVY = 'Navy';
    case ARMY = 'Army';
    case STARFIGHTER = 'Starfighter';
    case ADMIRAL = 'Admiral';
    case GENERAL = 'General';
    case CHIEF_MARSHAL = 'Chief Marshal';
    case VICE_ADMIRAL = 'Vice Admiral';
    case MARSHAL = 'Marshal';
    case MAJOR_GENERAL = 'Major General';
    case COMMODORE = 'Commodore';
    case FLEET_COMMODORE = 'Fleet Commodore';
    case MOFF = 'Moff';
    case GOVERNOR = 'Governor';
    case GRAND_MOFF = 'Grand Moff';
    case GRAND_ADMIRAL = 'Grand Admiral';

    public static function toArray(): array
    {
        $array = [];

        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }

        return $array;
    }

    public static function fromName(?string $name): ?string
    {
        return isset($name) ? constant("self::$name")->value : null;
    }
}