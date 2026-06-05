<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Auth::user()->expenses()->latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'expense_date' => ['required', 'date'],
        ]);

        // Saves the expense and attaches it directly to the logged-in user
        Auth::user()->expenses()->create($validated);

        return redirect()->route('expenses.index')->with('toast_success', 'Expense logged successfully!');
    }

    public function edit(Expense $expense)
    {
        // Inline Security Check: Ensure the logged-in user actually owns this expense record
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }

        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Inline Security Check: Ensure the logged-in user actually owns this expense record
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'expense_date' => ['required', 'date'],
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('toast_success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        // Inline Security Check: Ensure the logged-in user actually owns this expense record
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }

        $expense->delete();
        
        return redirect()->route('expenses.index')->with('toast_success', 'Expense deleted successfully!');
    }
}