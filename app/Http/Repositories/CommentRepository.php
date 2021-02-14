<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRepository
{
    protected $url;

    /**
     * Initialize main url
     */
    public function __construct()
    {
        $this->url = 'https://jsonplaceholder.typicode.com/comments';
    }

    public function get()
    {
        return $this->tryGet($this->url);
    }

    private function tryGet(string $url)
    {
        try {
            $response = Http::get($url);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                ])
            );
        }

        return $response;
    }
}
