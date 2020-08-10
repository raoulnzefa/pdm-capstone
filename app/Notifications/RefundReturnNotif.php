<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RefundReturnNotif extends Notification
{
    use Queueable;

    protected $return_refund;
    protected $amount_detail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($return_refund, $amount_detail)
    {
        $this->return_refund = $return_refund;
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
            ->subject('Refund Return')
            ->markdown('mail.order.refund_return', [
                'return_refund' => $this->return_refund,
                'amount_detail' => $this->amount_detail,
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
