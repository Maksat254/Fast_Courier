<?php
namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;

class OrderStatusService
{
    /**
     * Смена статуса заказа.
     *
     * @param Order $order
     * @param OrderStatus $newStatus
     * @return void
     * @throws \Exception
     */
    public function changeStatus(Order $order, OrderStatus $newStatus): void
    {
        $currentStatus = OrderStatus::from($order->status);

//        $allowedTransitions = [
//            OrderStatus::NEW => [OrderStatus::PREPARING],
//            OrderStatus::PREPARING => [OrderStatus::READY],
//            OrderStatus::READY => [OrderStatus::ON_THE_WAY],
//            OrderStatus::ON_THE_WAY => [OrderStatus::DELIVERED],
//        ];
//
//        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
//            throw new \Exception('Недопустимый переход между статусами.');
//        }

        $order->status = $newStatus->value;
        $order->save();
    }
}
