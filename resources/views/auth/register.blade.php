<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create your journal - Expense Tracker</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body { background-color: #fcfcf8; color: #2b4535; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); width: 100%; max-width: 420px; padding: 3rem 2.5rem; border: 1px solid #eaeaea; }
        .auth-logo { display: flex; justify-content: center; margin-bottom: 1.5rem; color: #2b4535; }
        .auth-title { font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; }
        .auth-subtitle { font-size: 0.9rem; color: #6c757d; text-align: center; margin-bottom: 2rem; }
        .form-label { font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; }
        .form-control { border-radius: 6px; padding: 0.6rem 1rem; border: 1px solid #dee2e6; }
        .form-control:focus { border-color: #2b4535; box-shadow: 0 0 0 0.2rem rgba(43, 69, 53, 0.25); }
        .btn-custom { background-color: #2b4535; color: #fff; border-radius: 6px; padding: 0.6rem 1.25rem; font-weight: 600; width: 100%; margin-top: 1rem; border: none; }
        .btn-custom:hover { background-color: #1e3025; }
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; }
        .auth-link { color: #2b4535; font-weight: 600; text-decoration: none; }
        .invalid-feedback { display: block; font-size: 0.8rem; margin-top: 0.3rem; color: #dc3545; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.5 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm12-1v14h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zm-1 0H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h9V1z"/>
            </svg>
        </div>
        
        <h1 class="auth-title">Create your journal</h1>
        <p class="auth-subtitle">Start tracking with clarity</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Create a password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-custom">Continue &#9656;</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="/login" class="auth-link">Sign in</a>
        </div>
    </div>

</body>
</html>