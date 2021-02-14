<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Repositories\CommentRepository;
use App\Http\Requests\Comment\SearchByKeywords;

class CommentController extends Controller
{
    public function search(SearchByKeywords $request, CommentRepository $repo)
    {
        $keywords = $request->validated()['keywords'];
        $comments = $repo->get();

        // Remove comments that does not contain keywords in body
        foreach ($comments as $key => &$comment) {
            if (!Str::contains($comment['body'], $keywords)) {
                unset($comments[$key]);
                continue;
            }

            // Filter out column name and email
            unset($comment['name'], $comment['email']);
        }

        $count = count($comments);

        return response()->json([
            'success' => true,
            'message' => "Successfully fetched $count comments with given keywords",
            'data' => array_values($comments),
        ]);
    }
}
