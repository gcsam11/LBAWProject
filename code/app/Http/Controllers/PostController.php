<?php
 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;


class PostController extends Controller
{
    /**
     * Show the Post for a given id.
     */
    public function show(string $id): View
    {
        // Get the post.
        $post = Post::findOrFail($id);

        // Get all comments for the post ordered by date.
        /* $comments = $post->comments()->orderByDesc('commentdate')->get(); */
        $comments = $post->comments()
            ->orderByRaw('(upvotes - downvotes) DESC')
            ->get();

        // No need to authorize show action here as it's already fetched the post.

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Shows all posts sorted by Upvotes/Downvote Difference
     */
    public function listTop()
    {
        // Get posts ordered by the difference between upvotes and downvotes.
        $posts = Post::orderByRaw('(upvotes - downvotes) DESC')->get();

        // Use the pages.post template to display all cards.
        return view('pages.posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Shows all posts sorted by How recent they were published
     */
    public function listRecent()
    {
        // Get posts ordered by postdate in descending order (most recent first).
        $posts = Post::orderBy('postdate', 'DESC')->get();

        // Use the pages.post template to display all cards.
        return view('pages.posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Creates a new post.
     */
    public function create(Request $request)
    {
        // Check if the current user is authorized to create this post.
        $this->authorize('create', Auth::user());

        $request->validate([
            'title' => ['required'],
            'caption' => ['required']
        ]);

        // Set post details.
        $post = Post::create([
            'title' => $request->input('title'),
            'caption' => $request->input('caption'),
            'postdate' => now(), // Set the current date and time.
            'user_id' => Auth::user()->id
        ]);

        // Return the created post.
        return response()->json($post);
    }

    /**
     * Update a post.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => ['required'],
            'caption' => ['required']
        ]);

        // Update post details.
        $post->title = $request->input('title');
        $post->caption = $request->input('caption');

        // Save the updated post and return it as JSON.
        $post->save();

        return redirect()->route('posts.show', ['id' => $post->id])->with('success', 'Post updated successfully');
    }

    /**
     * Delete a post.
     */
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        
        try {
            $post->delete();
            \Log::info('Post deleted successfully with ID: ' . $post->id);
            return redirect()->route('posts')->with('success', 'Post deleted successfully');
        } 
        catch (\Exception $e) {
            \Log::error('Failed to delete post with ID: ' . $post->id . '. Error: ' . $e->getMessage());
            
            return redirect()->route('posts')->with('error', 'Failed to delete the post');
        }
    }

}
?>