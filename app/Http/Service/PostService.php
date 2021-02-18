<?php

namespace App\Http\Service;

class PostService
{
    public function mapCommentsCount(array $posts, array $commentsCount)
    {
        return array_map(function ($post) use ($commentsCount) {
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
    }
}
