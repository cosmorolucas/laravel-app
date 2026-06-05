<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        /* Modern Minimalist Ledger Theme Styles */
        body.ledger-bg { background-color: #f8f9f6; color: #1a1d20; }
        .font-serif { font-family: "Georgia", serif; letter-spacing: -0.5px; }
        .bg-dark-green { background-color: #2a3f34 !important; color: white !important; }
        .text-dark-green { color: #2a3f34; }
        
        .sidebar-wrapper { width: 260px; height: 100vh; position: fixed; border-right: 1px solid #eaeaea; background: #f8f9f6; display: flex; flex-direction: column; top: 0; left: 0;}
        .main-content { margin-left: 260px; padding: 3rem 4rem; min-height: 100vh; }
        
        .nav-link-ledger { color: #6c757d; font-weight: 500; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 0.2rem; transition: all 0.2s; text-decoration: none;}
        .nav-link-ledger:hover { background-color: #eef0eb; color: #2a3f34; }
        .nav-link-ledger.active { background-color: #2a3f34; color: white; font-weight: 600; }
        
        .ledger-card { background: #ffffff; border-radius: 12px; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); padding: 2rem; }
        
        .btn-ledger-dark { background-color: #2a3f34; color: white; border: none; font-weight: 500; border-radius: 8px; padding: 0.6rem 1.5rem; transition: background-color 0.2s; }
        .btn-ledger-dark:hover { background-color: #1c2b23; color: white; }
        
        /* Modal Custom Styling to match Ledger Theme */
        .modal-content-ledger { border-radius: 16px; border: none; background-color: #f8f9f6; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .modal-header-ledger { border-bottom: 1px solid #eaeaea; padding: 1.5rem 2rem; background: #ffffff; border-top-left-radius: 16px; border-top-right-radius: 16px; }
        .modal-body-ledger { padding: 2rem; background: #ffffff; }
        .modal-footer-ledger { border-top: 1px solid #eaeaea; padding: 1.2rem 2rem; background: #f8f9f6; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }
        
        .form-control-ledger { background-color: #f8f9f6; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; border-radius: 8px; color: #1a1d20; transition: all 0.2s; }
        .form-control-ledger:focus { background-color: #ffffff; border-color: #2a3f34; box-shadow: 0 0 0 3px rgba(42, 63, 52, 0.1); outline: none; }
        
        .user-profile-bottom { margin-top: auto; padding: 1.5rem; border-top: 1px solid #eaeaea; }
        .user-avatar { width: 36px; height: 36px; background: #8b5cf6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="font-serif fw-bold" style="font-size: 2.2rem; color: #1a1d20;">System Users</h1>
                <p class="text-muted" style="font-size: 0.95rem;">Manage and audit accounts registered across your platform.</p>
            </div>
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-ledger-dark shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Add New User
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="ledger-card">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr style="border-bottom: 2px solid #f0f0f0;">
                                    <th class="py-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #8792a2; font-weight: 600;">Name</th>
                                    <th class="py-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #8792a2; font-weight: 600;">Email address</th>
                                    <th class="py-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #8792a2; font-weight: 600;">Created Date</th>
                                    <th class="py-3 text-end text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #8792a2; font-weight: 600; padding-right: 1rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr style="border-bottom: 1px solid #f8f9f6;">
                                    <td class="py-3 font-serif fw-bold" style="color: #1a1d20; font-size: 1.05rem;">{{ $user->name }}</td>
                                    <td class="py-3 text-muted" style="font-size: 0.95rem;">{{ $user->email }}</td>
                                    <td class="py-3 text-muted" style="font-size: 0.95rem;">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 text-end" style="padding-right: 1rem;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1 me-1 rounded-pill fw-medium" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            Edit
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3 py-1 rounded-pill fw-medium" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content modal-content-ledger">
                                            <div class="modal-header-ledger d-flex justify-content-between align-items-center">
                                                <h5 class="modal-title font-serif fw-bold text-dark-green" style="font-size: 1.3rem;">Edit User Credentials</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('users.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body-ledger">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                                                        <input type="text" name="name" class="form-control form-control-ledger" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                                                        <input type="email" name="email" class="form-control form-control-ledger" value="{{ $user->email }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer-ledger d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-light border px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-ledger-dark px-4 rounded-3">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content modal-content-ledger">
                                            <div class="modal-header-ledger d-flex justify-content-between align-items-center">
                                                <h5 class="modal-title font-serif fw-bold text-danger" style="font-size: 1.2rem;">Remove Account</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body-ledger py-4 text-center">
                                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i>
                                                <p class="mt-3 mb-0 text-dark" style="font-size: 0.95rem;">
                                                    Are you absolutely sure you want to delete <strong>{{ $user->name }}</strong>? This process cannot be undone.
                                                </p>
                                            </div>
                                            <div class="modal-footer-ledger d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light border px-3 rounded-3" data-bs-dismiss="modal">Go Back</button>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger px-3 rounded-3">Confirm Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
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