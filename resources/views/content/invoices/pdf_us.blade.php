@php
  $sym = $symbol ?: '';
  $cPrimary = '#1b84e7'; $cMuted='#6c757d'; $cLight='#f5f7fb'; $cLine='#e9edf2';
  $badgeMap = ['draft'=>'#adb5bd','submitted'=>'#1b84e7','finance_approved'=>'#00a76f','sent'=>'#ffb400','paid'=>'#16b364','rejected'=>'#e03131'];
  $badge = $badgeMap[$invoice->status] ?? '#adb5bd';
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice {{ $invoice->invoice_no }}</title>
<style>
  body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#222; font-size: 12px; }
  .header { display:flex; justify-content: space-between; gap:20px; }
  .brand h2 { margin:0 0 6px 0; color:#111; }
  .muted { color: {{ $cMuted }}; }
  .pre { white-space: pre-line; }
  .pill { display:inline-block; padding:2px 8px; border-radius: 999px; color:#fff; font-size: 11px; background: {{ $badge }}; }
  .box { border:1px solid {{ $cLine }}; padding:10px; border-radius:6px; background:#fff; }
  .meta { width: 45%; }
  .tbl { width:100%; border-collapse: collapse; }
  .tbl th, .tbl td { border:1px solid {{ $cLine }}; padding:8px; vertical-align: top; }
  .tbl thead th { background: {{ $cLight }}; color:#111; }
  .grid { display:flex; gap: 16px; }
  .w-50 { width: 50%; }
  .title { color: {{ $cPrimary }}; font-weight: 700; }
  .totals { width: 50%; margin-left: auto; }
  .right { text-align: right; }
  .mb-2 { margin-bottom: 8px; }
  .mb-3 { margin-bottom: 12px; }
  .mb-4 { margin-bottom: 16px; }
</style>
</head>
<body>

<div class="header mb-4">
  <div class="brand" style="width:55%">
    <h2>{{ $company['name'] ?? 'Company' }}</h2>
    <div class="pre muted">{{ $company['address'] ?? '' }}</div>
    <div class="mb-2"></div>
    <div class="muted">Reference No: <b>{{ $company['ref'] ?? '—' }}</b></div>
  </div>

  <div class="meta box">
    <table style="width:100%; font-size:12px">
      <tr><td><b>Invoice No</b></td><td class="right">{{ $invoice->invoice_no }}</td></tr>
      <tr><td><b>Date</b></td><td class="right">{{ $invoice->invoice_date ?? now()->toDateString() }}</td></tr>
      <tr><td><b>PO No</b></td><td class="right">{{ $invoice->po_number ?? '—' }}</td></tr>
      <tr><td><b>Due Date</b></td><td class="right">{{ $invoice->due_date ?? '—' }}</td></tr>
      <tr><td><b>Month of Service</b></td><td class="right">{{ $invoice->billing_month }}</td></tr>
      <tr><td><b>Currency</b></td><td class="right">{{ $invoice->currency_name }} {{ $invoice->currency_symbol ? '(' . $invoice->currency_symbol . ')' : '' }}</td></tr>
      <tr><td><b>Status</b></td><td class="right"><span class="pill">{{ strtoupper(str_replace('_',' ', $invoice->status)) }}</span></td></tr>
    </table>
  </div>
</div>

<div class="grid mb-4">
  <div class="w-50">
    <div class="box">
      <div class="title mb-2">Bill To</div>
      <div><b>{{ $customer['name'] ?? '—' }}</b></div>
      <div class="pre muted">{{ $customer['address'] ?? '' }}</div>
    </div>
  </div>
  <div class="w-50">
    <div class="box">
      <div class="title mb-2">Remit To</div>
      <div><b>{{ $company['name'] ?? '—' }}</b></div>
      <div class="pre muted">{{ $company['address'] ?? '' }}</div>
    </div>
  </div>
</div>

@if(!empty($invoice->description))
  <div class="box mb-4">
    <div class="title mb-2">Description</div>
    <div class="pre">{{ $invoice->description }}</div>
  </div>
@endif

<div class="mb-3 title">Invoice Details ({{ $invoice->currency_name }}):</div>
<table class="tbl mb-4">
  <thead>
    <tr>
      <th style="width:60px">S.No</th>
      <th>Description</th>
      <th style="width:80px">Qty</th>
      <th style="width:110px" class="right">Rate</th>
      <th style="width:130px" class="right">Amount</th>
    </tr>
  </thead>
  <tbody>
  @foreach($lines as $l)
    <tr>
      <td>{{ $l->sno }}</td>
      <td class="pre">{{ $l->description }}</td>
      <td>{{ number_format((float)$l->qty,2) }}</td>
      <td class="right">{{ $sym }} {{ number_format((float)$l->rate,2) }}</td>
      <td class="right">{{ $sym }} {{ number_format((float)$l->value,2) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<table class="tbl totals">
  <tr><th>Gross Total</th><td class="right">{{ $sym }} {{ number_format((float)$invoice->gross_total,2) }}</td></tr>
  <tr><th>Discount</th><td class="right">{{ $sym }} {{ number_format((float)$invoice->discount_total,2) }}</td></tr>
  <tr><th>Tax</th><td class="right">{{ $sym }} {{ number_format((float)$invoice->tax_total,2) }}</td></tr>
  <tr><th>Net Total</th><td class="right"><b>{{ $sym }} {{ number_format((float)$invoice->net_total,2) }}</b></td></tr>
</table>

<div class="box" style="margin-top:16px;">
  <div class="title mb-2">Bank / Wire Details</div>
  <table style="width:100%">
    <tr><td style="width:160px"><b>Bank Name</b></td><td>{{ $bank->bank_name ?? '—' }}</td></tr>
    <tr><td><b>Account Name</b></td><td>{{ $bank->account_holder_name ?? '—' }}</td></tr>
    <tr><td><b>Account No.</b></td><td>{{ $bank->account_number ?? '—' }}</td></tr>
    <tr><td><b>SWIFT Code</b></td><td>{{ $bank->swift_code ?? '—' }}</td></tr>
    <tr><td><b>Routing / ABA</b></td><td>{{ $bank->bsr_code ?? '—' }}</td></tr>
    <tr><td><b>Branch</b></td><td>{{ $bank->branch_name ?? '—' }}</td></tr>
    <tr><td><b>Branch Address</b></td><td class="pre">{{ $bank->branch_address ?? '—' }}</td></tr>
  </table>
</div>

<div style="margin-top:18px;">
  <div><b>Authorized Signatory Name:</b></div>
  <div>{{ $company['signatory'] ?? '—' }}</div>
  <div class="muted" style="margin-top:12px;">Thank you for your business.</div>
</div>

</body>
</html>
