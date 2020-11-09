<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CompanyDetails;

class ActivationLink extends Notification
{
    use Queueable;

    protected $cust;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cust)
    {
        $this->cust = $cust;
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
        $url = url('/email-verified/'.$this->cust->email.'/'.$this->cust->token);

        return (new MailMessage)
                    ->subject('Email Verification')
                    ->markdown('notification.activation_link', [
                        'url'=>$url,
                        'company'=>CompanyDetails::first(),
                        'customer' => $this->cust
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
