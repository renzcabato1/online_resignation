<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChangeEffectiveDate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $new_clearance;
    protected $request;
    public function __construct($user,$new_clearance,$request)
    {
        //
        $this->user = $user;
        
        $this->new_clearance = $new_clearance;
        
        $this->request = $request;
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
        ->greeting(' ')
        ->subject('Change of Effectivity Date')
        ->line('Dear '.$this->user->name.',')
        ->line('Please be informed that the effectivity of the resignation of '.$this->user->name.' has been changed from '.date('M. d, Y',strtotime($this->new_clearance->effective_date)).' TO '.date('M. d, Y',strtotime($this->request->last_day)).'.
        If you have concerns on this, please get in touch with HR immediately.
        Thank you.')
        ->line('This is an auto generated email please do not reply!')
        ->line('Thank you for using our application!');
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
