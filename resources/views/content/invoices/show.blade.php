@extends('layouts/layoutMaster')

@section('title', 'Invoice #' . ($invoice->invoice_no ?? $invoice->id))

@section('page-style')
<style>
  .inv-wrap { max-width: 1100px; margin: 0 auto; }
  .muted { color:#6c757d; }
  .pre { white-space: pre-line; }
  .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
  .tbl { width:100%; border-collapse: collapse; }
  .tbl th, .tbl td { border:1px solid #e5e5e5; padding:8px; font-size:13px; vertical-align:top; }
  .tbl thead th { background:#f7f7f7; }
  .sum { max-width:420px; margin-left:auto; }
  .head-flex { display:flex; justify-content:space-between; align-items:flex-start; gap:24px; }
  .badge { padding: .25rem .5rem; border-radius:.35rem; font-size:.75rem; }
  .badge-submitted { background:#e7f1ff; color:#0a58ca; }
  .badge-draft { background:#f8f9fa; color:#6c757d; }
  .badge-finance_approved { background:#e9fbe7; color:#207a16; }
  .badge-sent { background:#fff3cd; color:#856404; }
  .badge-paid { background:#e6ffed; color:#1f7a1f; }
  .badge-rejected { background:#fde2e1; color:#b02a37; }
  .pill { display:inline-flex; align-items:center; gap:6px; }
  .kv { display:flex; gap:6px; }
  .kv .k { min-width:130px; color:#6c757d; }
  .h4 { font-weight:700; font-size:1.15rem; margin:0 0 .5rem; }
  .h5 { font-weight:600; font-size:1rem; margin:0 0 .5rem; }
</style>
@endsection

@section('content')
@include('content.partials.flash')
<div class="card inv-wrap">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <div class="h4">Invoice #{{ $invoice->invoice_no }}</div>
      <div class="muted">Project: {{ $invoice->project?->project_name ?? '-' }}</div>
    </div>
    <div class="pill">
      <span class="muted">Status:</span>
      @php
        $status = $invoice->status;
        $badgeClass = match($status) {
          'draft' => 'badge-draft',
          'submitted' => 'badge-submitted',
          'finance_approved' => 'badge-finance_approved',
          'sent' => 'badge-sent',
          'paid' => 'badge-paid',
          'rejected' => 'badge-rejected',
          default => 'badge-draft'
        };
      @endphp
      <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ', $status)) }}</span>
    </div>

    <a href="{{ route('invoices.list') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body">

    {{-- Header: Company snapshot & Invoice meta --}}
    <div class="head-flex">
      <div>
        <div class="h4">{{ $invoice->company_name ?? 'Company' }}</div>
        <div class="pre muted">{{ $invoice->company_address ?? '' }}</div>
        <div class="mt-2 small">
          <div>PAN: <strong>{{ $invoice->company_pan ?? '—' }}</strong></div>
          <div>GSTIN: <strong>{{ $invoice->company_gstin ?? '—' }}</strong></div>
          <div>LUT No: <strong>{{ $invoice->company_lut_no ?? '—' }}</strong></div>
          <div>IEC: <strong>{{ $invoice->company_iec ?? '—' }}</strong></div>
          <div>Reference No: <strong>{{ $invoice->company_reference_no ?? '—' }}</strong></div>
        </div>
      </div>
      <div style="min-width:340px;">
        <table class="tbl">
          <tbody>
            <tr><th>Invoice No</th><td>{{ $invoice->invoice_no }}</td></tr>
            <tr><th>Invoice Date</th><td>{{ Helper::ymd_to_mdy($invoice->invoice_date) ?? '—' }}</td></tr>
            <tr><th>Due Date</th><td>{{ Helper::ymd_to_mdy($invoice->due_date) ?? '—' }}</td></tr>
            <tr><th>PO Number</th><td>{{ $invoice->po_number ?? '—' }}</td></tr>
            <tr><th>Billing Month</th><td>{{ $invoice->billing_month }}</td></tr>
            <tr>
              <th>Currency</th>
              <td>{{ $invoice->currency_name ?? '—' }} {!! $invoice->currency_symbol ? "({$invoice->currency_symbol})" : '' !!}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Bill To / Customer --}}
    <div class="grid-2 mt-3">
      <div>
        <div class="h5">Bill To</div>
        <div class="kv"><div class="k">Name</div><div class="v"><strong>{{ $invoice->customer_name ?? '—' }}</strong></div></div>
        <div class="kv"><div class="k">Address</div><div class="v pre">{{ $invoice->customer_address ?? '—' }}</div></div>
    
      </div>
      <div>
        <div class="h5">Summary</div>
        <div class="kv"><div class="k">Project</div><div class="v">{{ $invoice->project?->project_name ?? '-' }}</div></div>
        <div class="kv"><div class="k">Created By</div><div class="v">{{ $invoice->creator?->first_name ?? $invoice->created_by }}</div></div>
        <div class="kv"><div class="k">Assigned To</div><div class="v">{{ $invoice->assignee?->first_name ?? '—' }}</div></div>
      </div>
    </div>

    {{-- Description (optional) --}}
    @if(!empty($invoice->description))
      <div class="mt-3">
        <div class="h5">Description</div>
        <div class="pre">{{ $invoice->description }}</div>
      </div>
    @endif

    {{-- Lines --}}
    <div class="mt-4">
      <div class="h5">Invoice Lines</div>
      <div class="table-responsive">
        <table class="tbl">
          <thead>
            <tr>
              <th style="width:70px">S.No</th>
              <th>Description</th>
              <th style="width:120px">SAC</th>
              <th style="width:100px">Qty</th>
              <th style="width:140px" class="text-end">Rate</th>
              <th style="width:160px" class="text-end">Value</th>
            </tr>
          </thead>
          <tbody>
            @php $sym = $invoice->currency_symbol ?: ''; @endphp
            @forelse($lines as $line)
              <tr>
                <td>{{ $line->sno }}</td>
                <td class="pre">{{ $line->description }}</td>
                <td>{{ $line->sac ?? '' }}</td>
                <td>{{ number_format((float)$line->qty, 2) }}</td>
                <td class="text-end">{{ $sym }} {{ number_format((float)$line->rate, 2) }}</td>
                <td class="text-end">{{ $sym }} {{ number_format((float)$line->value, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="muted text-center">No lines.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Totals --}}
    <div class="sum mt-4">
      <table class="tbl">
        <tbody>
          <tr>
            <th>Gross Total</th>
            <td class="text-end">{{ $sym }} {{ number_format((float)$invoice->gross_total, 2) }}</td>
          </tr>
          <tr>
            <th>Discount</th>
            <td class="text-end">{{ $sym }} {{ number_format((float)$invoice->discount_total, 2) }}</td>
          </tr>
          <tr>
            <th>Tax</th>
            <td class="text-end">{{ $sym }} {{ number_format((float)$invoice->tax_total, 2) }}</td>
          </tr>
          <tr>
            <th>Net Total</th>
            <td class="text-end"><strong>{{ $sym }} {{ number_format((float)$invoice->net_total, 2) }}</strong></td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- Finance Action Panel --}}
    @can('finance approve invoice')
      @php
        // Allow actions typically only in 'submitted' state. Tweak to your flow.
        $canAct = in_array($invoice->status, ['submitted']);
      @endphp

      <div class="mt-4 border rounded p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="h5 mb-0">Finance Action</div>
          @if(!$canAct)
            <span class="muted small">This invoice is not in an actionable state.</span>
          @endif
        </div>

        <form id="financeForm" onsubmit="return false;">
  <div class="row g-3">
    {{-- Bank --}}
    <div class="col-md-6">
      <label class="form-label">
        Select Bank @if($canAct)<span class="text-danger">*</span>@endif
      </label>
      <select id="bank_id" name="bank_id" class="form-select" {{ $canAct ? '' : 'disabled' }}>
        <option value="">-- Select Bank --</option>
        @foreach(($banks ?? []) as $b)
          <option value="{{ $b->id }}" {{ (int)$invoice->bank_id === (int)$b->id ? 'selected':'' }}>
            {{ $b->entity ?? $b->bank_name ?? ('Bank #'.$b->id) }}
          </option>
        @endforeach
      </select>
      <div class="form-text">Bank is required for Approve; optional for Reject.</div>
    </div>

    {{-- PO Number --}}
    <div class="col-md-6">
      <label class="form-label">PO Number</label>
      <input
        type="text"
        id="po_number"
        name="po_number"
        class="form-control"
        value="{{ old('po_number', $invoice->po_number ?: ($poNo ?? '')) }}"
        {{ $canAct ? '' : 'readonly' }}
        placeholder="Enter PO number (optional)"
      >
      <!-- <div class="form-text">Prefilled if available; you can update or add a new PO number.</div> -->
    </div>

    {{-- SAC Number --}}
    <div class="col-md-6">
      <label class="form-label">SAC Number @if($canAct)<span class="text-danger">*</span>@endif</label>
      <select id="sac_number" name="sac_number" class="form-select" {{ $canAct ? '' : 'disabled' }}>
        <option value="">-- Select SAC --</option>
        <option value="998311" {{ ($invoice->sac_number ?? '') == '998311' ? 'selected' : '' }}>998311</option>
        <option value="998313" {{ ($invoice->sac_number ?? '') == '998313' ? 'selected' : '' }}>998313</option>
      </select>
      <div class="form-text">Required when approving the invoice.</div>
    </div>

    {{-- (Optional) Payment completed toggle --}}
    <div class="col-md-6 d-flex align-items-end">
      {{-- keep disabled if you don’t want to expose --}}
      {{-- <div class="form-check">
        <input class="form-check-input" type="checkbox" id="payment_completed" name="payment_completed"
               {{ $canAct ? '' : 'disabled' }} {{ $invoice->payment_completed ? 'checked' : '' }}>
        <label for="payment_completed" class="form-check-label">Mark payment completed</label>
      </div> --}}
    </div>

    {{-- Finance Notes --}}
    <div class="col-12">
      <label class="form-label">Finance Notes (optional)</label>
      <textarea id="finance_notes" name="finance_notes" class="form-control" rows="2"
                placeholder="Add a note for audit trail..." {{ $canAct ? '' : 'disabled' }}>{{ old('finance_notes', $invoice->finance_notes ?? '') }}</textarea>
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <button type="button" id="btnReject" class="btn btn-outline-danger" {{ $canAct ? '' : 'disabled' }}>
      <i class="ti ti-x me-1"></i> Reject
    </button>
    <button type="button" id="btnApprove" class="btn btn-primary" {{ $canAct ? '' : 'disabled' }}>
      <i class="ti ti-check me-1"></i> Approve
    </button>
  </div>
</form>


<!-- <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-info">
  <i class="ti ti-file-text me-1"></i> Generate PDF
</a> 
@if($invoice->pdf_path)
  <a href="{{ Storage::url($invoice->pdf_path) }}" target="_blank" rel="noopener">View stored PDF</a>
@endif -->
      </div>
    @endcan

    {{-- Signatory / Footer --}}
    <div class="mt-4">
      <div class="h5">Authorized Signatory</div>
      <div>{{ $invoice->company_signatory ?? '—' }}</div>
    </div>

  </div>
</div>
@php
  /** @var \App\Models\Invoice $invoice */
  $isApproved = ($invoice->status === 'finance_approved' || $invoice->status==='sent');
  $hasPdf     = filled($invoice->pdf_path ?? null);
@endphp

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2">
      @if($isApproved)
        <span class="badge bg-success d-inline-flex align-items-center">
          <i class="ti ti-badge-check me-1"></i> Finance Approved
        </span>
        @if(!empty($invoice->pdf_generated_at))
          <small class="text-muted">
            PDF generated {{ \Carbon\Carbon::parse($invoice->pdf_generated_at)->format('d M Y, H:i') }}
          </small>
        @endif
      @else
        <span class="badge bg-secondary d-inline-flex align-items-center">
          <i class="ti ti-clock me-1"></i> Awaiting Finance Approval
        </span>
      @endif
    </div>
    @if($invoice->status === 'sent')
        <div class="form-check">
          <input class="form-check-input" type="checkbox"
                 id="chkPaymentCollected"
                 {{ $invoice->payment_completed ? 'checked disabled' : '' }}>
          <label class="form-check-label" for="chkPaymentCollected">
            Payment collected
          </label>
        </div>
      @endif
    <div class="btn-group">
      @php
        use Illuminate\Support\Facades\Crypt;
        $encId = Crypt::encryptString($invoice->id);
      @endphp
      <a href="{{ route('invoices.preview', $encId) }}" target="_blank" rel="noopener"
           class="btn btn-primary">
          <i class="ti ti-file-plus me-1"></i> View PDF
        </a>
      @if($isApproved && $hasPdf)
       
        <!-- <a href="{{ Storage::url($invoice->pdf_path) }}" target="_blank" rel="noopener"
           class="btn btn-info">
          <i class="ti ti-file-text me-1"></i> View PDF
        </a> -->
        <a href="{{ Storage::url($invoice->pdf_path) }}" download class="btn btn-outline-secondary">
          <i class="ti ti-download me-1"></i> Download
        </a>
        {{-- If you prefer to stream via controller (access-controlled), use this instead of Storage::url: --}}
        {{-- <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" rel="noopener" class="btn btn-info">
             <i class="ti ti-file-text me-1"></i> View PDF
           </a> --}}
      @elseif($isApproved && !$hasPdf)
        {{-- Approved but file not saved yet: generate/stream once --}}
        <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" rel="noopener"
           class="btn btn-primary">
          <i class="ti ti-file-plus me-1"></i> Generate PDF
        </a>
      @else
        <!-- <button type="button" class="btn btn-info" disabled
                data-bs-toggle="tooltip" title="PDF available after finance approval">
          <i class="ti ti-lock me-1"></i> View PDF
        </button> -->
      @endif
      
        
    </div>
  </div>
</div>
@php
  $isApproved = $invoice->status === 'finance_approved';
  $niceInvNo  = $invoice->invoice_no ?: ('INV-'.$invoice->id);
 
  $subjectInv = preg_replace('/-?0+(\d+)$/', '/$1', str_replace('-', '/', $niceInvNo));
  $datedStr   = $invoice->invoice_date
                ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y')
                : now()->format('d F Y');
  $defaultSubject = "Invoice {$subjectInv} — Dated {$datedStr}";

  $custName = trim($invoice->customer_name ?: 'Customer Team');
  $defaultBody = <<<TXT
Dear {$custName},

Greetings!
We appreciate your continued partnership. Please find attached Invoice No. {$subjectInv}, dated {$datedStr}.
If you have any questions or need clarifications, feel free to reach out. We’ll be happy to assist.

Thank you for your business.
TXT;
@endphp

<div class="btn-group">
  {{-- your existing PDF buttons here --}}

  @if($isApproved)
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#emailInvoiceModal">
      <i class="ti ti-mail me-1"></i> Send to Client
    </button>
  @else
    <button type="button" class="btn btn-warning" disabled data-bs-toggle="tooltip"
            title="Available after Finance Approval">
      <i class="ti ti-lock me-1"></i> Send to Client
    </button>
  @endif
</div>

{{-- Email Modal --}}
<div class="modal fade" id="emailInvoiceModal" tabindex="-1" aria-labelledby="emailInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="emailInvoiceForm" method="POST" action="{{ route('invoices.email', $invoice->id) }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="emailInvoiceModalLabel">Send Invoice to Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">To (comma-separated)</label>
            <input type="text" name="to" class="form-control" value={{$customer_emails}}  placeholder="client@example.com, billing@example.com" required>
          </div>
          <div class="mb-3">
            <label class="form-label">CC (optional, comma-separated)</label>
            <input type="text" name="cc" class="form-control" placeholder="finance@example.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" value="{{ $defaultSubject }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="8" required>{{ $defaultBody }}</textarea>
          </div>

          <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="ti ti-paperclip me-2"></i>
            The latest PDF will be attached automatically.
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-send me-1"></i> Send Email
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- (optional) enable tooltips --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tts = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tts.map(el => new bootstrap.Tooltip(el));
  });
</script>

@endsection

@section('extra-script')
<script>
(function(){
  const csrf = '{{ csrf_token() }}';
  const decisionUrl = "{{ route('invoices.financeDecision', $invoice->id) }}";

  function postDecision(payload){
    return fetch(decisionUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify(payload)
    }).then(async res => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : null) || ('HTTP ' + res.status);
        throw new Error(msg);
      }
      return data;
    });
  }

  const approveBtn = document.getElementById('btnApprove');
  const rejectBtn  = document.getElementById('btnReject');
  const bankSel    = document.getElementById('bank_id');
  const noteEl     = document.getElementById('finance_notes'); // << plural
  const sacSel     = document.getElementById('sac_number');
  const payChk     = document.getElementById('payment_completed');
  const poNumber   = document.getElementById('po_number');

  function getCommonPayload() {
    return {
      bank_id: (bankSel && bankSel.value) ? Number(bankSel.value) : null,
      sac_number: (sacSel && sacSel.value) ? sacSel.value : null,
      poNumber : poNumber ? poNumber.value : null,
      finance_notes: (noteEl && noteEl.value) ? noteEl.value.trim() : null,
      // Send legacy key too for backward-compat (controller will prefer finance_notes)
      note: (noteEl && noteEl.value) ? noteEl.value.trim() : null,
      payment_completed: (payChk && payChk.checked) ? 1 : 0
    };
  }

  if (approveBtn) {
    approveBtn.addEventListener('click', function(){
      const bankId = bankSel ? bankSel.value : '';
      if (!bankId) { alert('Please select a bank before approving.'); return; }

      // SAC required on approve
      const sac = sacSel ? sacSel.value : '';
      if (!sac) { alert('Please select a SAC number before approving.'); return; }

      const payload = { action: 'approve', ...getCommonPayload() };

      approveBtn.disabled = true; rejectBtn && (rejectBtn.disabled = true);
      postDecision(payload)
        .then(data => {
          alert(data.message || 'Invoice approved.');
          if (data.redirect) window.location = data.redirect; else window.location.reload();
        })
        .catch(err => { console.error(err); alert(err.message || 'Approval failed.'); })
        .finally(() => { approveBtn.disabled = false; rejectBtn && (rejectBtn.disabled = false); });
    });
  }

  if (rejectBtn) {
    rejectBtn.addEventListener('click', function(){
      // Save notes even on reject (bank/sac optional)
      const payload = { action: 'reject', ...getCommonPayload(), payment_completed: 0 };

      approveBtn && (approveBtn.disabled = true); rejectBtn.disabled = true;
      postDecision(payload)
        .then(data => {
          alert(data.message || 'Invoice rejected.');
          if (data.redirect) window.location = data.redirect; else window.location.reload();
        })
        .catch(err => { console.error(err); alert(err.message || 'Rejection failed.'); })
        .finally(() => { approveBtn && (approveBtn.disabled = false); rejectBtn.disabled = false; });
    });
  }
})();

(function() {
  const chk = document.getElementById('chkPaymentCollected');
  if (!chk) return;

  const url = @json(route('invoices.markPaid', $invoice->id));
  const token = @json(csrf_token());

  chk.addEventListener('change', function() {
    // only act when turning ON
    if (!this.checked) { this.checked = false; return; }

    if (!confirm('Mark this invoice as payment collected?')) {
      this.checked = false;
      return;
    }

    this.disabled = true;

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ payment_completed: 1 })
    })
    .then(async res => {
      const json = await res.json().catch(() => ({}));
      if (!res.ok || json.status !== 1) {
        throw new Error(json.message || 'Failed to update payment status.');
      }

      // Replace status badge
      const wrap = document.getElementById('invoiceStatusWrap');
      if (wrap) {
        wrap.innerHTML = `
          <span class="badge bg-success d-inline-flex align-items-center">
            <i class="ti ti-cash me-1"></i> Payment Collected
          </span>
        `;
      }

      // Inline success flash
      showFlash('success', json.message || 'Payment marked as collected.');
    })
    .catch(err => {
      this.checked = false;
      this.disabled = false;
      showFlash('danger', err.message || 'Update failed.');
    });
  });

  function showFlash(type, msg) {
    const host = document.getElementById('flashHost') || document.body;
    const el = document.createElement('div');
    el.className = `alert alert-${type} alert-dismissible fade show`;
    el.role = 'alert';
    el.innerHTML = `${escapeHtml(msg)}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    host.prepend(el);
    setTimeout(() => el.querySelector('.btn-close')?.click(), 4000);
  }

  function escapeHtml(s) {
    return (s ?? '').toString().replace(/[&<>"']/g, m => ({
      '&': '&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));
  }
})();
</script>

@endsection
