<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Post;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewPostCreatedNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Post $post)
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
                    ->line('Hello ' . $notifiable->name)
                    ->line('A new post has been created by ' . $this->user->name)
                    ->action('Show me', url('/posts/' . $this->post->id));
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
