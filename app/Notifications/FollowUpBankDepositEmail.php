<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CompanyDetails;

class FollowUpBankDepositEmail extends Notification
{
    use Queueable;

    protected $order;
    protected $due_date;
    protected $bank_account;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $due_date, $bank_account)
    {
        $this->order = $order;
        $this->due_date = $due_date;
        $this->bank_account = $bank_account;
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
                    ->subject('Payment for Order #'.$this->order->number)
                    ->markdown('mail.order.follow_up_bank_deposit',[
                        'order'=>$this->order, 
                        'url' => route('customer.view_order',['order'=>$this->order->number]), 
                        'due_date'=>$this->due_date, 
                        'bank_account'=> $this->bank_account,
                        'company'=>CompanyDetails::first()
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
