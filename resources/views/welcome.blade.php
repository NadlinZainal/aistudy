<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>AIStudy - Master Any Subject with AI</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        <style>
            :root {
                --primary: #6366f1;
                --primary-hover: #4f46e5;
                --secondary: #10b981;
                --dark: #0f172a;
                --light: #f8fafc;
                --text-muted: #64748b;
                --glass: rgba(255, 255, 255, 0.7);
                --glass-border: rgba(255, 255, 255, 0.3);
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: 'Poppins', sans-serif;
            }

            body {
                background-color: var(--light);
                color: var(--dark);
                line-height: 1.6;
                overflow-x: hidden;
            }

            /* Animated Background */
            .bg-blob {
                position: fixed;
                width: 500px;
                height: 500px;
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(167, 139, 250, 0.2) 100%);
                filter: blur(80px);
                border-radius: 50%;
                z-index: -1;
                animation: float 20s infinite alternate;
            }

            .blob-1 { top: -100px; right: -100px; }
            .blob-2 { bottom: -100px; left: -100px; animation-delay: -5s; }

            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                100% { transform: translate(100px, 50px) scale(1.1); }
            }

            /* Container */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            /* Navbar */
            nav {
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: var(--glass);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--glass-border);
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .logo {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--primary);
                display: flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
            }

            .nav-links {
                display: flex;
                gap: 2rem;
                align-items: center;
            }

            .nav-links a {
                text-decoration: none;
                color: var(--dark);
                font-weight: 500;
                transition: color 0.3s;
            }

            .nav-links a:hover {
                color: var(--primary);
            }

            .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                text-decoration: none;
                display: inline-block;
            }

            .btn-primary {
                background-color: var(--primary);
                color: white;
                box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            }

            .btn-primary:hover {
                background-color: var(--primary-hover);
                transform: translateY(-2px);
                box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
            }

            .btn-outline {
                border: 2px solid var(--primary);
                color: var(--primary);
            }

            .btn-outline:hover {
                background-color: var(--primary);
                color: white;
            }

            /* Hero Section */
            .hero {
                padding: 8rem 0;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero h1 {
                font-size: 4rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                line-height: 1.1;
                background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                max-width: 900px;
            }

            .hero p {
                font-size: 1.25rem;
                color: var(--text-muted);
                max-width: 700px;
                margin-bottom: 3rem;
            }

            .hero-btns {
                display: flex;
                gap: 1.5rem;
            }

            /* Features Section */
            .features {
                padding: 8rem 0;
            }

            .section-title {
                text-align: center;
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 4rem;
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
            }

            .feature-card {
                background: var(--glass);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid var(--glass-border);
                padding: 2.5rem;
                border-radius: 24px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .feature-card:hover {
                transform: translateY(-10px);
                background: white;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                background-color: rgba(99, 102, 241, 0.1);
                color: var(--primary);
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 16px;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .feature-card h3 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .feature-card p {
                color: var(--text-muted);
            }

            /* Animations */
            .fade-in {
                animation: fadeIn 1s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero h1 { font-size: 2.5rem; }
                .nav-links { display: none; }
                .hero-btns { flex-direction: column; width: 100%; }
                .btn { width: 100%; text-align: center; }
            }
        </style>
    </head>
    <body>
        <div class="bg-blob blob-1"></div>
        <div class="bg-blob blob-2"></div>

        <nav>
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <a href="/" class="logo">
                    <i class="fas fa-brain"></i> AIStudy
                </a>
                <div class="nav-links">
                    @auth
                        <a href="{{ route('login') }}">Log in</a>
                        <a href="{{ url('/home') }}" class="btn btn-primary">Get Started</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <section class="hero container fade-in">
            <h1>Unlock Your Full Potential with AI-Powered Learning</h1>
            <p>Master any subject faster than ever. Generate smart flashcards, take interactive quizzes, and track your progress with your personal AI tutor.</p>
            <div class="hero-btns">
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-primary">Get Started</a>
                    <a href="{{ url('/home') }}" class="btn btn-outline">Log In</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
                @endauth
            </div>
        </section>

        <section class="features container">
            <h2 class="section-title fade-in" style="animation-delay: 0.2s;">Everything You Need to Excel</h2>
            <div class="features-grid">
                <div class="feature-card fade-in" style="animation-delay: 0.4s;">
                    <div class="feature-icon"><i class="fas fa-magic"></i></div>
                    <h3>AI Generation</h3>
                    <p>Simply upload your notes or paste a URL, and our AI will generate comprehensive flashcard decks in seconds.</p>
                </div>
                <div class="feature-card fade-in" style="animation-delay: 0.5s;">
                    <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h3>Smart Quizzes</h3>
                    <p>Test your knowledge with adaptive quizzes that focus on areas where you need the most improvement.</p>
                </div>
                <div class="feature-card fade-in" style="animation-delay: 0.6s;">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Progress Tracking</h3>
                    <p>Visualize your learning journey with detailed analytics and insights into your study habits.</p>
                </div>
                <div class="feature-card fade-in" style="animation-delay: 0.7s;">
                    <div class="feature-icon"><i class="fas fa-robot"></i></div>
                    <h3>Telegram Bot</h3>
                    <p>Connect with our AI tutor on Telegram for daily micro-learning and instant assistance anytime, anywhere.</p>
                </div>
            </div>
        </section>

        <footer style="margin-top: 8rem; padding: 4rem 0; border-top: 1px solid var(--glass-border); text-align: center; background: var(--glass); backdrop-filter: blur(12px);">
            <div class="container">
                <div class="logo" style="justify-content: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-brain"></i> AIStudy
                </div>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Empowering students with artificial intelligence.</p>
                <p style="color: var(--text-muted); font-size: 0.875rem;">&copy; {{ date('Y') }} AIStudy. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
