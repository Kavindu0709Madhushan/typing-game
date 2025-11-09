<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship with TypingScore
    public function typingScores()
    {
        return $this->hasMany(TypingScore::class);
    }

    // Get user's highest score
    public function highestScore()
    {
        return $this->typingScores()->orderBy('wpm', 'desc')->first();
    }

    // Get user's average WPM
    public function averageWpm()
    {
        return $this->typingScores()->avg('wpm');
    }
}