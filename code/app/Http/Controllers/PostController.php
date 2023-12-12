<?php
 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

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

/*  
    public function listRecent()
    {
        // Get posts ordered by postdate in descending order (most recent first).
        $posts = Post::orderBy('postdate', 'DESC')->get();

        // Use the pages.post template to display all cards.
        return view('pages.main', [
            'posts' => $posts
        ]);
    }
 */
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
            'title' => ['required'],
            'caption' => ['required']
        ]);
    
        $topicId = 1; // Replace this with your desired topic ID

        // Set post details.
        $post = Auth::user()->posts()->create([
            'topic_id' => $topicId,
            'title' => $request->input('title'),
            'caption' => $request->input('caption'),
            'postdate' => now(), // Set the current date and time.
            'user_id' => Auth::user()->id
        ]);
    
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

    public function applyFilter(Request $request)
    {
        $query = Post::query();


        // Minimum Date
        if ($request->filled('minimum_date')) {
            $minDate = $request->input('minimum_date');
            $query->whereDate('postdate', '>=', $minDate);
        }

        // Maximum Date
        if ($request->filled('maximum_date')) {
            $maxDate = $request->input('maximum_date');
            $query->whereDate('postdate', '<=', $maxDate);
        }

        if ($request->filled('minimum_upvote')) {
            $query->where('upvotes', '>=', $request->input('minimum_upvote'));
        }

        if ($request->filled('maximum_upvote')) {
            $query->where('upvotes', '<=', $request->input('maximum_upvote'));
        }

        if ($request->filled('minimum_downvotevote')) {
            $query->where('downvotes', '>=', $request->input('minimum_downvote'));
        }

        if ($request->filled('maximum_downvote')) {
            $query->where('downvotes', '<=', $request->input('maximum_downvote'));
        }

        if ($request->filled('user_name')) {
            $userName = $request->input('user_name');
            $query->whereHas('user', function ($userQuery) use ($userName) {
                $userQuery->where('name', 'like', "%$userName%");
            });
        }
        
        if ($request->filled('user_id')) {
            $userId = $request->input('user_id');
            $query->where('user_id', $userId);
        }

        if ($request->has('sort')) {
            $sortQuery = $request->input('sort');

            switch ($sortQuery) {
                case 'dateDown':
                    $query->orderBy('postdate', 'DESC');
                    break;
                case 'dateUp':
                    $query->orderBy('postdate', 'ASC');
                    break;
                case 'voteDown':
                    $query->orderBy('upvotes', 'DESC');
                    break;
                case 'voteUp':
                    $query->orderBy('upvotes', 'ASC');
                    break;
                default:
                    $query->orderBy('postdate', 'DESC');
                    break;
            }
        } else {
            $query->orderBy('upvotes', 'DESC');
        }

        $filteredPosts = $query->get();

        $filteredPostsHtml = view('partials.posts', ['posts' => $filteredPosts])->render();

        return response()->json(['success' => true, 'html' => $filteredPostsHtml]);
    }
}
?>