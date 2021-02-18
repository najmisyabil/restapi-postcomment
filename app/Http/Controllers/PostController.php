<?php

namespace App\Http\Controllers;

use App\Http\Service\PostService;
use App\Http\Service\CommentService;
use App\Http\Repositories\PostRepository;
use App\Http\Repositories\CommentRepository;

class PostController extends Controller
{
    /**
     * Display a listing of the Post sorted by number of comments.
     *
     * The API response should have the following fields:
     *   post_id
     *   post_title
     *   post_body
     *   total_number_of_comments
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        PostService $postService,
        PostRepository $postRepo,
        CommentService $commentService,
        CommentRepository $commentRepo
    ) {
        $posts = $postRepo->get();
        $comments = $commentRepo->get();

        $commentsCount = $commentService->countCommentsBasedOnPostId($comments);
        $posts = $postService->mapCommentsCount($posts, $commentsCount);
        $postsCount = count($posts);

        // Sort posts with higher comments first
        $count = array_column($posts, 'total_number_of_comments');
        array_multisort($count, SORT_DESC, $posts);

        return response()->json([
            'success' => true,
            'message' => "Successfully displayed $postsCount posts",
            'data' => $posts,
        ], 200);
    }
}
