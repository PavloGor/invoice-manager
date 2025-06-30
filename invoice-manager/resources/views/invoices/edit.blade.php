@extends('layouts.app')

@section('title', 'Редагувати інвойс #' . $invoice->number)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Редагувати інвойс #{{ $invoice->number }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-1"></i>
                Переглянути
            </a>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Назад до списку
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Редагування інвойсу
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceEditForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>
                                    Номер інвойсу <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="number" 
                                       class="form-control @error('number') is-invalid @enderror" 
                                       placeholder="INV-001"
                                       value="{{ old('number', $invoice->number) }}" 
                                       required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Унікальний номер для ідентифікації інвойсу
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Термін оплати <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="due_date" 
                                       class="form-control @error('due_date') is-invalid @enderror" 
                                       value="{{ old('due_date', $invoice->due_date) }}" 
                                       required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status !== 'paid')
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Термін оплати минув!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Ім'я клієнта <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="client" 
                                       class="form-control @error('client') is-invalid @enderror" 
                                       placeholder="Іван Петренко"
                                       value="{{ old('client', $invoice->client) }}" 
                                       required>
                                @error('client')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email клієнта <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="client_email" 
                                       class="form-control @error('client_email') is-invalid @enderror" 
                                       placeholder="client@example.com"
                                       value="{{ old('client_email', $invoice->client_email) }}" 
                                       required>
                                @error('client_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Сума (₴) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₴</span>
                                    <input type="number" 
                                           step="0.01" 
                                           name="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           placeholder="0.00"
                                           value="{{ old('amount', $invoice->amount) }}" 
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    Сума з ПДВ: <span id="amount-with-vat" class="fw-bold">₴{{ number_format($invoice->amount * 1.2, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Статус <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Оберіть статус</option>
                                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>
                                        📝 Чернетка
                                    </option>
                                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>
                                        📧 Відправлено
                                    </option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>
                                        ✅ Оплачено
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-comment me-1"></i>
                            Опис / Примітки
                        </label>
                        <textarea name="description" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Додаткова інформація про інвойс...">{{ old('description', $invoice->description ?? '') }}</textarea>
                    </div>

                    <!-- Change History -->
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <i class="fas fa-info-circle me-2 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Інформація про зміни</h6>
                                <small>
                                    Створено: {{ $invoice->created_at->format('d.m.Y H:i') }}<br>
                                    Останнє оновлення: {{ $invoice->updated_at->format('d.m.Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Скасувати
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Зберегти зміни
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Попередній перегляд
                </h5>
            </div>
            <div class="card-body">
                <div class="invoice-preview">
                    <div class="mb-3">
                        <h6 class="text-muted">Номер інвойсу:</h6>
                        <p class="fw-bold" id="preview-number">{{ $invoice->number }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Клієнт:</h6>
                        <p id="preview-client">{{ $invoice->client }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Email:</h6>
                        <p id="preview-email">{{ $invoice->client_email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Сума:</h6>
                        <p class="fw-bold text-success" id="preview-amount">₴{{ number_format($invoice->amount, 2) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Термін оплати:</h6>
                        <p id="preview-date">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Статус:</h6>
                        <span class="badge status-{{ $invoice->status }}" id="preview-status">
                            @switch($invoice->status)
                                @case('draft')
                                    📝 Чернетка
                                    @break
                                @case('sent')
                                    📧 Відправлено
                                    @break
                                @case('paid')
                                    ✅ Оплачено
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <p class="text-muted mb-2">
                        <i class="fas fa-lightbulb me-1"></i>
                        Підказка
                    </p>
                    <small class="text-muted">
                        Попередній перегляд оновлюється автоматично при зміні даних форми
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Швидкі дії
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i>
                        Переглянути PDF
                    </a>
                    <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-envelope me-1"></i>
                            Надіслати клієнту
                        </button>
                    </form>
                    <hr>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 btn-delete">
                            <i class="fas fa-trash me-1"></i>
                            Видалити інвойс
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form inputs
    const numberInput = document.querySelector('input[name="number"]');
    const clientInput = document.querySelector('input[name="client"]');
    const emailInput = document.querySelector('input[name="client_email"]');
    const amountInput = document.querySelector('input[name="amount"]');
    const dueDateInput = document.querySelector('input[name="due_date"]');
    const statusSelect = document.querySelector('select[name="status"]');

    // Preview elements
    const previewNumber = document.getElementById('preview-number');
    const previewClient = document.getElementById('preview-client');
    const previewEmail = document.getElementById('preview-email');
    const previewAmount = document.getElementById('preview-amount');
    const previewDate = document.getElementById('preview-date');
    const previewStatus = document.getElementById('preview-status');
    const amountWithVat = document.getElementById('amount-with-vat');

    // Update preview function
    function updatePreview() {
        previewNumber.textContent = numberInput.value || '{{ $invoice->number }}';
        previewClient.textContent = clientInput.value || '{{ $invoice->client }}';
        previewEmail.textContent = emailInput.value || '{{ $invoice->client_email }}';
        
        const amount = parseFloat(amountInput.value) || {{ $invoice->amount }};
        previewAmount.textContent = '₴' + amount.toFixed(2);
        amountWithVat.textContent = '₴' + (amount * 1.2).toFixed(2);
        
        previewDate.textContent = dueDateInput.value ? 
            new Date(dueDateInput.value).toLocaleDateString('uk-UA') : 
            '{{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}';
        
        const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || 'Не обрано';
        const statusValue = statusSelect.value;
        previewStatus.textContent = statusText;
        previewStatus.className = 'badge ' + getStatusClass(statusValue);
    }

    function getStatusClass(status) {
        switch(status) {
            case 'draft': return 'status-draft';
            case 'sent': return 'status-sent';
            case 'paid': return 'status-paid';
            default: return 'bg-secondary';
        }
    }

    // Add event listeners
    [numberInput, clientInput, emailInput, amountInput, dueDateInput, statusSelect].forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    // Form validation
    document.getElementById('invoiceEditForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value);
        if (amount <= 0) {
            e.preventDefault();
            alert('Сума повинна бути більше 0');
            amountInput.focus();
            return false;
        }

        // Confirm changes
        if (!confirm('Ви впевнені, що хочете зберегти зміни?')) {
            e.preventDefault();
            return false;
        }
    });

    // Initial preview update
    updatePreview();
});
</script>
@endpush