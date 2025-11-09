<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TypingScore;

class TypingGameController extends Controller
{
    public function index()
    {
        // 150 words for the typing game
        $words = [
            // Fruits (20)
            'apple', 'banana', 'orange', 'grape', 'kiwi', 'mango', 'lemon', 'peach', 'pear', 'plum',
            'melon', 'berry', 'cherry', 'fig', 'lime', 'date', 'apricot', 'papaya', 'guava', 'coconut',
            
            // Vegetables (20)
            'avocado', 'tomato', 'carrot', 'onion', 'potato', 'garlic', 'pepper', 'spinach', 'lettuce', 'cabbage',
            'broccoli', 'cauliflower', 'corn', 'peas', 'bean', 'celery', 'cucumber', 'radish', 'turnip', 'beet',
            
            // Grains & Nuts (15)
            'rice', 'wheat', 'barley', 'oats', 'rye', 'almond', 'cashew', 'walnut', 'pistachio', 'hazelnut',
            'pecan', 'macadamia', 'chestnut', 'peanut', 'quinoa',
            
            // Animals (20)
            'cat', 'dog', 'bird', 'fish', 'lion', 'tiger', 'bear', 'wolf', 'fox', 'deer',
            'elephant', 'giraffe', 'zebra', 'monkey', 'rabbit', 'mouse', 'horse', 'cow', 'sheep', 'goat',
            
            // Colors (15)
            'red', 'blue', 'green', 'yellow', 'purple', 'orange', 'pink', 'brown', 'black', 'white',
            'gray', 'violet', 'indigo', 'cyan', 'magenta',
            
            // Common Objects (20)
            'book', 'pen', 'paper', 'desk', 'chair', 'table', 'door', 'window', 'phone', 'computer',
            'laptop', 'mouse', 'keyboard', 'screen', 'lamp', 'clock', 'mirror', 'bottle', 'cup', 'plate',
            
            // Nature (15)
            'tree', 'flower', 'grass', 'leaf', 'branch', 'root', 'seed', 'plant', 'forest', 'mountain',
            'river', 'lake', 'ocean', 'beach', 'island',
            
            // Weather (10)
            'sun', 'moon', 'star', 'cloud', 'rain', 'snow', 'wind', 'storm', 'thunder', 'lightning',
            
            // Body Parts (10)
            'hand', 'foot', 'head', 'eye', 'ear', 'nose', 'mouth', 'arm', 'leg', 'finger',
            
            // Time (5)
            'day', 'night', 'morning', 'evening', 'noon'
        ];
        
        // Shuffle the words randomly
        shuffle($words);
        
        // Get user's scores (handle if TypingScore model doesn't exist yet)
        try {
            $userScores = TypingScore::getUserScores(auth()->id());
            $highestScore = TypingScore::getUserHighestScore(auth()->id());
            $leaderboard = TypingScore::getLeaderboard(10);
        } catch (\Exception $e) {
            // If table doesn't exist yet, set empty values
            $userScores = collect([]);
            $highestScore = null;
            $leaderboard = collect([]);
        }
        
        return view('typing-game', compact('words', 'userScores', 'highestScore', 'leaderboard'));
    }
    
    public function saveScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'wpm' => 'required|integer|min:0',
        ]);

        TypingScore::create([
            'user_id' => auth()->id(),
            'score' => $request->score,
            'wpm' => $request->wpm,
            'time_taken' => 60
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Score saved successfully!'
        ]);
    }
}