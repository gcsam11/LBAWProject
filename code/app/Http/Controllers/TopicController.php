<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function showCreatePostForm(){
        $topics = Topic::all(); // Retrieve all topics
        return view('pages.create_post', ['topics' => $topics]);

    }
    public function showFiltersTopic(){
        $topics = Topic::all(); // Retrieve all topics
        return response()->json(['topics' => $topics]);
    }
}
?>