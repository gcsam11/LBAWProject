<?php

namespace Tests\Feature;

use Tests\TestCase; // Make sure to import the correct TestCase class
use App\Http\Controllers\PostController;
use App\Models\Post;


class PostControllerTest extends TestCase
{
    public function testPrintAllPosts()
    {
        // Fetch all posts from the database
        $posts = Post::orderByRaw('(upvotes - downvotes) DESC')->get();
    
        // Make assertions
        $this->assertNotEmpty($posts);
        // Add your assertion for the expected number of posts
    
        // Print posts in the command line
        foreach ($posts as $post) {
            echo "Post ID: {$post->id} - Title: {$post->title}\n";
            // Print other details of the post as needed
        }
    
        // You can also use PHPUnit's `dump()` or Laravel's `dd()` to inspect the retrieved posts
        dump($posts); // or dd($posts);
    }
}