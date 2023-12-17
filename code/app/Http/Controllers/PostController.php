<?php
 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagePostController;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


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
        $comments = $post->comments()->orderByRaw('(upvotes - downvotes) DESC')->with('image')->get();
        // Get all images that belong to the Post.
        $images = $post->images();

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post,
            'comments' => $comments,
            'images' => $images,
        ]);
    }
    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search_term' => ['required']
        ]);
    
        $searchTerm = $validatedData['search_term'];
    
        $searchTerm = preg_replace('/\s+/', ' ', $searchTerm);
    
        if (strpos($searchTerm, ' ') !== false) {
            // Full-text search for terms with spaces
            $modifiedSearchTerm = str_replace(' ', ':* & ', $searchTerm) . ':*';
            $results = Post::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$modifiedSearchTerm])
                ->get();
        } else {
            // Exact match search for both title and caption
            $results = Post::where('title', 'ILIKE', "%$searchTerm%")
            ->orWhere('caption', 'ILIKE', "%$searchTerm%")
            ->get();
        }
    
        return view('pages/posts_search_results', [
            'results' => $results
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
        return view('pages.main', [
            'posts' => $posts,
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
        return view('pages.main', [
            'posts' => $posts
        ]);
    }

    /**
     * Shows all posts made by the currently logged-in user.
     */
    public function userNews()
    {
        // Get the currently logged-in user's ID
        $userId = Auth::id();
        

        // Get posts made by the user ordered by postdate in descending order (most recent first).
        $posts = Post::where('user_id', $userId)
            ->orderBy('postdate', 'DESC')
            ->get();

        // Pass the retrieved posts to the view
        return view('pages.user_news', [
            'posts' => $posts
        ]);
    }


    /**
     * Creates a new post.
     */
    public function create(Request $request) {
        $request->validate([
            'title' => 'required',
            'caption' => 'required',
            'images' => 'array',
            'topic' => 'string',
            'images.*' =>  'image|max:2048|mimes:jpg,jpeg,svg,gif,png',
        ]);
    
        // $topicId = getTopicId($request->topic);

        // Set post details.
        $post = Auth::user()->posts()->create([
            //'topic_id' => $topicId,
            'title' => $request->input('title'),
            'caption' => $request->input('caption'),
            'postdate' => now(), // Set the current date and time.
            'user_id' => Auth::user()->id
        ]);


        // Check if images array is not null
        if (!empty($request->images)) {
            // Call the create method of ImagePostController and pass the images array
            $errorImages = ImagePostController::create($request, $post->id);
            if ($errorImages === true) {
                // No errors, continue with the code
            } else {
                // Delete any images associated with the post
                ImagePost::where('post_id', $post->id)->delete();

                // Delete the post
                $post->delete();

                // Return error Message                
                return redirect()->route('pages.create_post')->withErrors($errorImages);
            }

        }
        // Redirect the user to the newly created post page or any other page you prefer.
        return redirect()->route('user_news')->with('success', 'Post created successfully');
    }

    /**
     * Update a post.
     */
    public function update(Request $request, $id){
        try {

            // Find the post.
            $post = Post::findOrFail($id);
            
            $this->authorize('update', $post);


            $request->validate([
                'title' => ['required'],
                'caption' => ['required']
            ]);
            
            // Update post details.
            $post->title = $request->input('title');
            $post->caption = $request->input('caption');

            // Save the updated post.
            $post->save();

            return redirect()->route('home')->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            // Log the error message.
            \Log::error('Failed to update post with ID: ' . $post->id . '. Error: ' . $e->getMessage());

            // Redirect back with an error message.
            return redirect()->route('home')->with('error',  'Failed to update the post');
        }
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
            
            return redirect()->route('home')->with('error', 'Failed to delete the post');
        }
    }

}
?>