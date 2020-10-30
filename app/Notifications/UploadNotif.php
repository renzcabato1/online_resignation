<?php

namespace App\Notifications;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UploadNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $resign_data;
    protected $user;
    public function __construct($user,$resign_data)
    {
        //
        $this->user = $user;
        $this->server = Server::first();
        $this->name = auth()->user()->name;
        $this->date_effective = $resign_data;
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
                    ->subject('Resigned Employee')
                    ->greeting(' ')
                    ->line('Dear '.$this->user->name.',')
                    ->line($this->name.' has uploaded your resignation letter with effectivity date of '.date('M. d, Y',strtotime($this->date_effective)).'. You may now start processing your clearance by clicking on the link below. Please coordinate with the following departments regarding your accountabilities. ')
                    ->line('    - DEPARTMENT HEAD')
                    ->line('    - DIVISION HEAD')
                    ->line('    - CENTRAL PURCHASING UNIT')
                    ->line('    - INFORMATION TECHNOLOGY')
                    ->line('    - HUMAN RESOURCES')
                    ->line('    - ADMIN AND GENERAL SERVICES')
                    ->line('    - FINANCE')
                    ->line('    - INTERNAL AUDIT')
                    ->line('    - LEGAL')
                    ->line('These should be cleared and settled not later than your effectivity date. This also authorizes the company to deduct from your accrued wages, or any kind of compensation due to you, your unsettled or pending accountabilities.Please note that this is without prejudice to the results of an audit investigation if any, conducted after clearance has been granted. ')
                    ->line('Please click below to start your clearance process.')
                    ->action('Click button', $this->server->server_ip.'/clearance')
                    ->line('This is an auto generated email please do not reply.')
                    ->line('Thank you for using our application');
                    
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
