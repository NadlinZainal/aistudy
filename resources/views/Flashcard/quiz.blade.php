@extends('layouts.template')

@section('content')
<div class="container py-4 fade-in" style="max-width: 850px;">
    <!-- Quiz Header -->
    <div id="quiz-header" class="mb-5 px-2 animate-slide-down">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div class="mb-3 mb-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('flashcard.index') }}" class="text-primary">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quiz</li>
                    </ol>
                </nav>
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;">{{ $flashcard->title }}</h3>
                <div class="d-flex align-items-center">
                    <span class="badge badge-soft-primary rounded-pill px-3 py-1 mr-2">Question <span id="current-q-num">1</span> of {{ count($quiz) }}</span>
                </div>
            </div>
            <a href="{{ route('flashcard.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm transition-all hover-translate-y">
                <i class="fas fa-times mr-2 text-danger"></i> Quit Quiz
            </a>
        </div>
        
        <div class="progress-wrapper glass p-1 rounded-pill shadow-sm">
            <div class="progress bg-transparent border-0 rounded-pill" style="height: 12px; overflow: hidden;">
                <div id="quiz-progress" class="progress-bar progress-bar-animated rounded-pill" role="progressbar" 
                     style="width: 0%; background: linear-gradient(90deg, #4f46e5, #818cf8); transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);"></div>
            </div>
        </div>
    </div>

    <!-- Quiz Body -->
    <div id="quiz-body" class="px-2">
        <div class="card card-modern glass border-0 rounded-24 shadow-lg mb-4 overflow-hidden animate-pop-in" id="question-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-5">
                    <span class="text-uppercase font-weight-bold text-primary small tracking-widest d-block mb-3" style="letter-spacing: 2px;">Question</span>
                    <h4 id="question-text" class="font-weight-bold mb-0" style="line-height: 1.6; font-size: 1.4rem;">
                        <!-- Question will be inserted here -->
                    </h4>
                </div>
                
                <div id="options-container" class="d-flex flex-column align-items-center w-100">
                    <!-- Options will be inserted here as a vertical stack -->
                </div>
            </div>
        </div>

        <div class="text-center pt-2 animate-slide-up" id="next-container" style="display: none;">
            <button id="btn-next" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg transition-all hover-translate-y font-weight-bold" onclick="nextQuestion()">
                <span>Next Question</span> <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>

    <!-- Results Body (Hidden) -->
    <div id="result-body" style="display: none;">
        <div class="card card-modern glass border-0 rounded-24 text-center shadow-lg mb-4 overflow-hidden animate-pop-in">
            <div class="card-body p-5">
                <div id="result-icon" class="mb-4 animate-bounce">
                    <i class="fas fa-trophy fa-5x text-warning"></i>
                </div>
                <h2 class="font-weight-bold mb-4">Quiz Results</h2>
                
                <div class="score-display mb-5 position-relative d-inline-block">
                    <svg width="200" height="200" viewBox="0 0 200 200" class="position-relative">
                        <circle cx="100" cy="100" r="90" stroke="rgba(0,0,0,0.05)" stroke-width="12" fill="none" />
                        <circle id="score-circle" cx="100" cy="100" r="90" stroke="var(--primary-color)" stroke-width="12" 
                                fill="none" stroke-dasharray="0 565" stroke-linecap="round" 
                                style="transition: stroke-dasharray 1.5s ease-out;" />
                        <foreignObject x="0" y="0" width="200" height="200">
                            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                <span class="display-3 font-weight-bold text-primary" style="line-height: 1;"><span id="score-percentage">0</span>%</span>
                            </div>
                        </foreignObject>
                    </svg>
                </div>

                <p class="h5 text-muted mb-5">You answered <span class="text-dark font-weight-bold" id="score-count">0</span> out of {{ count($quiz) }} correctly.</p>
                
                <div id="feedback-box" class="p-4 rounded-xl mb-5 glass-feedback transition-all">
                    <h4 id="feedback-title" class="font-weight-bold mb-2"></h4>
                    <p id="feedback-desc" class="text-muted mb-0"></p>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center pt-2">
                    <a href="{{ route('flashcard.quiz', $flashcard->id) }}" class="btn btn-soft-primary btn-lg rounded-pill px-4 mx-sm-2 mb-3 mb-sm-0 shadow-sm transition-all hover-translate-y">
                        <i class="fas fa-redo mr-2"></i> Retake Quiz
                    </a>
                    <a href="{{ route('flashcard.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 mx-sm-2 shadow-lg transition-all hover-translate-y">
                        <i class="fas fa-check-circle mr-2"></i> Back to Library
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-24 { border-radius: 24px !important; }
    .rounded-xl { border-radius: 16px !important; }
    .tracking-widest { letter-spacing: 0.15em; }
    
    .glass {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .glass-feedback {
        background: rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Option Button Redesign - VERTICAL STACK */
    .option-btn {
        width: 100%;
        max-width: 600px;
        padding: 20px 25px;
        margin-bottom: 16px;
        text-align: left;
        background-color: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(0, 0, 0, 0.05);
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
        position: relative;
        overflow: hidden;
        color: var(--text-main);
        display: flex;
        align-items: center;
        cursor: pointer;
        outline: none !important;
    }
    
    .option-btn:hover:not(:disabled) {
        border-color: var(--primary-color);
        background-color: #fff;
        transform: scale(1.02);
        box-shadow: 0 10px 20px -10px rgba(79, 70, 229, 0.3);
    }
    
    .option-btn.selected {
        border-color: var(--primary-color);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .option-btn.correct {
        border-color: #10b981 !important;
        background-color: rgba(16, 185, 129, 0.05) !important;
        color: #065f46 !important;
    }
    
    .option-btn.incorrect {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.05) !important;
        color: #991b1b !important;
    }

    .option-btn:disabled {
        cursor: default;
        opacity: 0.8;
    }

    .letter-badge {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 800;
        margin-right: 20px;
        transition: all 0.3s;
        color: #64748b;
    }
    
    .option-btn:hover .letter-badge {
        background: var(--primary-color);
        color: white;
    }
    
    .option-btn.correct .letter-badge {
        background: #10b981;
        color: white;
    }
    
    .option-btn.incorrect .letter-badge {
        background: #ef4444;
        color: white;
    }

    /* Animations */
    .animate-slide-down { animation: slideDown 0.6s ease-out; }
    .animate-slide-up { animation: slideUp 0.6s ease-out; }
    .animate-pop-in { animation: popIn 0.5s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .animate-bounce { animation: miniBounce 2s infinite; }
    
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes popIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    @keyframes miniBounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

    /* Dark Mode Support */
    body.dark-mode .glass {
        background: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .option-btn {
        background-color: rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.1);
        color: #f1f5f9;
    }
    body.dark-mode .option-btn:hover:not(:disabled) {
        background-color: rgba(0, 0, 0, 0.3);
        border-color: var(--primary-color);
    }
    body.dark-mode .letter-badge {
        background: rgba(255, 255, 255, 0.1);
        color: #94a3b8;
    }
    body.dark-mode .glass-feedback {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .text-dark { color: #f8fafc !important; }
    body.dark-mode .option-btn.correct { color: #86efac !important; }
    body.dark-mode .option-btn.incorrect { color: #fca5a5 !important; }
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
    const nextContainer = document.getElementById('next-container');
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
        
        // Vertical stack generation
        question.options.forEach((option, index) => {
            const button = document.createElement('button');
            button.className = 'option-btn animate-slide-up';
            button.style.animationDelay = (index * 0.1) + 's';
            button.innerHTML = `
                <div class="letter-badge">
                    ${String.fromCharCode(65 + index)}
                </div>
                <div class="option-content">${option}</div>
            `;
            button.onclick = () => selectOption(button, option);
            optionsContainer.appendChild(button);
        });
        
        nextContainer.style.display = 'none';
        selectedOption = null;

        // Re-apply pop animation to card
        const card = document.getElementById('question-card');
        card.classList.remove('animate-pop-in');
        void card.offsetWidth; // trigger reflow
        card.classList.add('animate-pop-in');
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
            const btnText = btn.querySelector('.option-content').innerText;
            if (btnText === currentQuestion.correct_answer) {
                btn.classList.add('correct');
            } else if (btnText === option && !isCorrect) {
                btn.classList.add('incorrect');
            } else {
                btn.style.opacity = '0.5';
                btn.style.transform = 'scale(0.98)';
            }
        });
        
        if (isCorrect) score++;
        
        nextContainer.style.display = 'block';
        if (currentQuestionIndex === quizData.length - 1) {
            btnNext.querySelector('span').innerText = 'Show Results';
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
        scoreCount.innerText = score;
        
        // Animate percentage and circle
        const circle = document.getElementById('score-circle');
        const circumference = 2 * Math.PI * 90; // 565.48
        const offset = circumference - (percentage / 100 * circumference);
        
        let currentPerc = 0;
        const timer = setInterval(() => {
            if (currentPerc >= percentage) {
                clearInterval(timer);
            } else {
                currentPerc++;
                scorePercentage.innerText = currentPerc;
            }
        }, 15);

        circle.setAttribute('stroke-dasharray', `${circumference - offset} ${circumference}`);
        
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
