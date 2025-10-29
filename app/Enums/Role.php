<?php

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
}
