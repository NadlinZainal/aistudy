@extends('layouts.template')

@section('content')
<div class="test-container d-flex flex-column" style="max-width: 900px; margin: 0 auto; min-height: 85vh;">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="font-weight-bold" style="color: #1f2937;">{{ $flashcard->title }}</h3>
            <span class="text-muted font-weight-medium">Card <span id="current-card-num" class="text-primary font-weight-bold">1</span> of <span id="total-cards">{{ count($flashcard->cards) }}</span></span>
        </div>
        <div class="d-flex align-items-center">
            <!-- Export Dropdown -->
            <div class="dropdown mr-3">
                <button class="btn btn-primary-soft btn-soft dropdown-toggle px-3" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right card-modern shadow-lg border-0" aria-labelledby="exportDropdown" style="border-radius: 12px;">
                    <a class="dropdown-item py-2" href="{{ route('flashcard.export.pdf', $flashcard->id) }}">
                        <i class="fas fa-file-pdf text-danger mr-2" style="width: 20px;"></i> Save as PDF
                    </a>
                    <a class="dropdown-item py-2" href="{{ route('flashcard.export', $flashcard->id) }}">
                        <i class="fas fa-file-code text-success mr-2" style="width: 20px;"></i> Save as JSON
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="px-3 py-2">
                        <div class="g-savetodrive"
                           data-src="{{ asset('storage/' . $flashcard->document_path) }}"
                           data-filename="{{ $flashcard->title }}.{{ pathinfo($flashcard->document_path, PATHINFO_EXTENSION) }}"
                           data-sitename="Flashcard AI">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exit Button -->
            <a href="{{ route('flashcard.index') }}" class="btn btn-light shadow-sm text-secondary rounded-pill px-4">
                <i class="fas fa-times mr-2"></i> Exit
            </a>
        </div>
    </div>

    <!-- Flashcard Area -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center mb-5" style="perspective: 2000px;">
        <div id="flashcard" class="cursor-pointer position-relative shadow-2xl" 
             style="width: 100%; max-width: 750px; height: 480px; transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1); transform-style: preserve-3d; border-radius: 32px;"
             onclick="flipCard()">
            
            <!-- Front (Question) -->
            <div id="card-front" class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center p-5 border shadow-sm" 
                 style="backface-visibility: hidden; -webkit-backface-visibility: hidden; top:0; left:0; border-radius: 32px; background: var(--card-bg); z-index: 2;">
                <span class="badge badge-primary-soft rounded-pill px-3 py-1 mb-4 text-uppercase tracking-widest font-weight-bold" style="font-size: 0.7rem; letter-spacing: 2.5px;">Question</span>
                <div id="question-text" class="h2 font-weight-bold tracking-tight" style="line-height: 1.4; max-width: 80%;">
                    <!-- Question loaded via JS -->
                </div>
                <div class="mt-5 text-muted small opacity-50">
                    <i class="fas fa-mouse-pointer mr-2"></i> Click to reveal answer
                </div>
            </div>

            <!-- Back (Answer) -->
            <div id="card-back" class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center p-5 border shadow-sm" 
                 style="backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg); top:0; left:0; border-radius: 32px; background: var(--card-bg);">
                <span class="badge badge-success-soft rounded-pill px-3 py-1 mb-4 text-uppercase tracking-widest font-weight-bold" style="font-size: 0.7rem; letter-spacing: 2.5px; color: #16a34a !important;">Answer</span>
                <div id="answer-text" class="h2 font-weight-medium" style="line-height: 1.4; max-width: 80%;">
                    <!-- Answer loaded via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="d-flex justify-content-center align-items-center mb-5">
        <button class="btn glass shadow-sm rounded-circle mx-3 d-flex align-items-center justify-content-center hover-up" 
                style="width: 64px; height: 64px; color: var(--text-main);" onclick="prevCard()" id="btn-prev" disabled>
            <i class="fas fa-chevron-left fa-lg"></i>
        </button>
        
        <div class="px-4 py-2 glass rounded-pill text-muted small mx-3 d-none d-md-block" style="opacity: 0.8;">
            <kbd class="bg-white border text-secondary px-2 py-1 rounded shadow-sm mr-2">Space</kbd> Flip card
        </div>

        <button class="btn btn-primary btn-soft shadow-lg rounded-circle mx-3 d-flex align-items-center justify-content-center hover-up" 
                style="width: 64px; height: 64px;" onclick="nextCard()" id="btn-next">
            <i class="fas fa-chevron-right fa-lg"></i>
        </button>
    </div>

    <!-- AI Chat Widget -->
    <div id="chat-widget" style="position: fixed; bottom: 40px; right: 40px; z-index: 1000;">
        <!-- Chat Button -->
        <button id="chat-toggle-btn" class="btn btn-primary shadow-2xl rounded-24 d-flex justify-content-center align-items-center pulse-primary" 
                style="width: 72px; height: 72px; transition: all 0.3s; border: none; background: linear-gradient(135deg, #6366f1, #4f46e5);"
                onclick="toggleChat()">
            <i class="fas fa-comment-dots fa-2x text-white"></i>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" class="glass shadow-24 rounded-24 overflow-hidden" 
             style="position: absolute; bottom: 90px; right: 0; width: 380px; height: 550px; max-height: 75vh; display: none; flex-direction: column; border: none;">
            
            <!-- Header -->
            <div class="p-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <div class="d-flex align-items-center text-white">
                    <div class="glass rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border: none;">
                        <i class="fas fa-robot fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bold">AI Learning Assistant</h6>
                        <small class="opacity-75">Ready to help</small>
                    </div>
                </div>
                <button class="btn btn-link text-white p-0 opacity-75 hover-opacity" onclick="toggleChat()"><i class="fas fa-times"></i></button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages" class="flex-grow-1 p-4" style="overflow-y: auto; background-color: #f9fafb;">
                <div class="message system mb-4">
                    <div class="bg-white p-3 rounded-lg shadow-sm border border-light text-secondary" style="border-radius: 0 12px 12px 12px;">
                        👋 Hi! I'm your AI tutor. I can explain concepts on this card or give you examples. Just ask!
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-3 bg-white border-top">
                <div class="position-relative">
                    <input type="text" id="chat-input" class="form-control pl-4 pr-5 py-3 border-0 bg-light rounded-pill" 
                           style="height: 50px; letter-spacing: normal; font-family: 'Poppins', sans-serif;" 
                           placeholder="Ask a question..." onkeypress="handleEnter(event)">
                    <button class="btn text-primary position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%);" 
                            onclick="sendMessage()" id="send-btn">
                        <i class="fas fa-paper-plane fa-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content card-modern" style="border-radius: 20px; border: none;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold" id="summaryModalLabel">
            <i class="fas fa-bolt text-warning mr-2"></i> Smart Summary / Cheat Sheet
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
        <div id="summaryContent" class="text-muted" style="line-height: 1.6; white-space: pre-wrap;">
            @if($flashcard->summary)
                {!! nl2br(e($flashcard->summary)) !!}
            @else
                Generating summary... Please wait.
            @endif
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light btn-soft" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Pass flashcards data to JS -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script>
    const cards = @json($flashcard->cards);
    let currentIndex = 0;
    let isFlipped = false;
    let startTime = Date.now();
    let secondsSpentInCurrentSession = 0;

    const flashcardEl = document.getElementById('flashcard');
    const questionEl = document.getElementById('question-text');
    const answerEl = document.getElementById('answer-text');
    const currentNumEl = document.getElementById('current-card-num');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');

    // Update study time every minute or when moving between cards
    setInterval(() => {
        const now = Date.now();
        secondsSpentInCurrentSession = Math.floor((now - startTime) / 1000);
        if (secondsSpentInCurrentSession > 0 && secondsSpentInCurrentSession % 60 === 0) {
            saveProgress();
        }
    }, 1000);

    function updateCardDisplay() {
        const card = cards[currentIndex];
        
        // Reset flip
        isFlipped = false;
        flashcardEl.style.transform = 'rotateY(0deg)';

        // Update content with slight delay
        setTimeout(() => {
            questionEl.innerText = card.question;
            answerEl.innerText = card.answer;
        }, 150);

        // Update counters
        currentNumEl.innerText = currentIndex + 1;

        // Button states
        btnPrev.disabled = currentIndex === 0;
        if (currentIndex === 0) {
            btnPrev.classList.add('text-muted');
            btnPrev.classList.remove('shadow-sm');
        } else {
            btnPrev.classList.remove('text-muted');
            btnPrev.classList.add('shadow-sm');
        }

        btnNext.disabled = currentIndex === cards.length - 1;
        if (currentIndex === cards.length - 1) {
             btnNext.innerHTML = '<i class="fas fa-check fa-lg"></i>';
             btnNext.classList.remove('btn-primary-soft');
             btnNext.classList.add('btn-success');
             btnNext.onclick = finishStudy;
             btnNext.disabled = false; // Always enable finish button
        } else {
             btnNext.innerHTML = '<i class="fas fa-arrow-right fa-lg"></i>';
             btnNext.classList.add('btn-primary-soft');
             btnNext.classList.remove('btn-success');
             btnNext.onclick = nextCard;
        }
        
        // Save progress and reset session timer for this specific update
        saveProgress();
    }

    function saveProgress() {
        const now = Date.now();
        const deltaSeconds = Math.floor((now - startTime) / 1000);
        startTime = now; // Reset start time for next delta

        fetch('{{ route("flashcard.progress") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                flashcard_id: {{ $flashcard->id }},
                current_index: currentIndex,
                total_cards: cards.length,
                seconds_spent: deltaSeconds
            })
        }).catch(err => console.error('Failed to save progress', err));
    }

    function flipCard() {
        if (isFlipped) {
            flashcardEl.style.transform = 'rotateY(0deg)';
        } else {
            flashcardEl.style.transform = 'rotateY(180deg)';
        }
        isFlipped = !isFlipped;
    }

    function nextCard(e) {
        if (e) e.stopPropagation();
        if (currentIndex < cards.length - 1) {
            currentIndex++;
            updateCardDisplay();
        }
    }

    function prevCard(e) {
        if (e) e.stopPropagation();
        if (currentIndex > 0) {
            currentIndex--;
            updateCardDisplay();
        }
    }

    function finishStudy(e) {
        if(e) e.stopPropagation();
        saveProgress(); // Final save
        setTimeout(() => {
            window.location.href = "{{ route('flashcard.index') }}";
        }, 300); // Small delay to let fetch complete
    }

    // Initialize
    updateCardDisplay();

    // Keyboard support
    document.addEventListener('keydown', (e) => {
        // Ignore shortcuts if typing in an input
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        if (e.code === 'Space' || e.code === 'Enter') {
            e.preventDefault(); // Prevent scrolling
            flipCard();
        }
        if (e.code === 'ArrowRight') nextCard();
        if (e.code === 'ArrowLeft') prevCard();
    });

    // Chat Logic
    const chatWindow = document.getElementById('chat-window');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');

    function toggleChat() {
        if (chatWindow.style.display === 'none') {
            chatWindow.style.display = 'flex';
            // Chat entry animation
            chatWindow.style.opacity = '0';
            chatWindow.style.transform = 'translateY(20px)';
            setTimeout(() => {
                chatWindow.style.transition = 'all 0.3s ease';
                chatWindow.style.opacity = '1';
                chatWindow.style.transform = 'translateY(0)';
            }, 10);
        } else {
            chatWindow.style.display = 'none';
        }
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    function appendMessage(text, sender) {
        const div = document.createElement('div');
        div.className = `message ${sender} mb-3 d-flex ${sender === 'user' ? 'justify-content-end' : 'justify-content-start'}`;
        
        const bubble = document.createElement('div');
        if (sender === 'user') {
            bubble.className = 'p-3 rounded-lg bg-primary text-white shadow-sm';
            bubble.style.borderRadius = '12px 12px 0 12px';
        } else {
            bubble.className = 'p-3 rounded-lg bg-white shadow-sm text-secondary border border-light';
            bubble.style.borderRadius = '0 12px 12px 12px';
        }
        bubble.style.maxWidth = '85%';
        bubble.innerText = text;

        div.appendChild(bubble);
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendMessage(text, 'user');
        chatInput.value = '';
        sendBtn.disabled = true;

        const currentCard = cards[currentIndex];
        const context = `Question: ${currentCard.question}\nAnswer: ${currentCard.answer}`;

        // Show loading
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'message system mb-3 d-flex justify-content-start';
        loadingDiv.id = 'loading-msg';
        loadingDiv.innerHTML = '<div class="bg-white p-3 rounded-lg shadow-sm border border-light text-secondary small" style="border-radius: 0 12px 12px 12px;"><i class="fas fa-circle-notch fa-spin mr-2"></i> Thinking...</div>';
        chatMessages.appendChild(loadingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        fetch('{{ route("flashcard.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: text,
                context: context
            })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loading-msg').remove();
            appendMessage(data.reply, 'ai');
            sendBtn.disabled = false;
        })
        .catch(err => {
            document.getElementById('loading-msg').remove();
            appendMessage('Error connecting to AI.', 'system');
            sendBtn.disabled = false;
            console.error(err);
        });
    }
</script>

<style>
    body.dark-mode #card-front {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
    }
    
    body.dark-mode #card-back {
        background-color: rgba(34, 197, 94, 0.1) !important;
        border-color: rgba(34, 197, 94, 0.2) !important;
    }
    
    body.dark-mode #question-text { color: #f9fafb !important; }
    body.dark-mode #answer-text { color: #86efac !important; }
    body.dark-mode h3 { color: #f9fafb !important; }
    
    body.dark-mode #chat-window, body.dark-mode #chat-input {
        background-color: var(--card-bg) !important;
        color: var(--text-main) !important;
    }
    
    body.dark-mode #chat-messages {
        background-color: var(--bg-content) !important;
    }
    
    body.dark-mode .message.system .bg-white, 
    body.dark-mode .message.ai .bg-white {
        background-color: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    body.dark-mode kbd {
        background-color: var(--border-color) !important;
        color: var(--text-main) !important;
        border: none !important;
    }

    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .shadow-24 { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .rounded-24 { border-radius: 24px !important; }
    .hover-up:hover { transform: translateY(-5px); transition: transform 0.3s; }
</style>
@endsection
