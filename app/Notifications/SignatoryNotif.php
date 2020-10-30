<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SignatoryNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $request;
    protected $employee;

    public function __construct($user,$request,$employee)
    {
        //
        $this->user = $user;
        $this->request = $request;
        $this->employee = $employee;

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
                    ->subject('Accountabilities')
                    ->line('Dear '.$this->user->name.',')
                    ->line($this->employee->name.", ".$this->employee->position." from the ".$this->employee->department_name." Department of ".$this->employee->company_name." has tendered resignation effective ".date('M. d, Y',strtotime($this->request->last_day)).". Please check and indicate the employee's accountabilities with amount (if applicable) under your department.")
                    ->action('(Click Button)', url('/for-clearance'))
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
