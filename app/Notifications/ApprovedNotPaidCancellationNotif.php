<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovedNotPaidCancellationNotif extends Notification
{
    use Queueable;

    protected $cancellation;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cancellation)
    {
        $this->cancellation = $cancellation;
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
                    ->subject('Approved Cancellation')
                    ->markdown('mail.order.approved_not_paid_cancellation', [
                        'cancellation'=> $this->cancellation,
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
