<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Post;

class UpvotedPost extends Notification
{

    
    protected $upvoter;
    protected $post;

    public function __construct(User $upvoter, Post $post)
    {
        $this->upvoter = $upvoter;
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'upvoter_name' => $this->upvoter->name,
            'post_title' => $this->post->title,
        ];
    }
}
?>