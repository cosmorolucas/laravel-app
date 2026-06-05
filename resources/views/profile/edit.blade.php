<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        /* Modern Minimalist Ledger Theme Styles */
        body.ledger-bg { background-color: #f8f9f6; color: #1a1d20; }
        .font-serif { font-family: "Georgia", serif; letter-spacing: -0.5px; }
        .bg-dark-green { background-color: #2a3f34 !important; color: white !important; }
        .text-dark-green { color: #2a3f34; }
        
        .sidebar-wrapper { width: 260px; height: 100vh; position: fixed; border-right: 1px solid #eaeaea; background: #f8f9f6; display: flex; flex-direction: column; top: 0; left: 0; z-index: 100;}
        .main-content { margin-left: 260px; padding: 3rem 4rem; min-height: 100vh; }
        
        .nav-link-ledger { color: #6c757d; font-weight: 500; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 0.2rem; transition: all 0.2s; text-decoration: none;}
        .nav-link-ledger:hover { background-color: #eef0eb; color: #2a3f34; }
        .nav-link-ledger.active { background-color: #2a3f34; color: white; font-weight: 600; }
        
        .ledger-card { background: #ffffff; border-radius: 12px; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        
        .btn-ledger-dark { background-color: #2a3f34; color: white; border: none; font-weight: 500; border-radius: 8px; padding: 0.6rem 1.5rem; transition: background-color 0.2s; }
        .btn-ledger-dark:hover { background-color: #1c2b23; color: white; }
        
        .form-control-ledger { background-color: #f8f9f6; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; border-radius: 8px; color: #1a1d20; transition: all 0.2s; }
        .form-control-ledger:focus { background-color: #ffffff; border-color: #2a3f34; box-shadow: 0 0 0 3px rgba(42, 63, 52, 0.1); outline: none; }
        
        .user-profile-bottom { margin-top: auto; padding: 1.5rem; border-top: 1px solid #eaeaea; }
        .user-avatar { width: 36px; height: 36px; background: #8b5cf6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        .avatar-container { position: relative; width: 120px; height: 120px; margin: 0 auto; }
        .edit-overlay { position: absolute; bottom: 0; right: 0; background: #2a3f34; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; transition: transform 0.2s; }
        .edit-overlay:hover { transform: scale(1.05); color: white; }
    </style>
</head>
<body class="ledger-bg">

    <div class="sidebar-wrapper">
        <div class="p-4">
            <h3 class="font-serif fw-bold mb-4 d-flex align-items-center gap-2 text-dark-green">
                <i class="bi bi-book"></i> Expense Tracker
            </h3>
            
            <div class="nav flex-column gap-1">
                <a href="{{ route('dashboard') }}" class="nav-link-ledger {{ request()->routeIs('dashboard') ? 'active' : '' }} d-flex align-items-center gap-3">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                
                @if(auth()->user()->is_admin)
                    <a href="{{ route('users.index') }}" class="nav-link-ledger {{ request()->routeIs('users.*') ? 'active' : '' }} d-flex align-items-center gap-3">
                        <i class="bi bi-people"></i> User Management
                    </a>
                @else
                    <a href="{{ route('expenses.index') }}" class="nav-link-ledger {{ request()->routeIs('expenses.*') ? 'active' : '' }} d-flex align-items-center gap-3">
                        <i class="bi bi-receipt"></i> Expenses
                    </a>
                @endif
            </div>
        </div>
        
        <div class="user-profile-bottom">
            <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-3 mb-3 text-decoration-none text-dark">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <div class="fw-bold d-flex align-items-center gap-2" style="font-size: 0.9rem; line-height: 1;">
                        {{ auth()->user()->name }}
                        @if(auth()->user()->is_admin) 
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem; font-family: sans-serif; font-weight: 500;">Admin</span> 
                        @endif
                    </div>
                    <div class="text-muted" style="font-size: 0.8rem;">{{ auth()->user()->email }}</div>
                </div>
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light w-100 border text-start d-flex align-items-center gap-2 shadow-sm rounded-3">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h1 class="font-serif fw-bold m-0" style="font-size: 2.2rem; color: #1a1d20;">Account Settings</h1>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.95rem;">Manage your profile configuration details and security keys.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
            
            <div class="row align-items-start g-4">
                
                <div class="col-lg-4 col-xl-3">
                    <div class="ledger-card p-4 text-center">
                        <div class="avatar-container mb-3">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 2.5rem; background-color: #8b5cf6; font-weight: bold;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="profile_picture" class="edit-overlay"><i class="bi bi-camera-fill"></i></label>
                            <input type="file" name="profile_picture" id="profile_picture" class="d-none" accept="image/*">
                        </div>
                        <h5 class="fw-bold m-0" style="color: #1a1d20;">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="col-lg-8 col-xl-9">
                    <div class="ledger-card p-4 p-md-5 mb-4">
                        <h5 class="font-serif fw-bold mb-4 text-dark-green" style="font-size: 1.25rem;">Personal Details</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                                <input type="text" class="form-control form-control-ledger" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                                <input type="email" class="form-control form-control-ledger" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-ledger-dark px-4 rounded-3">Save Changes</button>
                        </div>
                    </div>
        </form>

                    <div class="ledger-card p-4 p-md-5">
                        <h5 class="font-serif fw-bold mb-4 text-dark-green" style="font-size: 1.25rem;">Security</h5>
                        
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf 
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                                <input type="password" class="form-control form-control-ledger" name="current_password" required>
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                                    <input type="password" class="form-control form-control-ledger" name="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                    <input type="password" class="form-control form-control-ledger" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-secondary px-4 rounded-3 fw-medium">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    @if(session('toast_success'))
        <div class="toast-container position-fixed bottom-0 end-0 p-4">
            <div id="successToast" class="toast align-items-center text-bg-success border-0 shadow-lg rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-medium px-3 py-2">
                        {{ session('toast_success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastEl = document.getElementById('successToast');
                var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>