<?php

namespace App\Http\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CommentService
{
    public function shouldMatch(string $column, array $comments, array $search): array
    {
        // Directly return comments if the value from request search term is null
        if (! array_key_exists($column, $search))
            return $comments;

        $value = $search[$column];
        $comments = Arr::where($comments, function ($comment) use ($column, $value) {
            return $comment[$column] == $value;
        });

        return $comments;
    }

    public function containsAny(string $column, array $comments, array $search): array
    {
        // Directly return comments if the value from request search term is null
        if (! array_key_exists($column, $search))
            return $comments;

        $pattern = $search[$column];
        $comments = Arr::where($comments, function ($comment) use ($column, $pattern) {
            return Str::contains($comment[$column], $pattern);
        });

        return $comments;
    }

    public function replaceLineBreak(array $comments, string $column = 'body')
    {
        return array_map(function ($item) use ($column) {
            $item[$column] = preg_replace("/[\n\r]/", ' ', $item[$column]);

            return $item;
        }, $comments);
    }

    public function isEmptySearch(array $input): bool
    {
        $arr = array_filter(array_values($input));

        return empty($arr);
    }

    public function countCommentsBasedOnPostId(array $comments): array
    {
        $comments = collect($comments)->groupBy('postId')->toArray();

        return array_map(function ($item) {
            return count($item);
        }, $comments);
    }
}
