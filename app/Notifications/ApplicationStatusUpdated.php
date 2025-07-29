<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;



class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusMessages = [
            'approved' => "Hồ sơ xét tuyển (ID: {$this->application->id}) của bạn đã được duyệt.",
            'rejected' => "Rất tiếc, hồ sơ xét tuyển (ID: {$this->application->id}) của bạn đã bị từ chối.",
            'processing' => "Hồ sơ xét tuyển (ID: {$this->application->id}) của bạn đang được xử lý.",
        ];

        return [
            'application_id' => $this->application->id,
            'message' => $statusMessages[$this->application->status] ?? 'Trạng thái hồ sơ của bạn đã được cập nhật.',
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
   {
    $statusLabels = [
        'approved' => 'Đã duyệt',
        'rejected' => 'Bị từ chối',
        'processing' => 'Đang xử lý',
    ];

    $status = $this->application->status;
    $label = $statusLabels[$status] ?? 'Không xác định';

    return (new MailMessage)
        ->subject('Cập nhật trạng thái hồ sơ')
        ->greeting('Xin chào ' . $notifiable->name . '!')
        ->line('Trạng thái hồ sơ của bạn vừa được cập nhật:')
        ->line('→ Trạng thái mới: ' . $label)
        ->action('Xem hồ sơ của bạn', url('/applications/' . $this->application->id))
        ->line('Cảm ơn bạn đã sử dụng hệ thống!');
    }
} 
