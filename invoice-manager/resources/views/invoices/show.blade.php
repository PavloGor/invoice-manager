@extends('layouts.app')

@section('title', 'Інвойс #' . $invoice->number)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-invoice me-2"></i>
        Інвойс #{{ $invoice->number }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Редагувати
            </a>
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i>
                PDF
            </a>
            <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-envelope me-1"></i>
                    Надіслати
                </button>
            </form>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Назад
        </a>
    </div>
</div>

<div class="row">
    <!-- Invoice Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Деталі інвойсу
                </h5>
            </div>
            <div class="card-body">
                <div class="invoice-header mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-primary">ІНВОЙС</h3>
                            <p class="text-muted mb-0">Номер: <strong>{{ $invoice->number }}</strong></p>
                            <p class="text-muted">Дата створення: <strong>{{ $invoice->created_at->format('d.m.Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="status-container">
                                <span class="status-badge status-{{ $invoice->status }} fs-6">
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
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Client Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-building me-2"></i>
                            ВІД:
                        </h6>
                        <div class="company-info">
                            <h5>Ваша Компанія</h5>
                            <p class="mb-1">вул. Хрещатик, 1</p>
                            <p class="mb-1">Київ, 01001</p>
                            <p class="mb-1">Україна</p>
                            <p class="mb-0">info@yourcompany.com</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i>
                            ДО:
                        </h6>
                        <div class="client-info">
                            <h5>{{ $invoice->client }}</h5>
                            <p class="mb-1">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                {{ $invoice->client_email }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                Термін оплати: <strong>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Invoice Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>Опис</th>
                                <th class="text-end">Кількість</th>
                                <th class="text-end">Ціна</th>
                                <th class="text-end">Сума</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-bold">Послуги / Товари</div>
                                    <small class="text-muted">Опис наданих послуг або товарів</small>
                                </td>
                                <td class="text-end">1</td>
                                <td class="text-end">₴{{ number_format($invoice->amount, 2) }}</td>
                                <td class="text-end fw-bold">₴{{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment-info">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-credit-card me-2"></i>
                                ІНФОРМАЦІЯ ПРО ОПЛАТУ:
                            </h6>
                            <p class="mb-1"><strong>Банк:</strong> ПриватБанк</p>
                            <p class="mb-1"><strong>IBAN:</strong> UA123456789012345678901234567</p>
                            <p class="mb-0"><strong>Призначення:</strong> Оплата за інвойс {{ $invoice->number }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="invoice-total">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Підсумок:</strong></td>
                                    <td class="text-end"><strong>₴{{ number_format($invoice->amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>ПДВ (20%):</strong></td>
                                    <td class="text-end"><strong>₴{{ number_format($invoice->amount * 0.2, 2) }}</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>ДО СПЛАТИ:</strong></td>
                                    <td class="text-end">
                                        <h4 class="text-primary mb-0">₴{{ number_format($invoice->amount * 1.2, 2) }}</h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center text-muted">
                    <p class="mb-0">Дякуємо за співпрацю!</p>
                    <small>Цей інвойс згенеровано автоматично системою Invoice Manager</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Швидкі дії
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Редагувати інвойс
                    </a>
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-danger">
                        <i class="fas fa-download me-2"></i>
                        Завантажити PDF
                    </a>
                    <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-envelope me-2"></i>
                            Надіслати клієнту
                        </button>
                    </form>
                    <hr>
                    <a href="{{ route('invoices.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>
                        Створити новий
                    </a>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 btn-delete">
                            <i class="fas fa-trash me-2"></i>
                            Видалити інвойс
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info me-2"></i>
                    Інформація
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <small class="text-muted d-block">Створено:</small>
                    <strong>{{ $invoice->created_at->format('d.m.Y H:i') }}</strong>
                </div>
                <div class="info-item mb-3">
                    <small class="text-muted d-block">Оновлено:</small>
                    <strong>{{ $invoice->updated_at->format('d.m.Y H:i') }}</strong>
                </div>
                <div class="info-item mb-3">
                    <small class="text-muted d-block">Термін оплати:</small>
                    <strong class="{{ \Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status !== 'paid' ? 'text-danger' : 'text-success' }}">
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}
                        @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status !== 'paid')
                            <i class="fas fa-exclamation-triangle ms-1"></i>
                        @endif
                    </strong>
                </div>
                <div class="info-item">
                    <small class="text-muted d-block">ID інвойсу:</small>
                    <code>#{{ $invoice->id }}</code>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Історія
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Інвойс створено</h6>
                            <small class="text-muted">{{ $invoice->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                    </div>
                    @if($invoice->status === 'sent' || $invoice->status === 'paid')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Інвойс відправлено</h6>
                            <small class="text-muted">{{ $invoice->updated_at->format('d.m.Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                    @if($invoice->status === 'paid')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Інвойс оплачено</h6>
                            <small class="text-muted">{{ $invoice->updated_at->format('d.m.Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline:before {
    content: '';
    position: absolute;
    left: -24px;
    top: 10px;
    bottom: -10px;
    width: 2px;
    background: #dee2e6;
}

.invoice-total {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.company-info, .client-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.payment-info {
    background: #e3f2fd;
    border-radius: 8px;
    padding: 15px;
}
</style>
@endpush