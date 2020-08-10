<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnReplacementApprovedNotif extends Notification
{
    use Queueable;

    protected $return_replacement;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($return_replacement)
    {
        $this->return_replacement = $return_replacement;
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
            ->subject('Approved Replacement')
            ->markdown('mail.order.return_replacement_approved', [
                'return_replacement' => $this->return_replacement,
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
