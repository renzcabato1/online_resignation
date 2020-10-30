<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AcceptResignation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $employee_info;
    public function __construct($employee_info)
    {
        //
        $this->employee_info = $employee_info;
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
        ->subject('Accept Resignation')
        ->line('Dear '.$this->employee_info->name.',')
        ->line('Your resignation submitted '.date('M. d, Y',strtotime($this->employee_info->upload_date)).' , effective '.date('M. d, Y',strtotime($this->employee_info->last_day)).'  was approved by '.auth()->user()->name.'. A copy of this notice shall be given to Human Resources for clearance preparation.')
        // ->action('(click button)', $this->server->server_ip.'/uploaded-letter')
        ->line('An e-mail indicating the next steps shall be sent to you shortly. ')
        ->line('Please check the Offboarding System and your e-mail regularly for updates. ')
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
