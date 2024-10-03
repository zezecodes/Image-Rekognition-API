<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable|sometimes',
            'image' => 'nullable|sometimes|file'
        ]);

        $newPost = auth()->user()->posts()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        if($request->hasFile('image')){
            $client = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest'
            ]);

            $image = fopen($request->file('image')->getPathname(), 'r');
            $bytes = fread($image, $request->file('image')->getSize());

            $results = $client->detectModerationLabels([
                'Image' => ['Bytes' => $bytes],
                'minConfidence'
            ]);
            $resultLabels = $results->get('ModerationLabels');

            if(array_search('Explicit Nudity', array_column($resultLabels, 'Name')) !== false){
                $newPost->delete;
                return redirect()->back()->withErrors(['nudity' => 'The image you have uploaded contains explicit nudity']);
            }


            $imagePath = $request->file('image')->store('public/posts');

            if($imagePath === null){
                return redirect()->back()->withErrors(['image_upload_files' => 'The image you have uploaded does not exist']);
            }

            $newPost->image = $imagePath;
            $newPost->save();
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
