@component('mail::message')
# Ваш інвойс

Номер: {{ $invoice->number }}
Клієнт: {{ $invoice->client }}
Сума: {{ $invoice->amount }}
Термін: {{ $invoice->due_date }}

Дякуємо за співпрацю!
@endcomponent
