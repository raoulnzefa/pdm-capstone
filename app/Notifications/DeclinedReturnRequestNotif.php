<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclinedReturnRequestNotif extends Notification
{
    use Queueable;

    protected $return_declined;
    protected $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($return_declined, $msg)
    {
        $this->return_declined = $return_declined;
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
                ->subject('Declined Return')
                ->markdown('mail.order.declined_return_request', [
                    'return_declined' => $this->return_declined,
                    'msg' => $this->msg,
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
