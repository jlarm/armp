<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case CONSULTANT = 'consultant';
    case OWNER = 'owner';
    case CFO = 'cfo';
    case GM = 'gm';
    case GSM = 'gsm';
    case MANAGER = 'manager';
    case EMPLOYEE = 'employee';
    case PORTER = 'porter';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => (string) __('Admin'),
            self::CONSULTANT => (string) __('Consultant'),
            self::OWNER => (string) __('Owner'),
            self::CFO => (string) __('CFO'),
            self::GM => (string) __('GM'),
            self::GSM => (string) __('GSM'),
            self::MANAGER => (string) __('Manager'),
            self::EMPLOYEE => (string) __('Employee'),
            self::PORTER => (string) __('Porter'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'lime',
            self::CONSULTANT => 'grey',
            self::OWNER => 'blue',
            self::CFO => 'green',
            self::GM => 'yellow',
            self::GSM => 'red',
            self::MANAGER => 'cyan',
            self::EMPLOYEE => 'orange',
            self::PORTER => 'purple',
        };
    }
}
