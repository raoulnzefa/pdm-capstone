<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnRefundApprovedNotif extends Notification
{
    use Queueable;

    protected $return_refund;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($return_refund)
    {
        $this->return_refund = $return_refund;
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
                ->subject('Approved Return')
                ->markdown('mail.order.return_refund_approved', [
                    'return_refund' => $this->return_refund,
                    'url' => route('customer.return_requests')
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
