<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Admin - Invoice Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="p-4 mb-6 rounded-lg bg-gray-50">
                        <form method="GET" class="flex flex-wrap items-end gap-4">
                            <div class="flex-1 min-w-64">
                                <label for="search" class="block text-sm font-medium text-gray-700">Search User</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       placeholder="Search by name or email"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="waiting_review" {{ request('status') === 'waiting_review' ? 'selected' : '' }}>Waiting Review</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </div>

                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select name="user_id" id="user_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Filter
                                </button>
                                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Invoice Stats -->
                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-5">
                        <div class="p-4 rounded-lg bg-blue-50">
                            <h3 class="text-lg font-semibold text-blue-800">Total Invoices</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $allFilteredInvoices->count() }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-yellow-50">
                            <h3 class="text-lg font-semibold text-yellow-800">Pending</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $allFilteredInvoices->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-orange-50">
                            <h3 class="text-lg font-semibold text-orange-800">Waiting Review</h3>
                            <p class="text-2xl font-bold text-orange-600">{{ $allFilteredInvoices->where('status', 'waiting_review')->count() }}</p>
                            @if($allFilteredInvoices->where('status', 'waiting_review')->count() > 0)
                                <button
                                    onclick="copyWaitingReviewToClipboard()"
                                    class="px-3 py-1 mt-2 text-xs text-white bg-orange-600 rounded hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    title="Copy waiting review payments to clipboard">
                                    Copy Details
                                </button>
                            @endif
                        </div>
                        <div class="p-4 rounded-lg bg-green-50">
                            <h3 class="text-lg font-semibold text-green-800">Paid</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $allFilteredInvoices->where('status', 'paid')->count() }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-red-50">
                            <h3 class="text-lg font-semibold text-red-800">Overdue</h3>
                            <p class="text-2xl font-bold text-red-600">{{ $allFilteredInvoices->filter(fn($invoice) => $invoice->isOverdue())->count() }}</p>
                        </div>
                    </div>

                    <!-- Overdue Invoices Histogram -->
                    @if($histogramData->sum('user_count') > 0)
                        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Distribution of Overdue Invoices</h3>
                            <p class="mb-4 text-sm text-gray-600">Number of users grouped by how many overdue invoices they have</p>
                            <div class="w-full h-96">
                                <canvas id="overdueHistogram"></canvas>
                            </div>
                        </div>
                    @endif

                    <!-- Invoices Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Period</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Due Date</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Paid At</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $invoice->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $invoice->user->email }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            ${{ number_format($invoice->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {{ $invoice->billing_period }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {{ $invoice->due_date->format('M d, Y') }}
                                            @if ($invoice->isOverdue())
                                                <span class="px-2 py-1 ml-2 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Overdue</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($invoice->status === 'paid') bg-green-100 text-green-800
                                                @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($invoice->status === 'waiting_review') bg-orange-100 text-orange-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($invoice->status === 'waiting_review')
                                                    Waiting Review
                                                @else
                                                    {{ ucfirst($invoice->status) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            <div>
                                                {{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y H:i') : '-' }}
                                                @if($invoice->receipt_path)
                                                    <div class="mt-1">
                                                        <a href="{{ route('invoices.show-receipt', $invoice) }}"
                                                           target="_blank"
                                                           class="text-xs text-blue-600 underline hover:text-blue-900">
                                                            View Receipt
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2">

                                                @if ($invoice->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-green-600 rounded hover:text-green-900 bg-green-50 hover:bg-green-100"
                                                                onclick="return confirm('Mark this invoice as paid?')">
                                                            Mark Paid
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.invoices.mark-waiting-review', $invoice) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-orange-600 rounded hover:text-orange-900 bg-orange-50 hover:bg-orange-100"
                                                                onclick="return confirm('Mark this invoice as waiting review?')">
                                                            Mark Waiting Review
                                                        </button>
                                                    </form>
                                                @elseif ($invoice->status === 'waiting_review')
                                                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-green-600 rounded hover:text-green-900 bg-green-50 hover:bg-green-100"
                                                                onclick="return confirm('Approve and mark this invoice as paid?')">
                                                            Approve & Mark Paid
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.invoices.mark-pending', $invoice) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-yellow-600 rounded hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100"
                                                                onclick="return confirm('Reject and mark this invoice as pending?')">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.invoices.mark-pending', $invoice) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-yellow-600 rounded hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100"
                                                                onclick="return confirm('Mark this invoice as pending?')">
                                                            Mark Pending
                                                        </button>
                                                    </form>
                                                    @if ($invoice->status === 'paid')
                                                        <form method="POST" action="{{ route('admin.invoices.mark-waiting-review', $invoice) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="px-3 py-1 text-orange-600 rounded hover:text-orange-900 bg-orange-50 hover:bg-orange-100"
                                                                    onclick="return confirm('Mark this invoice as waiting review?')">
                                                                Mark Waiting Review
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 text-red-600 rounded hover:text-red-900 bg-red-50 hover:bg-red-100"
                                                            onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No invoices found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function copyWaitingReviewToClipboard() {
            const waitingReviewInvoices = @json($waitingReviewPayments);

            if (waitingReviewInvoices.length === 0) {
                alert('No waiting review payments found.');
                return;
            }

            let textToCopy = 'Waiting Review Payments:\n\n';
            waitingReviewInvoices.forEach(function(invoice) {
                textToCopy += invoice.name + ' - $' + invoice.amount + ' - ' + invoice.paid_at + '\n';
            });

            navigator.clipboard.writeText(textToCopy).then(function() {
                alert('Payment details copied to clipboard!');
            }).catch(function(err) {
                console.error('Failed to copy text: ', err);
                alert('Failed to copy to clipboard. Please try again.');
            });
        }

        // Initialize overdue invoices histogram
        @if($histogramData->sum('user_count') > 0)
            document.addEventListener('DOMContentLoaded', function() {
                const histogramData = @json($histogramData);
                const ctx = document.getElementById('overdueHistogram').getContext('2d');
                
                const labels = histogramData.map(item => item.overdue_count + ' overdue invoice' + (item.overdue_count === 1 ? '' : 's'));
                const userCounts = histogramData.map(item => item.user_count);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Users',
                            data: userCounts,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Distribution of Overdue Invoices'
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Number of Overdue Invoices'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Number of Users'
                                },
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
        @endif
    </script>
</x-app-layout>
