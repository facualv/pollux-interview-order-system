<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case RECEIVED = 'received';
    case CANCELED = 'canceled';
    case DELIVERED = 'delivered';

}
