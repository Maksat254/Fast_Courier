<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order status based on the time of creation';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('created_at', '<', Carbon::now()->subMinutes(30))
            ->whereIn('status', ['Новый заказ', 'Ваш заказ готовится', 'Готов к выдаче', 'В пути'])
            ->get();

        foreach ($orders as $order) {
            $order->status = 'Доставлено';
            $order->save();
            $this->info("Order #{$order->id} status updated to {$order->status}");
        }
    }
}
