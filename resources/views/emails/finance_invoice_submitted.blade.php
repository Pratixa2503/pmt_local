@php
  $brand = $brandName ?? 'Springbord';
  $logo  = $logoUrl ?? asset('assets/img/logo.svg');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $brand }} — Invoice Submitted</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
@media (max-width: 600px) {
  .container { width:100% !important; padding:16px !important; }
  .card { padding:20px !important; }
  .cta { display:block !important; width:100% !important; text-align:center !important; }
}
</style>
</head>
<body style="margin:0;background:#FFFFFF;
             font-family:'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
             color:#111827;">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#FFFFFF;">
    <tr>
      <td align="center" style="padding:24px;">
        <table class="container" role="presentation" width="600" cellpadding="0" cellspacing="0"
               style="width:600px; max-width:600px; background:#FFFFFF; border-radius:14px; overflow:hidden;
                      box-shadow:0 4px 14px rgba(0,0,0,0.07); border:1px solid #97999B1A;">

          <tr>
            <td style="background:#FFFFFF; padding:18px 24px; border-bottom:1px solid #97999B33;">
              <table width="100%">
                <tr>
                  <td align="left" style="line-height:0;">
                    <img src="{{ $logo }}" alt="{{ $brand }} Logo" height="36"
                         style="display:block; height:36px; margin:0; border:0; outline:none;">
                  </td>
                  <td align="right" style="font-size:13px; color:#97999B;">{{ $brand }}</td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td class="card" style="padding:28px 28px 8px 28px;">
              <h1 style="margin:0 0 12px 0; font-size:20px; font-weight:700; color:#111827;">
                Finance Review: Invoice {{ $invoiceNo }}
              </h1>
              <p style="margin:0 0 18px 0; font-size:14px; color:#555;">A new invoice is ready for your review.</p>

              <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0 0 16px 0;">
                <tr>
                  <td style="background:#FFFFFF; border:1px solid #97999B66; border-radius:10px; padding:14px;">
                    <table width="100%">
                      <tr>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Invoice No:</strong> {{ $invoiceNo }}
                        </td>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Date:</strong> {{ $invoiceDate }}
                        </td>
                      </tr>

                      <tr>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Project:</strong> {{ $projectName }}
                        </td>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Billing Month:</strong> {{ $billingMonth }}
                        </td>
                      </tr>

                      <tr>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Customer:</strong> {{ $customerName }}
                        </td>
                        <td style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>PO Number:</strong> {{ $poNumber ?: '—' }}
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="font-size:14px; color:#111827; padding:6px 0;">
                          <strong>Total:</strong> {{ $totalFormatted }}
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- CTA -->
              <table role="presentation" cellpadding="0" cellspacing="0" align="left" style="margin:0 0 24px 0;">
                <tr>
                  <td>
                    <a href="{{ $reviewUrl }}" class="cta"
                       style="text-decoration:none; display:inline-block; background:#E35205; color:#FFFFFF;
                              font-weight:700; font-size:14px; padding:12px 18px; border-radius:10px;">
                      Review Invoice
                    </a>
                  </td>
                </tr>
              </table>

              <div style="clear:both;"></div>

              <p style="margin:0 0 24px 0; font-size:12px; color:#97999B;">
                If the button doesn’t work, copy and paste this into your browser:<br>
                <span style="word-break:break-all; color:#555;">{{ $reviewUrl }}</span>
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:18px 28px; border-top:1px solid #97999B33; background:#FFFFFF; font-size:12px; color:#97999B;">
              © {{ date('Y') }} {{ $brand }}. All rights reserved.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
 