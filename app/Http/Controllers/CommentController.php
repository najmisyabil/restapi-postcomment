<?php

namespace App\Http\Controllers;

use App\Http\Service\CommentService;
use App\Http\Repositories\CommentRepository;
use App\Http\Requests\Comment\FilterByFields;

class CommentController extends Controller
{
    public function filter(
        FilterByFields $request,
        CommentService $service,
        CommentRepository $repo
    ) {
        $input = array_filter($request->validated());

        if ($service->isEmptySearch($input))
            return response()->json([
                'success' => false,
                'message' => "Please specify at least one field to filter",
            ], 422);

        // Only fetch and filter the comment if search input is given
        $comments = $repo->get();

        /*
         * These fields should match exactly with search term (if exists):
         * 1) postId
         * 2) id -> which is comment id
         * 3) email
         *
         * These fields will be filtered based on string given from search term.
         * Partial string may be used and will be treated as pattern
         * 1) name
         * 2) body
         */
        $comments = $service->shouldMatch('postId', $comments, $input);
        $comments = $service->shouldMatch('id', $comments, $input);
        $comments = $service->shouldMatch('email', $comments, $input);
        $comments = $service->containsAny('name', $comments, $input);
        // Filter line break from comments body before filtering
        $comments = $service->replaceLineBreak($comments);
        $comments = $service->containsAny('body', $comments, $input);

        $count = count($comments);
        $message = $count
            ? "Success: $count comments matched with given details"
            : "No comment matches with given details";

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => array_values($comments),
        ], 200);
    }
}
