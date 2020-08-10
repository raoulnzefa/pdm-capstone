<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclinedCancellationNotification extends Notification
{
    use Queueable;

    protected $cancellation;
    protected $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cancellation, $msg)
    {
        $this->cancellation = $cancellation;
        $this->msg = $msg;
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
            ->subject('Declined Cancellation')
            ->markdown('mail.order.declined_cancellation', [
                'cancellation' => $this->cancellation,
                'msg' => $this->msg,
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
