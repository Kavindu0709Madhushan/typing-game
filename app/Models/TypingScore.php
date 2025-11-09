<?php
// app/Models/TypingScore.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypingScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'wpm',
        'time_taken'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get highest score for a user
    public static function getUserHighestScore($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('wpm', 'desc')
            ->first();
    }

    // Get all scores for a user
    public static function getUserScores($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Get leaderboard - Only HIGHEST score per user
    public static function getLeaderboard($limit = 10)
    {
        return self::select('typing_scores.id', 'typing_scores.user_id', 'typing_scores.score', 'typing_scores.wpm', 'typing_scores.created_at')
            ->join(DB::raw('(SELECT user_id, MAX(wpm) as max_wpm FROM typing_scores GROUP BY user_id) as best_scores'), function($join) {
                $join->on('typing_scores.user_id', '=', 'best_scores.user_id')
                     ->on('typing_scores.wpm', '=', 'best_scores.max_wpm');
            })
            ->with('user')
            ->orderBy('typing_scores.wpm', 'desc')
            ->groupBy('typing_scores.user_id', 'typing_scores.id', 'typing_scores.score', 'typing_scores.wpm', 'typing_scores.created_at')
            ->limit($limit)
            ->get();
    }
}