<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignCourierJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $radius;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, $radius = 1)
    {
        $this->order = $order;
        $this->radius = $radius;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Попытаться найти ближайшего курьера
        $courier = $this->findNearbyCourier();

        // Если курьер найден, назначаем его
        if ($courier) {
            $this->order->courier_id = $courier->id;
            $this->order->status = 'awaiting_confirmation';
            $this->order->save();

            // Отправляем уведомление курьеру
            Notification::send($courier, new NewOrderAssigned($this->order));

            // Ожидание 30 секунд перед повторной попыткой назначить заказ (если нужно)
            // Если нужно просто подождать определенное время перед повторным назначением
            AssignCourierJob::dispatch($this->order, $this->radius)->delay(now()->addSeconds(30));
        } else {
            Log::info('No available courier found for order ID: ' . $this->order->id);
            // Возможно, стоит выполнить дополнительные действия, если курьер не найден
        }
    }

    /**
     * Функция для поиска ближайшего курьера.
     */
    protected function findNearbyCourier()
    {
        $restaurant = $this->order->restaurant;

        // Используем DB::raw для вычисления расстояния
        $courier = Courier::selectRaw("
            *,
            6371 * acos(cos(radians(?))
            * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?))
            * sin(radians(latitude))) AS distance", [
            $restaurant->latitude,
            $restaurant->longitude,
            $restaurant->latitude
        ])
            // Используем where вместо having для фильтрации по расстоянию
            ->whereRaw("6371 * acos(cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?))
                * sin(radians(latitude))) <= ?", [
                $restaurant->latitude,
                $restaurant->longitude,
                $restaurant->latitude,
                $this->radius // радиус в километрах
            ])
            ->whereDoesntHave('orders', function ($query) {
                // Исключаем курьеров с заказами в статусе "awaiting_confirmation"
                $query->where('status', 'awaiting_confirmation');
            })
            ->orderBy('distance', 'asc') // Сортировка по расстоянию
            ->first(); // Возвращаем первого курьера (самого ближайшего)

        return $courier;
    }
}
