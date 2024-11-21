<?php

namespace App\Enums;

enum ClientStatus: string
{
    case PREPARING = 'Ваш заказ готовится';
    case ON_THE_WAY = 'В пути';
    case DELIVERED = 'Доставлено';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
