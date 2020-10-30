<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclinedResignation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $cancel_rl;
    public function __construct($user,$cancel_rl)
    {
        //
        $this->user = $user;
        $this->cancel_rl = $cancel_rl;
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
        ->subject('Declined Resignation')
        ->line('Dear '.$this->user->name.',')
        ->line('For us to be able to proceed to the next steps, you need to discuss further your resignation submitted '.date('M. d, Y',strtotime($this->cancel_rl->created_at)).', effective '.date('M. d, Y',strtotime($this->cancel_rl->last_day)).' with your immediate superior and department head for clarification.')
        // ->action('(click button)', $this->server->server_ip.'/uploaded-letter')
        ->line('You may also approach Human Resources for any questions or concerns.')
        ->line('This is an auto generated email please do not reply')
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
