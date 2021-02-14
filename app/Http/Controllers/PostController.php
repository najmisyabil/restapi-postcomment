<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PostRepository;

class PostController extends Controller
{
    /**
     * Display a listing of the Post sorted by number of comments.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    public function index(PostRepository $repo)
    {
        $posts = $repo->get()->json();

        return response()->json([
            'success' => true,
            'message' => 'Successfully fetched all posts',
            'body' => $posts
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
