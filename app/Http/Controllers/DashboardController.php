<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. ADMIN DASHBOARD LOGIC
        if ($user->is_admin) {
            // Count total rows across the entire system database tables
            $totalUsers = User::count();
            $totalExpenses = Expense::count();

            return view('dashboard', compact('totalUsers', 'totalExpenses'));
        }
        
        // 2. REGULAR USER DASHBOARD LOGIC 
        // Total Spent
        $totalSpent = $user->expenses()->sum('amount');
        
        // Transaction Count
        $transactionCount = $user->expenses()->count();
        
        // Daily Average (Pacing)
        $currentDay = now()->day;
        $dailyAverage = $currentDay > 0 ? ($totalSpent / $currentDay) : 0;
        
        // Top Category (Renamed to match {{ $topCategory }} in your Blade file)
        $topCategoryRecord = $user->expenses()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->first();

        $topCategory = $topCategoryRecord ? $topCategoryRecord->category : 'None';
        
        // Category Breakdown (for the "By Category" card)
        $categoryData = $user->expenses()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();
        
        // Recent Transactions
        $recentTransactions = $user->expenses()->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalSpent', 
            'transactionCount', 
            'dailyAverage', 
            'topCategory', 
            'recentTransactions',
            'categoryData'
        ));
    }
}