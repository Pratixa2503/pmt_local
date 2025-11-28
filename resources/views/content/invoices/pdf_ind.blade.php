<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice {{ $invoice->invoice_no }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { border-bottom:1px solid #000; padding:10px 0; }
    .company { font-size:10px; line-height:1.4; }
    .title { font-size:42px; color:#E35205; text-align:right; }
    .section { margin:15px 0; }
    .label { font-weight:bold; }
    table { width:100%; border-collapse:collapse; font-size:12px; }
    th,td { border:1px solid #000; padding:6px; }
    th { background:#E35205; color:#fff; }
    .totals td { border:none; }
    .totals .label { font-weight:bold; color:#E35205; }
    .footer { margin-top:30px; font-size:11px; }
  </style>
</head>
<body>

  {{-- Header --}}
  <table width="100%" class="header">
    <tr>
      <td width="50%">
        <img src="{{ public_path('assets/img/logo.svg') }}" height="50"><br>
        <div class="company">
          {{ $invoice->company_name }}<br>
          {!! nl2br(e($invoice->company_address)) !!}<br>
          PAN: {{ $invoice->company_pan ?? '—' }}<br>
          GSTIN: {{ $invoice->company_gstin ?? '—' }}
        </div>
      </td>
      <td width="50%" class="title">INVOICE</td>
    </tr>
  </table>

  {{-- Meta --}}
  <table width="100%" style="margin-top:10px; border-bottom:1px solid #000;">
    <tr>
      <td width="50%">
        <div><span class="label">Month of Service:</span> {{ $invoice->billing_month }}</div>
        <div><span class="label">Bill To:</span> {{ $invoice->customer_name }}</div>
        <div><span class="label">Address:</span> {!! nl2br(e($invoice->customer_address)) !!}</div>
      </td>
      <td width="50%">
        <div><span class="label">Invoice No:</span> {{ $invoice->invoice_no }}</div>
        <div><span class="label">Date:</span> {{ $invoice->invoice_date }}</div>
        <div><span class="label">Due Date:</span> {{ $invoice->due_date }}</div>
        <div><span class="label">PO No:</span> {{ $invoice->po_number ?? '—' }}</div>
        <div><span class="label">Place of Supply:</span> {{ $invoice->place_of_supply ?? '—' }}</div>
      </td>
    </tr>
  </table>

  {{-- Line Items --}}
  <div class="section">
    <div class="label">Invoice Details ({{ $invoice->currency_name }}):</div>
    <table>
      <thead>
        <tr>
          <th>S.No</th>
          <th>Description</th>
          <th>SAC</th>
          <th>Qty</th>
          <th>Rate</th>
          <th>Taxable Value</th>
        </tr>
      </thead>
      <tbody>
        @foreach($lines as $line)
        <tr>
          <td>{{ $line->sno }}</td>
          <td>{{ $line->description }}</td>
          <td>{{ $line->sac ?? '' }}</td>
          <td>{{ number_format($line->qty,2) }}</td>
          <td>{{ $invoice->currency_symbol }} {{ number_format($line->rate,2) }}</td>
          <td>{{ $invoice->currency_symbol }} {{ number_format($line->value,2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Totals --}}
  <div class="section">
    <table class="totals" style="width:50%; float:right;">
      <tr><td>Net Invoice Value</td><td style="text-align:right;">{{ $invoice->currency_symbol }} {{ number_format($invoice->subtotal,2) }}</td></tr>
      <tr><td>Discount</td><td style="text-align:right;">{{ $invoice->currency_symbol }} {{ number_format($invoice->discount_total,2) }}</td></tr>
      @if($invoice->customer_type === 'IND')
        <tr><td>GST ({{ config('companyinfo.gst_percent',18) }}%)</td><td style="text-align:right;">{{ $invoice->currency_symbol }} {{ number_format($invoice->tax_total,2) }}</td></tr>
      @endif
      <tr><td class="label">Net Invoice Value</td><td style="text-align:right; font-weight:bold; color:#E35205;">{{ $invoice->currency_symbol }} {{ number_format($invoice->net_total,2) }}</td></tr>
    </table>
  </div>

  {{-- Bank Details --}}
  <div class="section">
    <div class="label">Bank Details:</div>
    <div>Account Name: {{ $bank->account_name ?? '' }}</div>
    <div>Bank: {{ $bank->bank_name ?? '' }}</div>
    <div>Branch: {{ $bank->branch ?? '' }}</div>
    <div>Account No: {{ $bank->account_no ?? '' }}</div>
    <div>IFSC: {{ $bank->ifsc_code ?? '' }}</div>
    <div>SWIFT: {{ $bank->swift_code ?? '' }}</div>
    <div>Routing No: {{ $bank->routing_no ?? '' }}</div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    Authorized Signatory: {{ $invoice->company_signatory }}
  </div>
</body>
</html>
