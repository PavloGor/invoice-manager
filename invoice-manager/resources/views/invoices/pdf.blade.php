<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інвойс #{{ $invoice->number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .invoice-title {
            font-size: 28px;
            color: #3498db;
            font-weight: bold;
            margin: 0;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-details > div {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .company-info, .client-info {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .company-info {
            background-color: #e3f2fd;
        }
        
        .section-title {
            font-size: 14px;
            color: #666;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        
        .totals table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .totals td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }
        
        .totals .total-row {
            font-weight: bold;
            font-size: 16px;
            background-color: #f8f9fa;
            border-top: 2px solid #3498db;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-sent {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .payment-info {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        
        .invoice-meta {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .due-date {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .amount-highlight {
            color: #27ae60;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="60%">
                    <h1 class="invoice-title">ІНВОЙС</h1>
                    <p style="margin: 5px 0; color: #666;">
                        Номер: <strong>{{ $invoice->number }}</strong><br>
                        Дата: <strong>{{ $invoice->created_at->format('d.m.Y') }}</strong>
                    </p>
                </td>
                <td width="40%" class="invoice-meta">
                    <div class="status-badge status-{{ $invoice->status }}">
                        @switch($invoice->status)
                            @case('draft')
                                Чернетка
                                @break
                            @case('sent')
                                Відправлено
                                @break
                            @case('paid')
                                Оплачено
                                @break
                        @endswitch
                    </div>
                    <br><br>
                    <p style="margin: 0;">
                        <strong>Термін оплати:</strong><br>
                        <span class="due-date">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="invoice-details">
        <div>
            <div class="company-info">
                <div class="section-title">Від:</div>
                <strong>Ваша Компанія ТОВ</strong><br>
                вул. Хрещатик, 1<br>
                Київ, 01001<br>
                Україна<br>
                Тел: +380 44 123-45-67<br>
                Email: info@yourcompany.com<br>
                ЄДРПОУ: 12345678
            </div>
        </div>
        <div>
            <div class="client-info">
                <div class="section-title">До:</div>
                <strong>{{ $invoice->client }}</strong><br>
                Email: {{ $invoice->client_email }}<br>
                <br>
                <strong>Дата інвойсу:</strong> {{ $invoice->created_at->format('d.m.Y') }}<br>
                <strong>Термін оплати:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="50%">Опис послуг/товарів</th>
                <th width="10%" class="text-right">К-сть</th>
                <th width="15%" class="text-right">Ціна за од.</th>
                <th width="15%" class="text-right">Сума</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>Надані послуги / Товари</strong><br>
                    <small style="color: #666;">Опис наданих послуг або проданих товарів згідно з договором</small>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">₴{{ number_format($invoice->amount, 2) }}</td>
                <td class="text-right amount-highlight">₴{{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Підсумок:</td>
                <td class="text-right">₴{{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr>
                <td>ПДВ (20%):</td>
                <td class="text-right">₴{{ number_format($invoice->amount * 0.2, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>ДО СПЛАТИ:</strong></td>
                <td class="text-right"><strong>₴{{ number_format($invoice->amount * 1.2, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <div class="section-title">Реквізити для оплати:</div>
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Отримувач:</strong> Ваша Компанія ТОВ<br>
                    <strong>Банк:</strong> АТ "ПриватБанк"<br>
                    <strong>МФО:</strong> 305299<br>
                    <strong>IBAN:</strong> UA123456789012345678901234567
                </td>
                <td width="50%">
                    <strong>Призначення платежу:</strong><br>
                    Оплата за інвойс {{ $invoice->number }} від {{ $invoice->created_at->format('d.m.Y') }}<br>
                    без ПДВ (якщо не платник ПДВ)
                </td>
            </tr>
        </table>
    </div>

    @if($invoice->status === 'paid')
    <div style="text-align: center; margin-top: 30px; padding: 15px; background-color: #d4edda; border-radius: 5px; color: #155724;">
        <strong>✓ ІНВОЙС ОПЛАЧЕНО</strong><br>
        <small>Дата оплати: {{ $invoice->updated_at->format('d.m.Y H:i') }}</small>
    </div>
    @endif

    <div class="footer">
        <p><strong>Дякуємо за співпрацю!</strong></p>
        <p>
            Цей документ згенеровано автоматично системою Invoice Manager<br>
            Дата генерації: {{ now()->format('d.m.Y H:i') }}
        </p>
        
        @if($invoice->status !== 'paid')
        <p style="color: #e74c3c; font-weight: bold;">
            Будь ласка, здійсніть оплату до {{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}
        </p>
        @endif
        
        <p style="font-size: 10px; margin-top: 20px;">
            З питань щодо цього інвойсу звертайтеся на email: info@yourcompany.com або за телефоном: +380 44 123-45-67
        </p>
    </div>
</body>
</html>