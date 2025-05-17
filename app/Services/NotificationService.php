<?php
namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    public function sendFriendRequestNotification(User $sender,User $receiver)
    {
        $receiver->notifications()->create([
            'sender_id' => $sender->id,
            'type' => 'friend_request',
            'data' => json_encode([
                'message' => 'send friend request for you',
                'friend_request_id' => $sender->id
            ])
        ]);
    }
    public function acceptFriendRequestNotification(User $sender ,User $receiver)
    {
               $receiver->notifications()->create([
            'sender_id' => $sender->id,
            'type' => 'accept_friend_request',
            'data' => json_encode([
                'message' => 'accept your friend request',
                'friend_request_id' => $sender->id
            ])
        ]);
    }


     public function sendLikeNotification(User $sender, User $receiver, $postId)
    {
        $receiver->notifications()->create([
            'sender_id' => $sender->id,
            'type' => 'like',
            'data' => json_encode([
                'message' => 'like to your post',
                'post_id' => $postId
            ])
        ]);
    }
      public static function sendCommentNotification(User $sender, User $receiver, $postId,Comment $comment)
    {
        $receiver->notifications()->create([
            'sender_id' => $sender->id,
            'type' => 'comment',
            'data' => json_encode([
                'message' => 'comment to your post',
                'post_id' => $postId,
                'comment' => $comment->comment
            ])
        ]);
    }



}
