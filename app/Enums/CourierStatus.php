<?php

namespace App\Enums;

enum CourierStatus: string
{
    case ASSIGNED = 'Принят';
    case WAITING = 'Ожидание в ресторане';
    case PICKED_UP = 'Забрал заказ';
    case ON_THE_WAY = 'Прибыл к клиенту';
    case DELIVERED = 'Доставлено';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
