{{-- Mirrors your sample invoice format; map/replace fields as needed --}}
<style>
  .inv-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; }
  .muted { color:#6c757d; }
  .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
  .tbl { width:100%; border-collapse:collapse; }
  .tbl th, .tbl td { border:1px solid #e5e5e5; padding:8px; font-size:13px; vertical-align:top; }
  .tbl thead th { background:#f7f7f7; }
  .sum { max-width:360px; margin-left:auto; }
  .mt-2{margin-top:0.5rem} .mt-3{margin-top:1rem} .mt-4{margin-top:1.5rem}
  .fw-bold{font-weight:700} .text-end{text-align:right}
  .pre { white-space:pre-line }
</style>

<div class="inv-head">
  <div>
    <div class="fw-bold" style="font-size:20px">{{ $company['name'] }}</div>
    <div class="pre muted">{{ $company['address'] }}</div>
    <div class="mt-2">GSTIN: {{ $gstin ?: '—' }}</div>
    <div>PAN: {{ $pan ?: '—' }}</div>
  </div>

  <div>
    <table class="tbl">
      <tr><th>Invoice No:</th><td>{{ $invoiceNo }}</td></tr>
      <tr><th>Date:</th><td>{{ $invoiceDt }}</td></tr>
      <tr><th>PO No:</th><td>{{ $poNo ?: '—' }}</td></tr>
    </table>
  </div>
</div>

<div class="grid-2 mt-4">
  <div>
    <table class="tbl">
      <tr><th>Bill To:</th><td class="pre">{{ $billTo['name'] }}&#10;{{ $billTo['address'] }}</td></tr>
      <tr><th>Place of Supply:</th><td>{{ $placeOfSupply ?: '—' }}</td></tr>
      <tr><th>IEC Code:</th><td>{{ $iec ?: '—' }}</td></tr>
      <tr><th>Purpose Code:</th><td>{{ $purposeCode ?: '—' }}</td></tr>
    </table>
  </div>
  <div>
    <table class="tbl">
      <tr><th>LUT No & Description:</th><td>{{ $lutNo ?: '—' }}</td></tr>
      <tr><th>Project:</th><td>{{ $project->project_name ?? '—' }}</td></tr>
      <tr><th>Billing Month:</th><td>{{ $month }}</td></tr>
    </table>
  </div>
</div>

<div class="mt-4">
  <div class="fw-bold">Invoice Details (Currency: {{ $currency['name'] ?? '' }}):</div>
  <table class="tbl mt-2">
    <thead>
      <tr>
        <th style="width:60px">S.No</th>
        <th>Description</th>
        <th style="width:100px">SAC</th>
        <th style="width:80px">Qty</th>
        <th style="width:120px" class="text-end">Rate</th>
        <th style="width:140px" class="text-end">Taxable Value</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lines as $l)
      <tr>
        <td>{{ $l->sno }}</td>
        <td class="pre">{{ $l->description }}</td>
        <td>{{ $l->sac }}</td>
        <td>{{ $l->qty }}</td>
        <td class="text-end">{{ $currency['symbol'] ?? '' }} {{ number_format($l->rate, 2) }}</td>
        <td class="text-end">{{ $currency['symbol'] ?? '' }} {{ number_format($l->value, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="sum mt-4">
  <table class="tbl">
    <tr>
      <th>Gross Invoice Value</th>
      <td class="text-end">{{ $currency['symbol'] ?? '' }} {{ number_format($totals['gross'],2) }}</td>
    </tr>
    <tr>
      <th>Less: Discount</th>
      <td class="text-end">{{ $currency['symbol'] ?? '' }} {{ number_format($totals['discount'],2) }}</td>
    </tr>
    <tr>
      <th>Net Invoice Value</th>
      <td class="text-end fw-bold">{{ $currency['symbol'] ?? '' }} {{ number_format($totals['net'],2) }}</td>
    </tr>
  </table>
</div>

<div class="mt-3">
  <div class="fw-bold">Additional Notes:</div>
  <div class="muted">Any comments from your Finance team</div>
</div>

<div class="grid-2 mt-4">
  <div>
    <table class="tbl">
      <tr><th>Bank Name:</th><td>{{ $company['bank']['name'] }}</td></tr>
      <tr><th>Account No.:</th><td>{{ $company['bank']['account'] }}</td></tr>
      <tr><th>IFSC Code:</th><td>{{ $company['bank']['ifsc'] }}</td></tr>
      <tr><th>Branch:</th><td>{{ $company['bank']['branch'] }}</td></tr>
    </table>
  </div>
  <div>
    <table class="tbl">
      <tr><th>Due Date:</th><td>{{ $company['due'] }}</td></tr>
      <tr><th>Authorized Signatory Name:</th><td>{{ $company['signatory'] }}</td></tr>
      <tr><th>Sign:</th><td>&nbsp;</td></tr>
    </table>
  </div>
</div>

<div class="mt-4 muted">“Thank you for doing business with us. We look forward to serving you again.”</div>
