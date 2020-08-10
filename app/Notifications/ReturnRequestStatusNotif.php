<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnRequestStatusNotif extends Notification
{
    use Queueable;

    protected $return_detail, $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($return_detail, $url)
    {
        $this->url = $url;
        $this->return_detail = $return_detail;
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
        $subject = 'Return Request Status';
        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('mail.order.return_request_status', ['return_detail'=>$this->return_detail, 'url' => $this->url]);
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
