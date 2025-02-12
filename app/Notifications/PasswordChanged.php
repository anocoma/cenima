<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Mật khẩu của bạn đã được thay đổi')
                ->greeting('Xin chào, ' . $notifiable->name)
                ->line('Mật khẩu của bạn đã được thay đổi thành công.')
                ->line('Nếu bạn đã yêu cầu thay đổi mật khẩu, bạn không cần phải làm gì thêm.')
                ->line('Nếu bạn không thực hiện thay đổi này, hãy liên hệ với chúng tôi ngay lập tức.')
                ->action('Đăng nhập', url('/login'))
                ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!')
                ->line('Trân trọng,')
                ->line('Đội ngũ hỗ trợ ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
