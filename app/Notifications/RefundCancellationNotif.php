<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RefundCancellationNotif extends Notification
{
    use Queueable;

    protected $cancellation;
    protected $amount_detail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cancellation, $amount_detail)
    {
        $this->cancellation = $cancellation;
        $this->amount_detail = $amount_detail;
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
            ->subject('Refund Cancellation')
            ->markdown('mail.order.refund_cancellation', [
                'cancellation' => $this->cancellation,
                'amount_detail' => $this->amount_detail,
                'url' => route('customer.cancellation')
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
