<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker - Clarity in every transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-layout-text-sidebar" viewBox="0 0 16 16">
                    <path d="M3.5 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm12-1v14h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zm-1 0H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h9V1z"/>
                </svg>
                Expense Tracker
            </a>
            <div class="d-flex align-items-center gap-4">
                <a href="/login" class="nav-link text-decoration-none">Sign In</a>
                <a href="/register" class="btn btn-custom">Get Started</a>
            </div>
        </div>
    </nav>

    <main class="hero-section">
        <div class="container">
            <div class="pill-badge">Your personal finance journal</div>
            <h1 class="hero-title">Clarity in every<br>transaction.</h1>
            <p class="hero-subtitle">A calm, approachable expense tracker that helps you stay<br>financially aware without the noise of corporate dashboards.</p>
            <a href="/register" class="btn btn-custom btn-large">Start tracking today</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>