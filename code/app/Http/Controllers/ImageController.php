<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
class ImageController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=320,max_height=320',
        ]);

        // Store the image in the database
        $image = new Image;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = public_path('profiles/' . $imageName);
            $imageFile->move(public_path('profiles'), $imageName);

            $image->name = $imageName;

            // Hash the name
            $hashedName = md5($imageName);

            // Update the image path and name with hashed values
            $image->name = $hashedName;

            $image->save();

            // Return a response
            return response()->json(['message' => 'Image added successfully'], 201);
        }

        // Return a response
        return response()->json(['message' => 'Image upload failed'], 400);
    }
}
