<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApEmailNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
       
    protected $employee_info;
    protected $copy_email;
    protected $request;

    public function __construct($employee_info,$copy_email,$request)
    {
        //
        $this->employee = $employee_info;
        $this->copy_email = $copy_email;
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
            ->subject('Accountabilities')
            ->line('Dear '.$this->copy_email->name.',')
            ->line($this->employee->name.", ".$this->employee->position." from the ".$this->employee->department_name." Department of ".$this->employee->company_name." has tendered resignation effective ".date('M. d, Y',strtotime($this->request->last_day)).". Please check accountabilities and ensure we do not release payments for Globe, Cash Advance and other reimbursements without coordinating with HR.")
            // ->action('(Click Button)', url('/for-clearance'))
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
