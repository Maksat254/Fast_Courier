<?php
namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'Новый заказ';
    case PREPARING = 'Ваш заказ готовится';
    case READY = 'Готов к выдаче';
    case ON_THE_WAY = 'В пути';
    case DELIVERED = 'Доставлено';
    case ASSIGNED = 'Принят';
    case WAITING = 'Ожидание в ресторане';
    case PICKED_UP = 'Забрал заказ';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
