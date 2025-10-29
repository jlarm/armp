<?php

namespace App\Enums;

enum Department: string
{
    case SALES = 'sales';
    case ACCOUNTING = 'accounting';
    case SERVICE = 'service';
    case PARTS = 'parts';
    case BODYSHOP = 'bodyshop';
    case FINANCE = 'finance';
}
