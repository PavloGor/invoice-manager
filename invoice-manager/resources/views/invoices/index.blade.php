@extends('layouts.app')

@section('title', 'Список інвойсів')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-invoice me-2"></i>
        Інвойси
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Створити інвойс
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $invoices->count() }}</h4>
                        <p class="mb-0">Всього інвойсів</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $invoices->where('status', 'paid')->count() }}</h4>
                        <p class="mb-0">Оплачено</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $invoices->where('status', 'sent')->count() }}</h4>
                        <p class="mb-0">Відправлено</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-paper-plane fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card mb-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">₴{{ number_format($invoices->sum('amount'), 2) }}</h4>
                        <p class="mb-0">Загальна сума</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Пошук</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Номер інвойсу або клієнт..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Статус</label>
                    <select class="form-select" name="status">
                        <option value="">Всі статуси</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Чернетка</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Відправлено</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Оплачено</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Дата від</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>
                            Фільтр
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Invoices Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Список інвойсів
        </h5>
    </div>
    <div class="card-body p-0">
        @if($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Номер</th>
                            <th>Клієнт</th>
                            <th>Email</th>
                            <th>Сума</th>
                            <th>Термін</th>
                            <th>Статус</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td><strong>#{{ $invoice->id }}</strong></td>
                            <td>{{ $invoice->number }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    {{ $invoice->client }}
                                </div>
                            </td>
                            <td>{{ $invoice->client_email }}</td>
                            <td><strong>₴{{ number_format($invoice->amount, 2) }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $invoice->status }}">
                                    @switch($invoice->status)
                                        @case('draft')
                                            <i class="fas fa-edit me-1"></i>Чернетка
                                            @break
                                        @case('sent')
                                            <i class="fas fa-paper-plane me-1"></i>Відправлено
                                            @break
                                        @case('paid')
                                            <i class="fas fa-check me-1"></i>Оплачено
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-cogs me-1"></i>
                                        Дії
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('invoices.show', $invoice) }}">
                                                <i class="fas fa-eye me-2"></i>Переглянути
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('invoices.edit', $invoice) }}">
                                                <i class="fas fa-edit me-2"></i>Редагувати
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('invoices.pdf', $invoice) }}">
                                                <i class="fas fa-file-pdf me-2"></i>Завантажити PDF
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-envelope me-2"></i>Надіслати email
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-delete">
                                                    <i class="fas fa-trash me-2"></i>Видалити
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Інвойси не знайдено</h5>
                <p class="text-muted">Створіть свій перший інвойс</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Створити інвойс
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
}
</style>
@endpush