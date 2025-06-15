<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class News extends Command
{

    protected $signature = 'app:news';
    protected $description = 'add News weekly';

    public function handle()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $posts = Post::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereHas('user', function ($query) {
                $query->where('role', 'police');
            })->where('isNews', '!=', true)
            ->pluck('content');
        $postArray = $posts->toArray();
        $response = Http::post('https://a08e-169-150-218-29.ngrok-free.app/summarize', [
            'texts' => $postArray
        ]);
        if ($response->successful()) {
            $url = env('GATE_WAY_URL') . '/storage/news/' . 'news.png';
            $news = Post::create([
                'user_id' => 1,
                'content' => json_encode($response['summaries']),
                'isNews' => true,
                'media' => $url
            ]);
        }
        // else {
        //     $news = Post::create([
        //         'user_id' => 1,
        //         'content' => json_encode($postArray),
        //         'isNews' => true
        //     ]);
        // }
    }
}
