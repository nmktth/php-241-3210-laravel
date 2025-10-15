<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendDailyStats extends Command 
{
    protected $signature = 'stats:daily';
    protected $description = 'Send daily statistics to moderators';

    public function handle()
    {
        // Статистика за сегодня
        $today = now()->format('Y-m-d');
        
        $viewsCount = ArticleView::whereDate('created_at', $today)->count();
        $commentsCount = Comment::whereDate('created_at', $today)->count();
        $newCommentsCount = Comment::whereDate('created_at', $today)
                                  ->where('accept', false)
                                  ->count();

        $moderators = User::where('role', 'moderator')->get();
        
        foreach ($moderators as $moderator) {
            Mail::send('emails.daily_stats', [
                'viewsCount' => $viewsCount,
                'commentsCount' => $commentsCount,
                'newCommentsCount' => $newCommentsCount,
                'date' => $today
            ], function ($message) use ($moderator) {
                $message->to($moderator->email)
                       ->subject('Daily Statistics - ' . now()->format('d.m.Y'));
            });
        }
        
        $this->info('Daily stats sent successfully!');
    }
}