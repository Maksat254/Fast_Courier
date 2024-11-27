<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Bus\Queueable;
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
  protected $maxRadius = 7;

  public function __construct(Order $order, $radius = 1 )
  {
      $this->order = $order;
      $this->radius = $radius;
  }

    public static function dispatch($order)
    {
    }

    protected function handle(): void
    {
        for ($this->radius = 1; $this->radius <= $this->maxRadius; $this->radius++) {
            $courier = $this->findCourierInRadius($this->radius);

            if ($courier) {
                $this->order->courier_id = $courier->id;
                $this->order->status = 'Ожидаем курьера';
                $this->order->save();

//                Notification::send($courier, new NewOrderAssigned($this->order));


                Log::info('Order ID ' . $this->order->id . ' назначен курьеру ' . $courier->id);
                return;
            }

            Log::info("Курьер не найден в радиусе " . $this->radius . " км.");
        }

        Log::info('Курьер не найден в радиусе до ' . $this->maxRadius . ' км. Начинаем поиск с 1 км.');

        $this->radius = 1;
        $this->handle();
    }

    protected function findCourierInRadius($radius)
  {
      $restaurant = $this->order->restaurant;

      return Courier::selectRaw("
       *,
            6371 * acos(cos(radians(?))
            * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?))
            * sin(radians(latitude))) AS distance",[
          $restaurant->latitude,
          $restaurant->longitude,
          $restaurant->latitude
      ])
          ->whereRaw("6371 * acos(cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?))
                * sin(radians(latitude))) <= ?", [
              $restaurant->latitude,
              $restaurant->longitude,
              $restaurant->latitude,
              $radius
          ])
          ->whereDoesntHave('orders', function ($query) {
              $query->where('status', 'awaiting_confirmation');
          })
          ->orderBy('distance', 'asc')
          ->first();
  }
}
