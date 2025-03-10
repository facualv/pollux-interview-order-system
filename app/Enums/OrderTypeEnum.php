<?php

namespace App\Enums;

enum OrderTypeEnum: string
{
    case DELIVERY = 'received';
    case PICKUP = 'pickup';

}
