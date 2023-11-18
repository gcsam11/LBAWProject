<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = "comment";

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