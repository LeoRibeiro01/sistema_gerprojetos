<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Pm = 'pm';
    case Manager = 'manager';
    case ScrumMaster = 'scrum_master';
    case Dev = 'dev';
    case Client = 'client';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<string>
     */
    public static function internalTeam(): array
    {
        return [self::Admin->value, self::Pm->value, self::Manager->value, self::ScrumMaster->value, self::Dev->value];
    }
}
