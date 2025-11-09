<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Typing Speed Test</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 700px;
    width: 100%;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 32px;
}

.stats {
    display: flex;
    justify-content: space-around;
    margin-bottom: 30px;
    gap: 20px;
}

.stat-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    flex: 1;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
}

.word-container {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 20px;
    min-height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.word {
    font-size: 36px;
    font-weight: 600;
    letter-spacing: 2px;
}

.letter {
    display: inline-block;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.correct {
    color: #28a745;
}

.wrong {
    color: #dc3545;
    animation: shake 0.3s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.input-container {
    margin-bottom: 20px;
}

#input {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.3s;
}

#input:focus {
    border-color: #667eea;
}

#input:disabled {
    background: #f0f0f0;
    cursor: not-allowed;
}

.button-container {
    display: flex;
    gap: 10px;
}

.btn {
    flex: 1;
    padding: 15px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-start {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-start:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-restart {
    background: #6c757d;
    color: white;
}

.btn-restart:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

.game-over {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-top: 20px;
    display: none;
}

.game-over h2 {
    color: #667eea;
    margin-bottom: 15px;
}

.game-over p {
    font-size: 18px;
    color: #666;
    margin: 10px 0;
}

.wpm {
    font-size: 48px;
    font-weight: bold;
    color: #28a745;
    margin: 20px 0;
}
</style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin: 0;">⚡ Typing Speed Test</h1>
        <div style="display: flex; gap: 10px; align-items: center;">
            <span style="color: #667eea; font-weight: bold;">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px;">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Time</div>
            <div class="stat-value" id="timer">60</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Score</div>
            <div class="stat-value" id="score">0</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">WPM</div>
            <div class="stat-value" id="wpm">0</div>
        </div>
    </div>

    <div class="word-container">
        <div class="word" id="current-word">Click START to begin</div>
    </div>

    <div class="input-container">
        <input type="text" id="input" disabled autocomplete="off" placeholder="Type the word above...">
    </div>

    <div class="button-container">
        <button class="btn btn-start" id="start-btn">START GAME</button>
        <button class="btn btn-restart" id="restart-btn">RESTART</button>
    </div>

    <div class="game-over" id="game-over">
        <h2>🎉 Game Over!</h2>
        <p>Words Typed: <strong id="final-score">0</strong></p>
        <div class="wpm" id="final-wpm">0 WPM</div>
        <p>Great job! Click RESTART to play again.</p>
    </div>

    <!-- User Stats Section -->
    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
        <h3 style="color: #333; margin-bottom: 15px;">📊 Your Statistics</h3>
        
        @if($highestScore)
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <h4 style="color: #667eea; margin-bottom: 10px;">🏆 Your Best Score</h4>
            <p><strong>WPM:</strong> {{ $highestScore->wpm }}</p>
            <p><strong>Words:</strong> {{ $highestScore->score }}</p>
            <p><strong>Date:</strong> {{ $highestScore->created_at->format('M d, Y') }}</p>
        </div>
        @else
        <p style="text-align: center; color: #666;">No scores yet. Play your first game!</p>
        @endif

        @if($userScores->count() > 0)
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <div onclick="toggleSection('recentGames')" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="color: #333; margin: 0;">📈 Recent Games ({{ $userScores->take(5)->count() }})</h4>
                <span id="recentGamesArrow" style="font-size: 20px; transition: transform 0.3s;">▼</span>
            </div>
            <div id="recentGames" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;">
                <div style="margin-top: 15px; max-height: 200px; overflow-y: auto;">
                    @foreach($userScores->take(5) as $score)
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 8px; display: flex; justify-content: space-between;">
                        <span>{{ $score->wpm }} WPM - {{ $score->score }} words</span>
                        <span style="color: #999; font-size: 12px;">{{ $score->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Leaderboard Section -->
    @if($leaderboard->count() > 0)
    <div style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
        <div style="background: white; padding: 15px; border-radius: 8px;">
            <div onclick="toggleSection('topPlayers')" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: #333; margin: 0;">🏅 Top Players ({{ $leaderboard->count() }})</h3>
                <span id="topPlayersArrow" style="font-size: 20px; transition: transform 0.3s;">▼</span>
            </div>
            <div id="topPlayers" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;">
                <div style="margin-top: 15px;">
                    @foreach($leaderboard as $index => $score)
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 5px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="font-size: 20px; font-weight: bold; color: {{ $index < 3 ? '#FFD700' : '#999' }};">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </span>
                            <span style="font-weight: 600;">{{ $score->user->name }}</span>
                        </div>
                        <span style="font-weight: bold; color: #667eea;">{{ $score->wpm }} WPM</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Get words from Laravel controller (already shuffled)
const words = @json($words);

let currentIndex = 0;
let score = 0;
let timeLeft = 60;
let timerInterval = null;
let gameStarted = false;
let startTime = null;

const input = document.getElementById('input');
const currentWordDiv = document.getElementById('current-word');
const scoreDiv = document.getElementById('score');
const timerDiv = document.getElementById('timer');
const wpmDiv = document.getElementById('wpm');
const startBtn = document.getElementById('start-btn');
const restartBtn = document.getElementById('restart-btn');
const gameOverDiv = document.getElementById('game-over');
const finalScoreDiv = document.getElementById('final-score');
const finalWpmDiv = document.getElementById('final-wpm');

function showWord() {
    currentWordDiv.innerHTML = '';
    const word = words[currentIndex];
    word.split('').forEach(letter => {
        const span = document.createElement('span');
        span.textContent = letter;
        span.classList.add('letter');
        currentWordDiv.appendChild(span);
    });
}

function calculateWPM() {
    if (!startTime) return 0;
    const timeElapsed = (60 - timeLeft) / 60;
    if (timeElapsed === 0) return 0;
    return Math.round(score / timeElapsed);
}

function updateWPM() {
    const wpm = calculateWPM();
    wpmDiv.textContent = wpm;
}

function startGame() {
    gameStarted = true;
    score = 0;
    timeLeft = 60;
    currentIndex = 0;
    startTime = Date.now();
    
    scoreDiv.textContent = '0';
    timerDiv.textContent = '60';
    wpmDiv.textContent = '0';
    input.value = '';
    input.disabled = false;
    input.focus();
    startBtn.disabled = true;
    gameOverDiv.style.display = 'none';
    
    showWord();
    
    timerInterval = setInterval(() => {
        timeLeft--;
        timerDiv.textContent = timeLeft;
        updateWPM();
        
        if (timeLeft <= 0) {
            endGame();
        }
    }, 1000);
}

function endGame() {
    clearInterval(timerInterval);
    input.disabled = true;
    gameStarted = false;
    startBtn.disabled = false;
    
    const finalWPM = calculateWPM();
    finalScoreDiv.textContent = score;
    finalWpmDiv.textContent = finalWPM + ' WPM';
    gameOverDiv.style.display = 'block';
    
    // Save score to database
    saveScoreToDatabase(score, finalWPM);
}

function saveScoreToDatabase(scoreValue, wpmValue) {
    fetch('{{ route("typing.save-score") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            score: scoreValue,
            wpm: wpmValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            setTimeout(() => {
                alert('Score saved! Refresh to see updated leaderboard.');
            }, 500);
        }
    })
    .catch(error => console.error('Error saving score:', error));
}

function restartGame() {
    clearInterval(timerInterval);
    gameStarted = false;
    startBtn.disabled = false;
    input.disabled = true;
    input.value = '';
    score = 0;
    timeLeft = 60;
    currentIndex = 0;
    startTime = null;
    
    scoreDiv.textContent = '0';
    timerDiv.textContent = '60';
    wpmDiv.textContent = '0';
    currentWordDiv.textContent = 'Click START to begin';
    gameOverDiv.style.display = 'none';
}

input.addEventListener('input', () => {
    if (!gameStarted) return;
    
    const word = words[currentIndex];
    const letters = currentWordDiv.querySelectorAll('.letter');
    const typed = input.value;
    
    letters.forEach((letterSpan, i) => {
        if (typed[i] == null) {
            letterSpan.classList.remove('correct', 'wrong');
        } else if (typed[i] === letterSpan.textContent) {
            letterSpan.classList.add('correct');
            letterSpan.classList.remove('wrong');
        } else {
            letterSpan.classList.add('wrong');
            letterSpan.classList.remove('correct');
        }
    });
    
    if (typed === word) {
        score++;
        scoreDiv.textContent = score;
        updateWPM();
        input.value = '';
        currentIndex = (currentIndex + 1) % words.length;
        showWord();
    }
});

startBtn.addEventListener('click', startGame);
restartBtn.addEventListener('click', restartGame);

// Toggle Section Function for Dropdowns
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const arrow = document.getElementById(sectionId + 'Arrow');
    
    if (section.style.maxHeight === '0px' || section.style.maxHeight === '') {
        section.style.maxHeight = section.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        section.style.maxHeight = '0px';
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>
</body>
</html>