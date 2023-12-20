<?php
 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use App\Models\UpvoteComment;
use App\Models\DownvoteComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use App\Events\UpvoteComment as UpvoteCommentEvent;
use App\Events\DownvoteComment as DownvoteCommentEvent;
use App\Events\UndoUpvoteComment;
use App\Events\UndoDownvoteComment;

class CommentController extends Controller
{
    
    /**
     * Shows all comments for a post.
     */
    public function showForPost($postId)
    {
        // Find the post.
        $post = Post::findOrFail($postId);

        // Get all comments for the post ordered by date.
        $comments = $post->comments()->orderByDesc('commentdate')->get();

        // Use the pages.comments template to display all comments.
        return view('pages.post', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Shows all posts.
     */
    public function list()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get posts for user ordered by date.
            $posts = Auth::user()->posts()->orderByDesc('postdate')->get();

            // Check if the current user can list the posts.
            $this->authorize('list', comment::class);

            // The current user is authorized to list posts.

            // Use the pages.posts template to display all posts.
            return view('pages.posts', [
                'posts' => $posts
            ]);
        }
    }

    /**
     * Creates a new comment.
     */
    public function create(Request $request, int $id)
    {
        // Find the post.
        $post = Post::findOrFail($id);

        // Create a new comment instance.
        $comment = new Comment();

        // Check if the current user is authorized to create this comment.
        $this->authorize('create', $comment);

        // Validate the request data.
        $request->validate([
            'title' => ['required'],
            'caption' => ['required'],
            'image' =>  'image|max:2048|mimes:jpg,jpeg,svg,gif,png',
        ]);

        // Set comment details.
        $comment = Auth::user()->comments()->create([
            'title' => $request->input('title'),
            'caption' => $request->input('caption'),
            'commentdate' => now(), // Set the current date and time.
            'user_id' => Auth::user()->id,
            'post_id' => $request->route('id')
        ]);

        // Save the comment
        $comment->save();

        // Check if image is not null
        if (!empty($request->image)) {
            
            $response = ImageCommentController::create($request, $comment->id);
            if ($response != '200') {
                // Delete the comment
                $comment->delete();

                // Return error Message                
                return redirect()->route('posts.show', ['id' => $id])->with('error', 'Could not create comment.');
            }
        }

        return redirect()->route('posts.show', ['id' => $id])->with('success', 'Comment created successfully');
    }

    /**
    * Show the form for editing a specific comment.
    */
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);

        // Check if the current user is authorized to edit this comment
        $this->authorize('update', $comment);

        return view('pages.edit_comment', ['comment' => $comment]);
    }

    /**
    * Update the specified comment in storage.
    */
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Check if the current user is authorized to update this comment
        $this->authorize('update', $comment);

        $request->validate([
            'title' => ['required'],
            'caption' => ['required'],
        ]);

        // Update comment details
        $comment->title = $request->input('title');
        $comment->caption = $request->input('caption');

        // Save the updated comment
        $comment->save();

        return redirect()->route('posts.show', ['id' => $comment->post_id])->with('success', 'Comment updated successfully');
    }

    /**
     * Delete a comment.
     */
    public function delete(Request $request, $id)
    {
        // Find the comment.
        $comment = Comment::findOrFail($id);

        // Check if the current user is authorized to delete this comment.
        $this->authorize('delete', $comment);

        try {
            $comment->delete();
            return redirect()->route('posts.show', ['id' => $comment->post_id])->with('success', 'Comment deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to delete comment with ID: ' . $comment->id . '. Error: ' . $e->getMessage());
            return redirect()->route('posts.show', ['id' => $comment->post_id])->with('error', 'Failed to delete the comment');
        }
    }

    /**
     * Upvote a comment.
     */
    public function upvote(Request $request)
    {
        $commentId = $request->id;
        $userId = Auth::id();

        $upvoteComment = new UpvoteComment();
        $upvoteComment->comment_id = $commentId;
        $upvoteComment->user_id = $userId;

        if ($upvoteComment->save()) {
            event(new UpvoteCommentEvent($commentId));
        } else {
            \Log::info('Failed to upvote comment with ID: ' . $commentId);
        }

        $comment = Comment::findOrFail($commentId);
        $upvotes = $comment->upvotes;
        $commentOwner = User::findOrFail($comment->user_id);
        $upvoter = User::findOrFail($userId);

        return response()->json($upvotes, 200);
    }

    /**
     * Undo upvote for a comment.
     */
    public function undoUpvote(Request $request)
    {
        $commentId = $request->id;
        $userId = Auth::id();

        $upvoteComment = UpvoteComment::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if ($upvoteComment) {
            $upvoteComment->delete();
            event(new UndoUpvoteComment($commentId));
        } else {
            \Log::info('Upvote not found for comment with ID: ' . $commentId);
        }

        $comment = Comment::findOrFail($commentId);
        $upvotes = $comment->upvotes;

        return response()->json($upvotes, 200);
    }

    /**
     * Downvote a comment.
     */
    public function downvote(Request $request)
    {
        $commentId = $request->id;
        $userId = Auth::id();

        $downvoteComment = new DownvoteComment();
        $downvoteComment->comment_id = $commentId;
        $downvoteComment->user_id = $userId;

        if ($downvoteComment->save()) {
            event(new DownvoteCommentEvent($commentId));
        } else {
            \Log::info('Failed to downvote comment with ID: ' . $commentId);
        }

        $comment = Comment::findOrFail($commentId);
        $downvotes = $comment->downvotes;
        $commentOwner = User::findOrFail($comment->user_id);
        $downvoter = User::findOrFail($userId);

        return response()->json($downvotes, 200);
    }

    /**
     * Undo downvote for a comment.
     */
    public function undoDownvote(Request $request)
    {
        $commentId = $request->id;
        $userId = Auth::id();

        $downvoteComment = DownvoteComment::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if ($downvoteComment) {
            $downvoteComment->delete();
            event(new UndoDownvoteComment($commentId));
        } else {
            \Log::info('Downvote not found for comment with ID: ' . $commentId);
        }

        $comment = Comment::findOrFail($commentId);
        $downvotes = $comment->downvotes;

        return response()->json($downvotes, 200);
    }
}
?>