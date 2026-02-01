<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
      :root {
          --primary-color: #6366f1; /* Soft Indigo */
          --primary-hover: #4f46e5;
          --bg-light: #f8fafc;
          --bg-content: #f1f5f9;
          --text-main: #0f172a;
          --text-muted: #64748b;
          --card-bg: #ffffff;
          --nav-bg: rgba(255, 255, 255, 0.8);
          --sidebar-bg: #1e293b;
          --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
          --card-shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
          --border-color: #f1f5f9;
          --glass-bg: rgba(255, 255, 255, 0.7);
          --glass-border: rgba(255, 255, 255, 0.3);
      }

      body.dark-mode {
          --bg-light: #020617;
          --bg-content: #020617;
          --text-main: #f8fafc;
          --text-muted: #94a3b8;
          --card-bg: #1e293b;
          --nav-bg: rgba(30, 41, 59, 0.8);
          --border-color: rgba(255, 255, 255, 0.05);
          --glass-bg: rgba(30, 41, 59, 0.7);
          --glass-border: rgba(255, 255, 255, 0.1);
      }

      .glass {
          background: var(--glass-bg);
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border: 1px solid var(--glass-border);
      }

      body {
          font-family: 'Poppins', sans-serif !important;
          background-color: var(--bg-light);
          color: var(--text-main);
          transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          letter-spacing: -0.01em;
      }
      .card-modern {
          border: 1px solid var(--border-color);
          border-radius: 20px;
          background: var(--card-bg);
          box-shadow: var(--card-shadow);
          transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          color: var(--text-main);
      }
      .card-modern:hover {
          transform: translateY(-8px) scale(1.01);
          box-shadow: var(--card-shadow-hover);
      }
      .btn-soft {
          border-radius: 14px;
          padding: 10px 20px;
          font-weight: 600;
          transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
          letter-spacing: 0.01em;
      }
      .btn-primary-soft {
          background-color: rgba(99, 102, 241, 0.1);
          color: var(--primary-color);
          border: 1px solid rgba(99, 102, 241, 0.1);
      }
      body.dark-mode .btn-primary-soft {
          background-color: rgba(129, 140, 248, 0.15);
          color: #a5b4fc;
      }
      .btn-primary-soft:hover {
          background-color: var(--primary-color);
          color: white;
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      }
      .content-wrapper {
          background-color: var(--bg-content) !important;
          transition: background-color 0.4s ease;
          padding-top: 20px;
      }
      .main-header {
          background-color: var(--nav-bg) !important;
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border-bottom: 1px solid var(--border-color) !important;
          transition: background-color 0.4s ease;
          padding: 0.75rem 1.5rem;
      }
      .main-header .nav-link {
          color: var(--text-main) !important;
          font-weight: 500;
      }
      
      /* Sidebar Modernization */
      .main-sidebar {
          background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
          backdrop-filter: blur(16px);
          -webkit-backdrop-filter: blur(16px);
          border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
          transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2) !important;
      }
      
      .brand-link {
          border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
          padding: 1.5rem 1.25rem !important;
          transition: all 0.3s;
          background: rgba(255, 255, 255, 0.02);
      }
      
      .brand-link:hover {
          background: rgba(255, 255, 255, 0.05);
      }

      .brand-link .brand-text {
          font-weight: 800 !important;
          letter-spacing: -0.03em;
          font-size: 1.3rem;
          background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
      }

      /* Sidebar Expanded Styles */
      body:not(.sidebar-collapse) .nav-sidebar .nav-item,
      .main-sidebar:hover .nav-sidebar .nav-item {
          margin-bottom: 6px;
          padding: 0 14px;
      }
      
      .nav-sidebar .nav-link {
          border-radius: 12px !important;
          padding: 12px 16px !important;
          transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
          font-weight: 500 !important;
          color: #94a3b8 !important;
          position: relative;
          overflow: hidden;
      }

      .nav-sidebar .nav-link i {
          transition: all 0.3s ease;
          font-size: 1.1rem;
          width: 28px;
          text-align: center;
      }

      .nav-sidebar .nav-link:hover {
          background: rgba(255, 255, 255, 0.05) !important;
          color: #fff !important;
          transform: translateX(4px);
      }

      .nav-sidebar .nav-link:hover i {
          transform: scale(1.1);
          color: var(--primary-color);
      }

      .nav-sidebar .nav-link.active {
          background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
          color: #fff !important;
          box-shadow: 0 8px 15px -3px rgba(79, 70, 229, 0.4);
          border: none !important;
      }

      .nav-sidebar .nav-link.active::before {
          content: '';
          position: absolute;
          left: 0;
          top: 25%;
          height: 50%;
          width: 4px;
          background: #fff;
          border-radius: 0 4px 4px 0;
          box-shadow: 0 0 10px #fff;
      }

      .nav-header {
          padding: 1.5rem 1.5rem 0.5rem !important;
          font-size: 0.65rem !important;
          font-weight: 800 !important;
          text-transform: uppercase !important;
          letter-spacing: 0.1em !important;
          color: #475569 !important;
      }
      
      /* Collapsed Sidebar Adjustments */
      body.sidebar-collapse .main-sidebar:not(:hover) .brand-link {
          padding-left: 0 !important;
          padding-right: 0 !important;
          margin-left: 0 !important;
          margin-right: 0 !important;
          width: 100% !important;
          display: flex !important;
          justify-content: center !important;
          align-items: center !important;
          height: 65px;
          overflow: hidden;
      }

      body.sidebar-collapse .main-sidebar:not(:hover) .brand-icon {
          display: none !important;
      }

      body.sidebar-collapse .main-sidebar:not(:hover) .brand-text-mini {
          display: block !important;
          width: 100% !important;
          text-align: center !important;
          margin: 0 !important;
          padding: 0 !important;
          line-height: 65px !important; /* Match brand-link height */
          font-weight: 800 !important;
          font-size: 1.1rem;
          color: #6366f1 !important;
          letter-spacing: 0.05em;
      }

      body.sidebar-collapse .main-sidebar:not(:hover) .nav-header {
          display: none !important;
      }

      .brand-text-mini {
          display: none;
      }
      
      /* Floating User Panel */
      .user-panel {
          background: rgba(255, 255, 255, 0.03) !important;
          border: 1px solid rgba(255, 255, 255, 0.05) !important;
          margin: 1.25rem 14px !important;
          border-radius: 16px !important;
          padding: 0.85rem !important;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
          transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
          display: flex;
          align-items: center;
      }
      
      .user-panel:hover {
          background: rgba(255, 255, 255, 0.08) !important;
          transform: translateY(-2px);
          border-color: rgba(255, 255, 255, 0.1) !important;
      }

      .user-panel .image img {
          border: 2px solid rgba(255, 255, 255, 0.2) !important;
          transition: transform 0.3s ease;
      }

      .user-panel:hover .image img {
          transform: scale(1.1);
      }

      .user-panel .info a {
          font-weight: 600 !important;
          color: #e2e8f0 !important;
          font-size: 0.9rem;
          transition: all 0.3s;
      }

      .user-panel .info a:hover {
          color: #fff !important;
          text-decoration: none;
      }

      /* Transitions and Fixes */
      body.sidebar-collapse .main-sidebar:not(:hover) .user-panel {
          margin: 1rem 8px !important;
          padding: 0.5rem !important;
          justify-content: center;
      }
      
      body.sidebar-collapse .main-sidebar:not(:hover) .user-panel .info {
          display: none !important;
      }
      
      /* Dark Mode Specific Sidebar */
      body.dark-mode .main-sidebar {
          background-color: #0f172a !important; /* Deeper slate for dark mode */
      }
      body.dark-mode .nav-sidebar .nav-link:hover {
          background: rgba(255, 255, 255, 0.08) !important;
      }

      /* Transitions and Fixes */
      body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-link i {
          margin-right: 0 !important;
          width: 100% !important;
          text-align: center;
      }

      /* Page Transitions */
      .fade-in {
          animation: fadeIn 0.5s ease-out;
      }
      @keyframes fadeIn {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
      }
  </style>

  <!-- AdminLTE Core -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Theme Toggle -->
      <li class="nav-item">
        <a class="nav-link" href="#" id="theme-toggle" title="Toggle Dark Mode">
          <i class="fas fa-moon"></i>
        </a>
      </li>

      <!-- Full Screen -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#"><i class="fas fa-expand-arrows-alt"></i></a>
      </li>

      <!-- Logout -->
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav-link btn btn-link" style="color: var(--text-main);">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </nav>
  <!-- End Navbar -->


  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand -->
    <a href="{{ url('/') }}" class="brand-link">
      <i class="fas fa-brain brand-icon" style="color: #6366f1;"></i>
      <span class="brand-text-mini">AIS</span>
      <span class="brand-text">AIStudy</span>
    </a>
    

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- User Info -->
      @auth
      <div class="user-panel d-flex align-items-center">
        <div class="image">
          @if(Auth::user()->profile_photo_path)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="img-circle" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);">
          @else
            <div class="img-circle bg-primary-soft d-flex justify-content-center align-items-center" style="width: 38px; height: 38px;">
              <i class="fas fa-user text-primary"></i>
            </div>
          @endif
        </div>
        <div class="info ml-2">
          <a href="{{ route('profile.edit') }}" class="d-block text-truncate" style="max-width: 130px;">{{ Auth::user()->name }}</a>
        </div>
      </div>
      @endauth
      
      <!-- Sidebar Menu -->
      <nav class="mt-2 pb-5">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-header">Main Menu</li>
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Analytics</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('flashcard.index') }}" class="nav-link {{ request()->routeIs('flashcard.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>My Decks</p>
            </a>
          </li>

          <li class="nav-header">Learning Center</li>
          <li class="nav-item">
            <a href="{{ route('flashcard.index') }}" class="nav-link">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>Practice Quiz</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('flashcard.quiz-history') }}" class="nav-link {{ request()->routeIs('flashcard.quiz-history') ? 'active' : '' }}">
              <i class="nav-icon fas fa-history"></i>
              <p>Performance</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('flashcard.favorites') }}" class="nav-link {{ request()->routeIs('flashcard.favorites') ? 'active' : '' }}">
              <i class="nav-icon fas fa-star"></i>
              <p>Starred Decks</p>
            </a>
          </li>

          <li class="nav-header">Networking</li>
          <li class="nav-item">
            <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-paper-plane"></i>
              <p>Messages</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('friends.index') }}" class="nav-link {{ request()->routeIs('friends.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>Friends</p>
            </a>
          </li>
        </ul>
      </nav>

    </div>
  </aside>
  <!-- End Sidebar -->


  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content p-4">
      <!-- Session Alerts -->
      {{-- Flash Notifications handled via SweetAlert2 below in scripts --}}

      @yield('content')
    </div>
  </div>

</div>

<!-- JS -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@yield('scripts')

<script>
    // Theme Toggle Logic
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle.querySelector('i');
    const body = document.body;

    // Check for saved theme
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    themeToggle.addEventListener('click', (e) => {
        e.preventDefault();
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            localStorage.setItem('theme', 'light');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    });

    // Handle Navbar class for AdminLTE
    function updateNavbar() {
        const navbar = document.querySelector('.main-header');
        if (body.classList.contains('dark-mode')) {
            navbar.classList.remove('navbar-white', 'navbar-light');
            navbar.classList.add('navbar-dark');
        } else {
            navbar.classList.add('navbar-white', 'navbar-light');
            navbar.classList.remove('navbar-dark');
        }
    }
    
    // Run on load and toggle
    updateNavbar();
    themeToggle.addEventListener('click', updateNavbar);

    // SweetAlert2 Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Flash Message Handling
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif
</script>

</body>
</html>
