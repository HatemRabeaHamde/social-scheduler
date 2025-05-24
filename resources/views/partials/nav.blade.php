<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" id="navbar">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-white" href="{{ route('dashboard') }}" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
            ContentScheduler
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @php $activePost = request()->routeIs('posts.*') ? 'active' : ''; @endphp
                <li class="nav-item">
                    <a class="nav-link {{ $activePost }}" href="{{ route('posts.index') }}">
                        Posts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.analytics') ? 'active' : '' }}" href="{{ route('posts.analytics') }}">
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.activity-log') ? 'active' : '' }}" href="{{ route('posts.activity-log') }}">
                        Activity Log
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('platforms.*') ? 'active' : '' }}" href="{{ route('platforms.index') }}">
                        Platforms
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3">
                    <button id="toggle-theme" class="btn btn-outline-light btn-sm shadow-sm rounded-pill" 
                        style="transition: background-color 0.3s, color 0.3s;">
                        Dark Mode
                    </button>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white fw-semibold"
                            style="text-decoration: none; transition: color 0.3s;">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    body.light-mode {
        background: linear-gradient(135deg, #e0e7ff, #f8f9fa);
        color: #212529;
        transition: background 0.5s ease, color 0.5s ease;
    }
    body.light-mode .navbar {
        background: linear-gradient(45deg, #0d6efd, #0a58ca) !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.6);
        transition: background 0.5s ease;
    }
    body.light-mode .nav-link {
        color: #ffffff !important;
        font-weight: 600;
        position: relative;
        transition: color 0.3s;
    }
    body.light-mode .nav-link.active,
    body.light-mode .nav-link:hover {
        color: #ffe600 !important;
        text-shadow: 0 0 10px #ffe600;
    }

    body.dark-mode {
        background: linear-gradient(135deg, #121212, #1e1e1e);
        color: #e4e6eb;
        transition: background 0.5s ease, color 0.5s ease;
    }
    body.dark-mode .navbar {
        background: linear-gradient(45deg, #343a40, #212529) !important;
        box-shadow: 0 4px 12px rgba(33, 37, 41, 0.8);
        transition: background 0.5s ease;
    }
    body.dark-mode .nav-link {
        color: #e4e6eb !important;
        font-weight: 600;
        position: relative;
        transition: color 0.3s;
    }
    body.dark-mode .nav-link.active,
    body.dark-mode .nav-link:hover {
        color: #00d8ff !important;
        text-shadow: 0 0 8px #00d8ff;
    }

    #toggle-theme:hover {
        background-color: #fff !important;
        color: #0d6efd !important;
    }
    form button[type="submit"]:hover {
        color: #ffc107 !important;
        text-shadow: 0 0 6px #ffc107;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggle-theme');
        const currentTheme = localStorage.getItem('theme') || 'light';

        setTheme(currentTheme);

        toggleBtn.addEventListener('click', () => {
            const newTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function setTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
                toggleBtn.textContent = 'Light Mode';
                toggleBtn.classList.remove('btn-outline-light');
                toggleBtn.classList.add('btn-outline-info');
            } else {
                document.body.classList.add('light-mode');
                document.body.classList.remove('dark-mode');
                toggleBtn.textContent = 'Dark Mode';
                toggleBtn.classList.remove('btn-outline-info');
                toggleBtn.classList.add('btn-outline-light');
            }
        }
    });
</script>
