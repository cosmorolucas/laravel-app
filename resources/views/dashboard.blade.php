<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .ledger-card { background: #ffffff; border-radius: 12px; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); padding: 1.5rem; height: 100%; }
        .ledger-card-dark { background: #2a3f34; border-radius: 12px; border: none; padding: 1.5rem; color: white; height: 100%; }
        
        .stat-label { font-size: 0.85rem; color: #8792a2; font-weight: 500; margin-bottom: 0.5rem; }
        .stat-label-dark { font-size: 0.85rem; color: rgba(255,255,255,0.8); font-weight: 500; margin-bottom: 0.5rem; }
        .stat-number { font-size: 2rem; font-weight: 800; color: #1a1d20; line-height: 1.2; }
        
        .chart-placeholder { height: 250px; width: 100%; display: flex; align-items: center; justify-content: center; color: #8792a2; font-size: 0.9rem; }
        
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
        <div class="mb-4">
            <h1 class="font-serif fw-bold" style="font-size: 2.2rem; color: #1a1d20;">
                {{ auth()->user()->is_admin ? 'Dashboard Overview' : 'Overview' }}
            </h1>
            <p class="text-muted" style="font-size: 0.95rem;">
                {{ auth()->user()->is_admin ? "Here is what's happening with your system today." : "Your financial summary for this month." }}
            </p>
        </div>

        @if(auth()->user()->is_admin)
            <div class="row mb-4 g-4">
                <div class="col-md-6">
                    <div class="ledger-card d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label">Total System Users</div>
                            <i class="bi bi-people text-muted"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">Active accounts registered</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="ledger-card d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label">Total System Expenses Logged</div>
                            <i class="bi bi-receipt text-muted"></i>
                        </div>
                        <div>
                            <div class="stat-number text-success">{{ $totalExpenses ?? 0 }}</div>
                            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">Logs tracked across platform</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="ledger-card">
                        <h5 class="font-serif fw-bold mb-4">System Records Overview</h5>
                        <div style="height: 320px; width: 100%;">
                            <canvas id="systemChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="ledger-card-dark d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label-dark">Total Spent</div>
                            <i class="bi bi-wallet2 text-white opacity-75"></i>
                        </div>
                        <div>
                            <div class="stat-number text-white mb-1">₱{{ number_format($totalSpent ?? 0, 2) }}</div>
                            <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">
                                <i class="bi bi-graph-down-arrow"></i> Recorded this month
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="ledger-card d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label">Transactions</div>
                            <i class="bi bi-clock-history text-muted"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $transactionCount ?? 0 }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Recorded this month</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="ledger-card d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label">Daily Average</div>
                            <i class="bi bi-calculator text-muted"></i>
                        </div>
                        <div>
                            <div class="stat-number">₱{{ number_format($dailyAverage ?? 0, 2) }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Pacing for the month</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="ledger-card d-flex flex-column justify-content-between" style="min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-label">Top Category</div>
                            <i class="bi bi-tag text-muted"></i>
                        </div>
                        <div>
                            <div class="font-serif fw-bold" style="font-size: 1.8rem; color: #1a1d20; line-height: 1.2;">{{ $topCategory ?? 'None' }}</div>
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">Highest spend this month</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-7">
                    <div class="ledger-card">
                        <h5 class="font-serif fw-bold mb-3">Spending Trend</h5>
                        <div style="height: 250px; width: 100%;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="ledger-card">
                        <h5 class="font-serif fw-bold mb-3">By Category</h5>
                        @if(isset($categoryData) && $categoryData->count() > 0)
                            <ul class="list-unstyled mt-3">
                                @foreach($categoryData as $data)
                                    <li class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">{{ $data->category }}</span>
                                        <span class="fw-bold">₱{{ number_format($data->total, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="chart-placeholder">No categorical spending yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="ledger-card">
                        <h5 class="font-serif fw-bold mb-4">Recent Transactions</h5>
                        @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        @foreach($recentTransactions as $expense)
                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                            <td class="py-3">
                                                <div class="fw-bold" style="color: #1a1d20;">{{ $expense->title }}</div>
                                                <div class="text-muted" style="font-size: 0.8rem;">{{ $expense->category }}</div>
                                            </td>
                                            <td class="py-3 text-muted" style="font-size: 0.9rem;">
                                                {{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 text-end fw-bold" style="color: #2a3f34;">
                                                ₱{{ number_format($expense->amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                No recent transactions to display.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
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
    
    @if(auth()->user()->is_admin)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('systemChart');
            if(ctx) {
                const chartCtx = ctx.getContext('2d');
                
                const gradientPrimary = chartCtx.createLinearGradient(0, 0, 0, 400);
                gradientPrimary.addColorStop(0, '#2a3f34');
                gradientPrimary.addColorStop(1, '#15201a');

                const gradientSecondary = chartCtx.createLinearGradient(0, 0, 0, 400);
                gradientSecondary.addColorStop(0, '#eef0eb');
                gradientSecondary.addColorStop(1, '#d5dad0');

                new Chart(chartCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Users', 'Total Expenses'],
                        datasets: [{
                            label: 'Records Count',
                            data: [{{ $totalUsers ?? 0 }}, {{ $totalExpenses ?? 0 }}],
                            backgroundColor: [gradientPrimary, gradientSecondary],
                            borderRadius: 8,
                            barPercentage: 0.3,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f0f0f0', drawBorder: false },
                                ticks: { stepSize: 1, font: { family: "Georgia, serif", size: 13 }, color: '#8792a2' }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: { font: { family: "Georgia, serif", size: 14, weight: '600' }, color: '#1a1d20' }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: '#2a3f34', padding: 12, cornerRadius: 8 }
                        }
                    }
                });
            }
        });
    </script>
    @else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trendCtx = document.getElementById('trendChart');
            if(trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
                        datasets: [{
                            data: [0, 0, 0, 0, 0, {{ $totalSpent ?? 0 }}], 
                            borderColor: '#2a3f34',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#2a3f34',
                            tension: 0.15
                        }]
                    },
                    options: {
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, border: {display: false}, grid: {color: '#f0f0f0'}, ticks: {color: '#a0aab5', callback: function(value) { return '₱' + value; }} },
                            x: { border: {display: false}, grid: {display: false}, ticks: {color: '#a0aab5'} }
                        }
                    }
                });
            }
        });
    </script>
    @endif
</body>
</html>