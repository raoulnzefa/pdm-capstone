<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductReplacementNewOrderNotif extends Notification
{
    use Queueable;

    protected $new_order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($new_order)
    {
        $this->new_order = $new_order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Replacement')
                ->markdown('mail.order.replacement_new_order', [
                    'order' => $this->new_order,
                    'url' => route('customer.view_order', ['order'=>$this->new_order->number])
                ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
