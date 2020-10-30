<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UploadLetter extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $approver;
    protected $user;
    protected $letter;

    public function __construct($approver,$user,$letter)
    {
        //
        $this->approver = $approver;
        $this->user =$user;
        $this->letter =$letter;
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
    {       return (new MailMessage)
        ->greeting(' ')
        ->subject('For Verification')
        ->line('Dear '.$this->user->name.',')
        ->line('Your employee, '.$this->approver->name.' , has tendered resignation effective '.date('F. d, Y',strtotime($this->letter->last_day)).'. Kindly check the uploaded details to proceed with clearance processing.')
        ->action('(click button)',  url('/uploaded-letter'))
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
