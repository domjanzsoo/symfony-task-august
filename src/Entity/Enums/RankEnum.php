<?php

namespace App\Entity\Enums;

use Exception;

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

    public static function getRankRoles(string $name): array
    {
        if (!is_object(constant("self::$name"))) {
            throw Exception('The given rank does not exist!');
        }

        switch ($name) {
            case self::NAVY->name:
            case self::ARMY->name:
            case self::STARFIGHTER->name:
                return ['ROLE_USER'];
                break;
            case self::VICE_ADMIRAL->name:
            case self::ADMIRAL->name:
            case self::GENERAL->name:
                return ['ROLE_USER', 'ROLE_MANAGE_USER'];
                break;
            case self::CHIEF_MARSHAL->name:
            case self::MARSHAL->name:
            case self::MAJOR_GENERAL->name:
            case self::FLEET_COMMODORE->name:
            case self::COMMODORE->name:
            case self::MOFF->name:
            case self::GRAND_ADMIRAL->name:
                return ['ROLE_USER', 'ROLE_MANAGE_USER', 'ROLE_MANAGE_USER_PLUS'];
                break;
            case self::GRAND_MOFF->name:
            case self::GOVERNOR->name:
                return ['ROLE_USER', 'ROLE_MANAGE_USER', 'ROLE_MANAGE_USER_PLUS', 'ROLE_ABSOLUTE_ACCESS'];
                break;
            default:
                return [];
        }
    }
}