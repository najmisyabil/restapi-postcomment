<?php

namespace App\Http\Controllers;

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
    public function index(PostRepository $postRepo, CommentRepository $commentRepo)
    {
        $posts = $postRepo->get()->json();
        $comments = $commentRepo->get()->json();

        // Count comments based on post id
        $comments = collect($comments)->groupBy('postId')->toArray();
        $commentsCount = array_map(function ($item) {
            return count($item);
        }, $comments);

        // Map comments count to post
        $posts = array_map(function ($post) use ($commentsCount) {
            $post['post_id'] = $post['id'];
            $post['post_title'] = $post['title'] ?? null;
            $post['post_body'] = $post['body'] ?? null;
            $post['total_number_of_comments'] = $commentsCount[$post['id']] ?? 0;

            unset(
                $post['id'],
                $post['body'],
                $post['title'],
                $post['userId'],
            );

            return $post;
        }, $posts);

        // Sort posts with higher comments first
        $count = array_column($posts, 'total_number_of_comments');
        array_multisort($count, SORT_DESC, $posts);

        $postsCount = count($posts);

        return response()->json([
            'success' => true,
            'message' => "Successfully displayed $postsCount posts",
            'data' => $posts,
        ], 200);
    }
}
