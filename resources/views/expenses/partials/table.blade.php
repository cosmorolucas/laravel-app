<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="py-3 px-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #8792a2;">Date</th>
                <th class="py-3 px-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #8792a2;">Title</th>
                <th class="py-3 px-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #8792a2;">Category</th>
                <th class="py-3 px-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #8792a2;">Amount</th>
                <th class="py-3 px-4 text-end text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #8792a2;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td class="py-3 px-4 text-muted fw-medium">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                <td class="py-3 px-4 fw-bold" style="color: #1a1d20;">{{ $expense->title }}</td>
                <td class="py-3 px-4">
                    <span class="badge bg-light text-dark border px-2 py-1">{{ $expense->category }}</span>
                </td>
                <td class="py-3 px-4 fw-bold" style="color: #2a3f34;">₱{{ number_format($expense->amount, 2) }}</td>
                <td class="py-3 px-4 text-end">
                    <button type="button" 
                            class="btn btn-outline-secondary btn-sm px-3 py-1 me-2 rounded-pill fw-medium"
                            data-bs-toggle="modal" 
                            data-bs-target="#editExpenseModal"
                            data-url="{{ route('expenses.update', $expense) }}"
                            data-title="{{ $expense->title }}"
                            data-amount="{{ $expense->amount }}"
                            data-category="{{ $expense->category }}"
                            data-date="{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}">
                        Edit
                    </button>

                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1 rounded-pill fw-medium" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    You haven't logged any expenses yet. Start tracking today!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>