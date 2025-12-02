@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<style>
  .pricing-disabled-row {
    opacity: 0.6;
    background-color: #f8f9fa;
  }
  .pricing-disabled-row td {
    cursor: not-allowed;
  }
  input.row-check:disabled {
    cursor: not-allowed;
    opacity: 0.5;
  }
</style>
@endsection

@section('vendor-script')
{{-- jQuery MUST be before select2/datepicker --}}
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
  <h4 class="mb-0">Generate Invoice</h4>
  <a href="{{ route('invoices.general') }}" class="btn btn-primary">
    <i class="ti ti-adjustments-horizontal me-1"></i> Submitted Invoice
  </a>
</div>

  <div class="card-body">
    <form id="invoicePicker" class="mb-3" novalidate>
      @csrf
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label mb-1">Project</label>
          <select name="project_id" id="projectSelect" class="form-select select2" required>
            <option value="">Select</option>
            @foreach($projects as $p)
            <option value="{{ $p->id }}">{{ $p->project_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">Billing Month</label>
          <input type="text" name="month" id="billingMonth" class="form-control" placeholder="MM-YYYY" required>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100" id="btnLoad">Load</button>
        </div>
      </div>
    </form>

    <div id="ajaxStatus" class="d-none">
      <div class="alert alert-info mb-0">Loading…</div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-sm" id="resultsTable">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="selectAll"></th>
            <th>ID</th>
            <th>Property</th>
            <th>Tenant</th>
            <th>Delivered Date</th>
            <th>Billing Month</th>
            <th>Status</th>
            <th>Notes</th> <!-- 👈 NEW -->
            <th class="text-end">Rate</th>
            <th class="text-end">Value</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
      <div class="text-muted"><span id="selectedCount">0</span> selected</div>
      <div class="d-flex gap-2">
        <button type="button" id="btnClear" class="btn btn-outline-secondary btn-sm">Clear</button>
        <button type="button" id="btnPreview" class="btn btn-success btn-sm" disabled>Preview</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal (uses Bootstrap JS) --}}
<div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="invoicePreviewBody"><!-- AJAX HTML --></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btnReject">Cancel</button>
        <button type="button" class="btn btn-primary" id="btnApprove">Approve</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-script')
<script>
  // Make sure all jQuery AJAX calls carry the CSRF token (prevents 419)
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });

  function nn(v, d) {
    return (v === null || v === undefined || v === '') ? d : v;
  }

  $(function() {
    // init plugins
    try {
      $('#projectSelect').select2({
        width: '100%'
      });
    } catch (e) {}
    try {
      var $m = $('#billingMonth');
      $m.datepicker({
        format: 'mm-yyyy',       // MM-YYYY
        autoclose: true,
        minViewMode: 1,
        endDate: new Date(),
        clearBtn: true
      });
      if (!$m.val()) {
        var d = new Date();
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        // DEFAULT AS MM-YYYY (fixed)
        $m.val(mm + '-' + d.getFullYear());
      }
    } catch (e) {}

    // submit handler
    $('#invoicePicker').on('submit', function(e) {
      e.preventDefault();

      const pid = $('#projectSelect').val();
      const month = $('#billingMonth').val(); // already MM-YYYY
      if (!pid || !month) {
        alert('Select project and month');
        return;
      }

      $('#ajaxStatus').removeClass('d-none');

      $.post('{{route('invoices.previewData')}}', {
            project_id: pid,
            month: month
          })
        .done(function(res) {
          const tbody = $('#resultsTable tbody').empty();
          const tfoot = $('#resultsTable tfoot').length ? $('#resultsTable tfoot').empty() :
            $('<tfoot/>').appendTo('#resultsTable');

          const sym = (res.currency && res.currency.symbol) ? res.currency.symbol + ' ' : '';
          
          // Store category for later use in preview
          const category = res.category || 2; // default to 2 if not set
          $('#resultsTable').data('category', category);

          if (!res.data || !res.data.length) {
           tbody.append('<tr><td colspan="10" class="text-center text-muted">No records found</td></tr>');
            tfoot.empty();
            return;
          }
          const safe = v => $('<div/>').text(v ?? '').html();
         res.data.forEach(function(r) {
            function titleCaseSnake(s){
              return String(s || '')
                .replace(/_/g, ' ')
                .replace(/\b\w/g, c => c.toUpperCase())
                .trim();
            }

            const STATUS_CLASS_MAP = {
              draft: 'secondary',
              submitted: 'info',
              finance_approved: 'success',
              sent: 'primary',
              rejected: 'danger',
              pending_invoice: 'secondary'
            };

            const rawStatus = r.invoice_status || r.status || 'pending_invoice';
            const badgeCls  = r.invoice_status_badge || STATUS_CLASS_MAP[rawStatus] || 'secondary';
            const label     = r.invoice_status_label || titleCaseSnake(rawStatus);

            const isLocked  = !!r.locked;
            const invNoText = r.locked_invoice_no ? safe(r.locked_invoice_no) : '';
            const statusBadge = isLocked
              ? `<div class="mt-1">
                  <span class="badge bg-secondary">
                    <i class="ti ti-lock me-1"></i> Invoiced ${invNoText}
                  </span>
                </div>`
              : '';

            const statusBadge1 = '<span class="badge bg-' + safe(badgeCls) + '">' +
                                  safe(label) +
                                '</span>';

            const notesText = (r.notes && r.notes.trim().length)
              ? safe(r.notes)
              : '<span class="text-muted">-</span>'; // fallback

            // Check if pricing is not approved
            // Disable checkbox if pricing approval is pending or not found
            const notesLower = (r.notes || '').toLowerCase().trim();
            const hasPricingPending = 
              notesLower.includes('pricing approval is pending') || 
              notesLower.includes('pricing approval pending') ||
              (notesLower.includes('pricing approval') && notesLower.includes('pending')) ||
              (notesLower.includes('pricing') && notesLower.includes('approval') && notesLower.includes('pending')) ||
              notesLower.includes('no approved pricing found');
            // Check if rate is null/undefined (but allow 0 as it might be valid)
            const hasNoRate = r.rate === null || r.rate === undefined;
            const isPricingDisabled = hasPricingPending || hasNoRate;
            
            // Debug: log if pricing is disabled (remove in production)
            if (isPricingDisabled) {
              console.log('Pricing disabled for row:', r.id, 'Notes:', r.notes, 'Rate:', r.rate, 'hasPricingPending:', hasPricingPending, 'hasNoRate:', hasNoRate);
            }

            // Build checkbox with proper disabled attribute
            const shouldDisable = isLocked || isPricingDisabled;
            const checkboxDisabled = shouldDisable ? ' disabled' : '';
            const checkboxClass = isPricingDisabled ? 'row-check pricing-disabled' : 'row-check';

            tbody.append(
              '<tr class="'+ (isLocked ? 'row-locked' : '') + (isPricingDisabled ? ' pricing-disabled-row' : '') +'">' +
                '<td><input type="checkbox" class="' + checkboxClass + '" name="intake_ids[]" value="' + r.id + '"' + checkboxDisabled + '></td>' +
                '<td>' + nn(r.id, '-') + '</td>' +
                '<td><div class="fw-medium">' + safe(nn(r.property_name, '-')) + '</div>' +
                  '<div class="text-muted small">ID: ' + safe(nn(r.property_id, '-')) + '</div>' +
                  statusBadge +
                '</td>' +
                '<td>' + safe(nn(r.tenant_name, '-')) + '</td>' +
                '<td>' + safe(nn(r.delivered_date, '-')) + '</td>' +
                '<td>' + safe(nn(r.billing_month, '-')) + '</td>' +
                '<td>' + statusBadge1 + '</td>' +
                '<td>' + notesText + '</td>' +                 // 👈 NEW notes cell
                '<td class="text-end">' + sym + (parseFloat(r.rate || 0).toFixed(2)) + '</td>' +
                '<td class="text-end">' + sym + (parseFloat(r.value || 0).toFixed(2)) + '</td>' +
              '</tr>'
            );
          });


          // Gross total footer (if present)
          const gross = (res.totals && typeof res.totals.gross !== 'undefined') ?
            parseFloat(res.totals.gross || 0) :
            res.data.reduce((acc, r) => acc + (parseFloat(r.value || 0) || 0), 0);

          tfoot.append(
            '<tr>' +
              '<th colspan="9" class="text-end">Gross Total</th>' + // was 8, now 9
              '<th class="text-end">' + sym + gross.toFixed(2) + '</th>' +
            '</tr>'
          );

          rebindSelection(); // keep your existing binding logic
        })
        .fail(function(xhr) {
          console.error(xhr.responseText || xhr.statusText);
          alert('Failed to load data');
        })
        .always(function() {
          $('#ajaxStatus').addClass('d-none');
        });
    });

    function rebindSelection() {
      const $table      = $('#resultsTable');
      const $selectAll  = $('#selectAll');
      const $allChecks  = $table.find('.row-check');          // includes disabled
      const $enabled    = $allChecks.filter(':enabled');      // only actionable
      const $count      = $('#selectedCount');
      const $btnPreview = $('#btnPreview');
      const category    = $table.data('category') || 2;

      function update() {
        const sel  = $enabled.filter(':checked').length;
        const tot  = $enabled.length;

        $count.text(sel);
        
        // For category 3, enable preview button without selection
        // For other categories, require at least one selection
        if (category === 3) {
          $btnPreview.prop('disabled', false);
        } else {
          $btnPreview.prop('disabled', sel === 0);
        }

        // Select-all only reflects enabled checkboxes
        $selectAll.prop('disabled', tot === 0);
        $selectAll.prop('checked',  sel > 0 && sel === tot);
        $selectAll.prop('indeterminate', sel > 0 && sel < tot);
      }

      // Select all only toggles enabled checkboxes
      $selectAll.off('change').on('change', function() {
        const state = $(this).is(':checked');
        $enabled.prop('checked', state);
        update();
      });

      // Row checkbox listener (enabled only)
      $table.off('change', '.row-check').on('change', '.row-check:enabled', update);

      // Prevent clicks on disabled checkboxes
      $table.off('click', '.row-check:disabled').on('click', '.row-check:disabled', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      });

      // Clear only affects enabled
      $('#btnClear').off('click').on('click', function() {
        $enabled.prop('checked', false);
        update();
      });

      update();
    }

    // initial
    rebindSelection();
  });

  $('#btnPreview').on('click', function() {
    const category = $('#resultsTable').data('category') || 2;
    const pid = $('#projectSelect').val();
    const month = $('#billingMonth').val(); // MM-YYYY

    // For category 3, no need to select rows - use API data directly
    if (category === 3) {
      $.post('{{route('invoices.previewHtmlCategory3')}}', {
            project_id: pid,
            month: month
          })
        .done(function(res) {
          $('#invoicePreviewBody').html(res.html || '<div class="alert alert-warning">No preview.</div>');
          const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
          modal.show();
        })
        .fail(function(xhr) {
          console.error(xhr.responseText || xhr.statusText);
          alert('Failed to load preview');
        });
      return;
    }

    // For other categories (1, 2), require row selection
    const ids = $('#resultsTable .row-check:checked').map(function() {
      return this.value;
    }).get();
    if (ids.length === 0) {
      alert('Select rows first');
      return;
    }

    $.post('{{route('invoices.previewHtml')}}', {
          project_id: pid,
          month: month,
          'intake_ids[]': ids
        })
      .done(function(res) {
        $('#invoicePreviewBody').html(res.html || '<div class="alert alert-warning">No preview.</div>');
        const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
        modal.show();
      })
      .fail(function(xhr) {
        console.error(xhr.responseText || xhr.statusText);
        alert('Failed to load preview');
      });
  });

  // Helper to read currently selected row ids
  function getSelectedIntakeIds() {
    return $('#resultsTable .row-check:enabled:checked').map(function () {
      return this.value;
    }).get();
  }

  // Approve -> create invoice + lines in one go
  $('#btnApprove').on('click', function () {
    const $btn = $(this);
    const pid   = $('#projectSelect').val();
    const month = $('#billingMonth').val(); // MM-YYYY
    const category = $('#resultsTable').data('category') || 2;
    const ids   = getSelectedIntakeIds();

    if (!pid || !month) {
      alert('Please select Project and Billing Month.');
      return;
    }
    
    // For category 3, no intake selection needed
    // For other categories, require at least one selection
    if (category !== 3 && !ids.length) {
      alert('Please select at least one row.');
      return;
    }

    // protect against double click
    $btn.prop('disabled', true).text('Approving…');

    const payload = {
      project_id: pid,
      month: month
    };
    
    // Only include intake_ids for non-category 3
    if (category !== 3 && ids.length > 0) {
      payload.intake_ids = ids;
    }

    $.post('{{ route('invoices.approve') }}', payload)
    .done(function (res) {
      if (res && res.status === 1) {
        // Close modal
        try {
          const m = bootstrap.Modal.getInstance(document.getElementById('invoicePreviewModal'));
          if (m) m.hide();
        } catch (e) {}

        // Optional toast
        alert('Invoice created successfully.');

        // Redirect if backend sent a route
        if (res.redirect) {
          window.location.href = res.redirect;
        } else if (res.invoice_id) {
          // Fallback route if you use invoices.show
          window.location.href = "{{ url('/invoices') }}/" + res.invoice_id;
        } else {
          // Otherwise, reload the page/table
          location.reload();
        }
      } else {
        const msg = (res && res.message) ? res.message : 'Approval failed.';
        alert(msg);
      }
    })
    .fail(function (xhr) {
      // Show server-side validation / conflict info if present
      let msg = 'Approval failed.';
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;

        // If duplicates/conflicts returned, show them
        if (xhr.responseJSON.conflicts && xhr.responseJSON.conflicts.length) {
          msg += '\nAlready invoiced intake IDs this month: ' + xhr.responseJSON.conflicts.join(', ');
        }
      }
      alert(msg);
    })
    .always(function () {
      $btn.prop('disabled', false).text('Approve');
    });
  });

  // (Optional) Reject simply closes the modal for now
  $('#btnReject').on('click', function () {
    try {
      const m = bootstrap.Modal.getInstance(document.getElementById('invoicePreviewModal'));
      if (m) m.hide();
    } catch (e) {}
  });
</script>
@endsection
