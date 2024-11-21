<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {

        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новый заказ назначен')
            ->line('Вам назначен новый заказ. Пожалуйста, подтвердите его в течение 30 секунд.')
            ->action('Принять заказ', url('/orders/'.$this->order->id.'/confirm'))
            ->line('Спасибо');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Вам назначен новый заказ. Пожалуйста, подтвердите его в течение 30 секунд.',
        ];
    }
}
