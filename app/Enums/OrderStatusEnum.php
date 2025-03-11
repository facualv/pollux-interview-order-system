<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case RECEIVED = 'Received';
    case CANCELED = 'Canceled';
    case DELIVERED = 'Delivered';

}
