<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invite;

class InvitationFamilyNotification extends Notification
{
    use Queueable;
    
    protected $invite;
    // public $token;
    // public $mail;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invite $invite)
    // public function __construct(string $token, BareMail $mail)
    // public function __construct()
    {
        $this->invite = $invite;
        // $this->token = $token;
        // $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    // public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = route('register.invited.{token}', ['token' => $this->invite->token]);
        
        return (new MailMessage)
            ->subject('家族への招待')
            ->line('あなたは家族メンバーとして招待されました。')
            ->action('登録する', $url)
            ->line('この招待の有効期限は24時間です。');
    }    
  
        // return $this->mail
        //     ->from(config('mail.from.address'), config('mail.from.name'))
        //     ->to($notifiable->email)
        //     ->subject('[Hon&Me] 家族招待')
        //     ->text('emails.invite')
        //     ->with([
        //         'url' => route('register.invited.{token}', [
        //             'token' => $this->token,
                    // 'token' => $notifiable->token,
    
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    
    public function toArray($notifiable)
    // public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
