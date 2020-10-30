<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Server;
class ApproveResignation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $employee_info;
    protected $account;
    public function __construct($employee_info,$account)
    {
        //
        $this->employee_info = $employee_info;
        $this->account = $account;
        $this->server = Server::first();
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
            ->subject('Approved Resignation')
            ->greeting('Dear '.$this->account->name.',')
            ->line('The resignation of '.$this->employee_info->name.', '.$this->employee_info->position.' from the '.$this->employee_info->department_name.' Department of '.$this->employee_info->company_name.' has been approved by '.auth()->user()->name.'.')
            ->line('Please proceed with next steps of the clearance.')
            ->action('(click button)',URL('/resigned-employee'))
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
