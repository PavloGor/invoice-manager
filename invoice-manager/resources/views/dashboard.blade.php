@extends('layouts.app')

@section('title', 'Дашборд - Invoice Manager')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Дашборд
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Новий інвойс
            </a>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <i class="fas fa-hand-wave fa-2x"></i>
        </div>
        <div class="flex-grow-1 ms-3">
            <h4 class="alert-heading">Вітаємо в Invoice Manager!</h4>
            <p class="mb-0">Керуйте своїми інвойсами легко та ефективно. Створюйте, відправляйте та відстежуйте платежі в одному місці.</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1" id="total-invoices">{{ $totalInvoices ?? 0 }}</h3>
                        <p class="mb-0">Всього інвойсів</p>
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +12% за місяць
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1" id="paid-invoices">{{ $paidInvoices ?? 0 }}</h3>
                        <p class="mb-0">Оплачено</p>
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +8% за тиждень
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1" id="pending-invoices">{{ $pendingInvoices ?? 0 }}</h3>
                        <p class="mb-0">Очікує оплати</p>
                        <small class="text-white-50">
                            <i class="fas fa-clock me-1"></i>
                            Потребує уваги
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">₴<span id="total-amount">{{ number_format($totalAmount ?? 0, 2) }}</span></h3>
                        <p class="mb-0">Загальна сума</p>
                        <small class="text-white-50">
                            <i class="fas fa-chart-line me-1"></i>
                            Всього за рік
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Activity -->
<div class="row">
    <!-- Charts -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Статистика інвойсів за місяць
                </h5>
            </div>
            <div class="card-body">
                <canvas id="invoiceChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Status Distribution -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-pie-chart me-2"></i>
                    Розподіл за статусами
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Останні інвойси
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($recentInvoices) && $recentInvoices->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentInvoices as $invoice)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $invoice->number }}</h6>
                                <small class="text-muted">{{ $invoice->client }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">₴{{ number_format($invoice->amount, 2) }}</div>
                                <span class="badge status-{{ $invoice->status }} bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'info' : 'warning') }} text-dark">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Інвойси відсутні</p>
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Створити перший інвойс
                        </a>
                    </div>
                @endif
            </div>
            @if(isset($recentInvoices) && $recentInvoices->count() > 0)
            <div class="card-footer text-center">
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-1"></i>
                    Переглянути всі
                </a>
            </div>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Швидкі дії
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Створити інвойс
                    </a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>
                        Переглянути всі інвойси
                    </a>
                    <button class="btn btn-outline-info" onclick="exportData()">
                        <i class="fas fa-download me-2"></i>
                        Експорт звіту
                    </button>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-cog me-2"></i>
                        Налаштування
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Invoices Alert -->
@if(isset($overdueInvoices) && $overdueInvoices > 0)
<div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle fa-2x"></i>
        </div>
        <div class="flex-grow-1 ms-3">
            <h5 class="alert-heading">Увага! Прострочені інвойси</h5>
            <p class="mb-0">
                У вас є <strong>{{ $overdueInvoices }}</strong> прострочених інвойсів. 
                <a href="{{ route('invoices.index') }}?overdue=1" class="alert-link">Переглянути їх</a>
            </p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Invoice Chart
    const ctx = document.getElementById('invoiceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Січ', 'Лют', 'Бер', 'Кві', 'Тра', 'Чер', 'Лип', 'Сер', 'Вер', 'Жов', 'Лис', 'Гру'],
            datasets: [{
                label: 'Інвойси',
                data: [12, 19, 8, 5, 12, 3, 15, 8, 10, 14, 7, 9],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4
            }, {
                label: 'Оплачені',
                data: [8, 15, 6, 3, 10, 2, 12, 6, 8, 11, 5, 7],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Оплачено', 'Відправлено', 'Чернетка'],
            datasets: [{
                data: [{{ $paidInvoices ?? 3 }}, {{ $sentInvoices ?? 2 }}, {{ $draftInvoices ?? 1 }}],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});

// Export function
function exportData() {
    alert('Функція експорту буде реалізована незабаром!');
}

// Auto-refresh stats every 30 seconds
setInterval(function() {
    // Here you would typically make an AJAX call to refresh stats
    console.log('Refreshing stats...');
}, 30000);
</script>
@endpush