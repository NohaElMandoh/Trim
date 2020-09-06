<?php

namespace Modules\Coupon\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewCouponNotification extends Notification
{
    use Queueable;
    private $coupon;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coupon)
    {
        $this->coupon   = $coupon;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', 'https://laravel.com')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $return =  [
            'notif_id'      => $this->id,
            'id'            => $this->coupon->id,
            'name'          => auth()->user()->name,
            'image'         => route('file_show', $this->coupon->image),
            'action'        => $this->coupon->title,
            'event'         => 'new_coupon',
            'url'           => '',
        ];
        send_notif($this->coupon->title, $return, '', $notifiable->tokens->pluck('token'));
        return $return;
    }
}
