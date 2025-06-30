@extends('layouts.app')

@section('title', 'Створити інвойс')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle me-2"></i>
        Створити інвойс
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                    Інформація про інвойс
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                    @csrf
                    
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
                                       value="{{ old('number', 'INV-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" 
                                       required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" 
                                       required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('client') }}" 
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
                                       value="{{ old('client_email') }}" 
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
                                           value="{{ old('amount') }}" 
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                        📝 Чернетка
                                    </option>
                                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>
                                        📧 Відправлено
                                    </option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>
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
                                  placeholder="Додаткова інформація про інвойс...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-undo me-1"></i>
                            Скинути
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Зберегти інвойс
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
                        <p class="fw-bold" id="preview-number">INV-0001</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Клієнт:</h6>
                        <p id="preview-client">-</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Email:</h6>
                        <p id="preview-email">-</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Сума:</h6>
                        <p class="fw-bold text-success" id="preview-amount">₴0.00</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Термін оплати:</h6>
                        <p id="preview-date">-</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Статус:</h6>
                        <span class="badge bg-secondary" id="preview-status">Не обрано</span>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Підказка:</strong> Попередній перегляд оновлюється автоматично при заповненні форми.
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightning-bolt me-2"></i>
                    Швидкі дії
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateRandomNumber()">
                        <i class="fas fa-dice me-1"></i>
                        Згенерувати номер
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillSampleData()">
                        <i class="fas fa-magic me-1"></i>
                        Тестові дані
                    </button>
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

    // Update preview function
    function updatePreview() {
        previewNumber.textContent = numberInput.value || 'INV-0001';
        previewClient.textContent = clientInput.value || '-';
        previewEmail.textContent = emailInput.value || '-';
        previewAmount.textContent = amountInput.value ? '₴' + parseFloat(amountInput.value).toFixed(2) : '₴0.00';
        previewDate.textContent = dueDateInput.value ? new Date(dueDateInput.value).toLocaleDateString('uk-UA') : '-';
        
        const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || 'Не обрано';
        const statusValue = statusSelect.value;
        previewStatus.textContent = statusText;
        previewStatus.className = 'badge ' + getStatusClass(statusValue);
    }

    function getStatusClass(status) {
        switch(status) {
            case 'draft': return 'bg-warning';
            case 'sent': return 'bg-info';
            case 'paid': return 'bg-success';
            default: return 'bg-secondary';
        }
    }

    // Add event listeners
    [numberInput, clientInput, emailInput, amountInput, dueDateInput, statusSelect].forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    // Initial preview update
    updatePreview();
});

// Generate random invoice number
function generateRandomNumber() {
    const random = Math.floor(Math.random() * 9999) + 1;
    const number = 'INV-' + random.toString().padStart(4, '0');
    document.querySelector('input[name="number"]').value = number;
    document.getElementById('preview-number').textContent = number;
}

// Fill sample data
function fillSampleData() {
    document.querySelector('input[name="client"]').value = 'Іван Петренко';
    document.querySelector('input[name="client_email"]').value = 'ivan.petrenko@example.com';
    document.querySelector('input[name="amount"]').value = '1500.00';
    document.querySelector('select[name="status"]').value = 'draft';
    
    // Update preview
    document.getElementById('preview-client').textContent = 'Іван Петренко';
    document.getElementById('preview-email').textContent = 'ivan.petrenko@example.com';
    document.getElementById('preview-amount').textContent = '₴1500.00';
    document.getElementById('preview-status').textContent = '📝 Чернетка';
    document.getElementById('preview-status').className = 'badge bg-warning';
}

// Form validation
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    const amount = document.querySelector('input[name="amount"]').value;
    if (amount <= 0) {
        e.preventDefault();
        alert('Сума повинна бути більше 0');
        return false;
    }
});
</script>
@endpush