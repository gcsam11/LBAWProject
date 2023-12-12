<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Through;


class Post extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = "post";

    // Define the columns that can be filled
    protected $fillable = [
        'title',
        'caption',
        'postdate',
        'upvotes',
        'downvotes',
        'user_id',
        'topic_id',
        'video_id'
    ];

    // Disable timestamps
    public $timestamps = false;

    /**
     * Get the user that created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the topic associated with the post.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the video associated with the post.
     */
    public function video()
    {
        return $this->HasOne(Video::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the upvotes for the post.
     */
    public function upvotes()
    {
        return $this->hasMany(UpvotePost::class);
    }

    /**
     * Get the images for the post.
     */
    public function images()
    {
        return $this->hasManyThrough(
            Image::class,
            ImagePost::class,
            'post_id', // Foreign key on ImagePost table...
            'id', // Foreign key on Images table...
            'id', // Local key on Posts table...
            'image_id' // Local key on ImagePost table...
        );
    }

}

?>