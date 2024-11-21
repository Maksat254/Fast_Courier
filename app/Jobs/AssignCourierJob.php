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

  protected function __construct(Order $order, $radius = 1 )
  {
      $this->order = $order;
      $this->radius = $radius;
  }

  protected function handle(): void
  {
      $courier = $this->findCourierInRadius($this->radius);

      if (!$courier && $this->radius < $this->maxRadius);
      {
          $this->radius = $this->maxRadius;
          $courier = $this->findCourierInRadius($this->radius);
      }
      if ($courier) {
          $this->order->courier_id = $courier->id;
          $this->order->status = 'Ожидаем курьера';
          $this->order->save();


          Notification::send($courier, new NewOrderAssigned($this->order));


          $maxWaitTime = 30;
          $waited = 0;

          while ($waited < $maxWaitTime) {
              $order = $this->order->fresh();

              if ($order->status !== 'awaiting_confirmation') {
                  Log::info('Order ID ' . $this->order->id . ' принято курьером ' . $courier->id);
                  return;
              }

              sleep(1);
              $waited++;
          }

          Log::info('Courier ID ' . $courier->id . ' не принял заказ' . $this->order->id);
          $this->order->courier_id = null;
          $this->order->save();


          $this->waitForAcceptance($courier);
      }else{
          Log::info('Для назначения заказа не найден доступный курьер');
      }
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

  protected function waitForAcceptance($courier)
  {
      $startTime = now();

      while (now()->diffInSeconds($startTime) < 30){
          $order = $this->order->fresh();
          if ($order->status ===  'ожидание_подтверждения') {
              sleep(1);
          } else {
              return;
          }
      }

      Log::info('Курьер не принял заказ в течение 30 секунд, переназначаем заказ');
      $this->handle();
  }

}
