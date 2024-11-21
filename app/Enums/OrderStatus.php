<?php
namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'Новый заказ';
    case PREPARING = 'Готовится';
    case READY = 'Готов к выдаче';
    case ON_THE_WAY = 'В пути';
    case DELIVERED = 'Доставлено';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
