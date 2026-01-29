@extends('layouts.template')

@section('content')
<div class="container py-5" style="max-width: 800px;">
    <!-- Quiz Header -->
    <div id="quiz-header" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="font-weight-bold">{{ $flashcard->title }} - Quiz</h3>
                <span class="text-muted">Question <span id="current-q-num" class="text-primary font-weight-bold">1</span> of {{ count($quiz) }}</span>
            </div>
            <a href="{{ route('flashcard.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="fas fa-times mr-2"></i> Quit Quiz
            </a>
        </div>
        <div class="progress shadow-sm" style="height: 10px; border-radius: 5px; background-color: var(--border-color);">
            <div id="quiz-progress" class="progress-bar progress-bar-animated" role="progressbar" style="width: 0%; background: linear-gradient(135deg, #6366f1, #4f46e5); transition: width 0.4s ease;"></div>
        </div>
    </div>

    <!-- Quiz Body -->
    <div id="quiz-body">
        <div class="card-modern p-5 shadow-lg mb-4" id="question-card" style="border: none;">
            <h4 id="question-text" class="mb-5 text-center font-weight-bold" style="line-height: 1.5;">
                <!-- Question will be inserted here -->
            </h4>
            
            <div id="options-container" class="row">
                <!-- Options will be inserted here -->
            </div>
        </div>

        <div class="text-right">
            <button id="btn-next" class="btn btn-primary btn-lg rounded-pill px-5 shadow-md" style="display: none;" onclick="nextQuestion()">
                Next Question <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>

    <!-- Results Body (Hidden) -->
    <div id="result-body" style="display: none;">
        <div class="card-modern p-5 text-center shadow-lg mb-4">
            <div id="result-icon" class="mb-4">
                <i class="fas fa-trophy fa-4x text-warning"></i>
            </div>
            <h2 class="font-weight-bold mb-2">Quiz Completed!</h2>
            <div class="display-3 font-weight-bold text-primary mb-3">
                <span id="score-percentage">0</span>%
            </div>
            <p class="h5 text-muted mb-4">You got <span id="score-count">0</span> out of {{ count($quiz) }} correct.</p>
            
            <!-- Feedback Overlay -->
            <div id="feedback-box" class="p-4 rounded-lg mb-5" style="background-color: #f9fafb;">
                <h4 id="feedback-title" class="font-weight-bold mb-2 text-dark"></h4>
                <p id="feedback-desc" class="text-muted mb-0"></p>
            </div>

            <div class="d-flex justify-content-center">
                <a href="{{ route('flashcard.quiz', $flashcard->id) }}" class="btn btn-primary-soft btn-lg rounded-pill px-4 mx-2">
                    <i class="fas fa-redo mr-2"></i> Retake Quiz
                </a>
                <a href="{{ route('flashcard.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 mx-2">
                    <i class="fas fa-check-circle mr-2"></i> Finish
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .option-btn {
        width: 100%;
        padding: 24px;
        margin-bottom: 24px;
        text-align: left;
        background-color: var(--card-bg);
        border: 2px solid var(--border-color);
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
        position: relative;
        overflow: hidden;
        color: var(--text-main);
        display: flex;
        align-items: center;
    }
    .option-btn:hover:not(:disabled) {
        border-color: var(--primary-color);
        background-color: rgba(99, 102, 241, 0.05);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.2);
    }
    body.dark-mode .option-btn:hover:not(:disabled) {
        background-color: rgba(99, 102, 241, 0.1);
    }
    .option-btn.selected {
        border-color: var(--primary-color);
        background-color: rgba(99, 102, 241, 0.1);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    body.dark-mode .option-btn.selected {
        background-color: rgba(99, 102, 241, 0.2);
    }
    .option-btn.correct {
        border-color: #22c55e !important;
        background-color: rgba(34, 197, 94, 0.1) !important;
        color: #166534 !important;
    }
    body.dark-mode .option-btn.correct {
        color: #86efac !important;
    }
    .option-btn.incorrect {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.1) !important;
        color: #991b1b !important;
    }
    body.dark-mode .option-btn.incorrect {
        color: #fca5a5 !important;
    }
    .option-btn:disabled {
        cursor: default;
        opacity: 0.8;
    }
    .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    
    body.dark-mode #feedback-box { background-color: rgba(255,255,255,0.03) !important; }
    body.dark-mode #question-text, body.dark-mode #feedback-title, body.dark-mode h3 { color: #f8fafc !important; }
    
    .letter-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: 1.5px solid currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        margin-right: 15px;
        transition: all 0.3s;
    }
    .option-btn:hover .letter-badge {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white !important;
    }
</style>

@endsection

@section('scripts')
<script>
    const quizData = @json($quiz);
    let currentQuestionIndex = 0;
    let score = 0;
    let selectedOption = null;

    const quizProgress = document.getElementById('quiz-progress');
    const currentQNum = document.getElementById('current-q-num');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const btnNext = document.getElementById('btn-next');
    const quizBody = document.getElementById('quiz-body');
    const resultBody = document.getElementById('result-body');
    const scorePercentage = document.getElementById('score-percentage');
    const scoreCount = document.getElementById('score-count');
    const feedbackTitle = document.getElementById('feedback-title');
    const feedbackDesc = document.getElementById('feedback-desc');
    const resultIcon = document.getElementById('result-icon');

    function loadQuestion() {
        const question = quizData[currentQuestionIndex];
        
        // Update header
        currentQNum.innerText = currentQuestionIndex + 1;
        quizProgress.style.width = ((currentQuestionIndex) / quizData.length * 100) + '%';
        
        // Update body
        questionText.innerText = question.question;
        optionsContainer.innerHTML = '';
        
        question.options.forEach((option, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';
            
            const button = document.createElement('button');
            button.className = 'option-btn shadow-sm';
            button.innerHTML = `
                <div class="letter-badge">
                    ${String.fromCharCode(65 + index)}
                </div>
                <span>${option}</span>
            `;
            button.onclick = () => selectOption(button, option);
            
            col.appendChild(button);
            optionsContainer.appendChild(col);
        });
        
        btnNext.style.display = 'none';
        selectedOption = null;
    }

    function selectOption(button, option) {
        if (selectedOption !== null) return;
        
        selectedOption = option;
        const currentQuestion = quizData[currentQuestionIndex];
        const isCorrect = option === currentQuestion.correct_answer;
        
        // Disable all buttons and show feedback
        const buttons = optionsContainer.querySelectorAll('.option-btn');
        buttons.forEach(btn => {
            btn.disabled = true;
            const btnText = btn.querySelector('span').innerText;
            if (btnText === currentQuestion.correct_answer) {
                btn.classList.add('correct');
            } else if (btnText === option && !isCorrect) {
                btn.classList.add('incorrect');
            }
        });
        
        if (isCorrect) score++;
        
        btnNext.style.display = 'inline-block';
        if (currentQuestionIndex === quizData.length - 1) {
            btnNext.innerHTML = 'Show Results <i class="fas fa-check-circle ml-2"></i>';
        }
    }

    function nextQuestion() {
        if (currentQuestionIndex < quizData.length - 1) {
            currentQuestionIndex++;
            loadQuestion();
        } else {
            showResults();
        }
    }

    function showResults() {
        quizProgress.style.width = '100%';
        document.getElementById('quiz-header').style.opacity = '0.5';
        quizBody.style.display = 'none';
        resultBody.style.display = 'block';
        
        const percentage = Math.round((score / quizData.length) * 100);
        scorePercentage.innerText = percentage;
        scoreCount.innerText = score;
        
        // Final feedback logic
        if (percentage >= 85) {
            feedbackTitle.innerText = "🏆 Exam Ready!";
            feedbackTitle.className = "font-weight-bold mb-2 text-success";
            feedbackDesc.innerText = "Fantastic! You have a strong grasp of this material. You're ready to ace the exam.";
            resultIcon.innerHTML = '<i class="fas fa-graduation-cap fa-4x text-success"></i>';
        } else if (percentage >= 60) {
            feedbackTitle.innerText = "📚 Almost There!";
            feedbackTitle.className = "font-weight-bold mb-2 text-warning";
            feedbackDesc.innerText = "Good job! You know the basics, but a little more revision on the harder topics would be beneficial.";
            resultIcon.innerHTML = '<i class="fas fa-book-open fa-4x text-warning"></i>';
        } else {
            feedbackTitle.innerText = "⏳ Need More Study";
            feedbackTitle.className = "font-weight-bold mb-2 text-danger";
            feedbackDesc.innerText = "This subject needs more attention. Try studying the flashcards again before retaking the quiz.";
            resultIcon.innerHTML = '<i class="fas fa-clock fa-4x text-danger"></i>';
        }

        saveResults(percentage, score, quizData.length);
    }

    function saveResults(score, correct, total) {
        fetch('{{ route("flashcard.quiz-result") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                flashcard_id: '{{ $flashcard->id }}',
                score: score,
                correct_count: correct,
                total_questions: total
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Result saved:', data);
        })
        .catch(error => {
            console.error('Error saving result:', error);
        });
    }

    // Initialize
    loadQuestion();
</script>
@endsection
