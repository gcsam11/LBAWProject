<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = "comment";

    protected $fillable = [
        'title',
        'caption',
        'commentdate',
        'upvotes',
        'downvotes',
        'post_id',
        'user_id',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin that owns the comment.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }


    /**
     * Get the post that owns the comment.
     */

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

}
?>