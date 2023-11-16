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

        // Check if the current user can see (show) the post.
        $this->authorize('show', $post);  

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post
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

            // Get comments ordered by date.
            $comments = $post->comments()->orderByDesc('commentdate')->get();

            // Check if the current user can list the posts.
            $this->authorize('list', post::class);

            // The current user is authorized to list posts.

            // Use the pages.post template to display the post and its comments.
            return view('pages.post', [
                'post' => $post,
                'comments' => $comments
            ]);
        }
    }

    /**
     * Creates a new post.
     */
    public function create(Request $request)
    {
        // Create a blank new post.
        $post = new post();

        // Check if the current user is authorized to create this post.
        $this->authorize('create', $post);

        $request->validate([
            'title' => ['required'],
            'caption' => ['required']
        ]);

        // Set post details.
        $post->title = $request->input('title');
        $post->caption = $request->input('caption');
        $post->postdate = now(); // Set the current date and time.
        $post->user_id = Auth::user()->id;

        // Save the post and return it as JSON.
        $post->save();
        return response()->json($post);
    }

    /**
     * Delete a post.
     */
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = post::find($id);

        // Check if the current user is authorized to delete this post.
        $this->authorize('delete', $post);

        // Delete the post and return it as JSON.
        $post->delete();
        return response()->json($post);
    }
}
?>