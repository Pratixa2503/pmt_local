<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td,
        .header-table th {
            vertical-align: top;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.4;
        }

        .invoice-title {
            font-size: 50px;
            font-weight: normal;
            text-align: right;
            color: #E35205;
        }

        .product-table th,
        .product-table td {
            padding: 5px;
            font-size: 12px;
        }

        .summary-table td {
            padding: 5px;
            font-size: 12px;
        }

        .last-row {            
            border-top: 1px solid black;
        }
        .product-table tbody td.b{border-bottom: 1px solid black;}
        .bg-dark{background: #ddd;}
         .bg-light{background: #f5f5f5;}
         /* New Css */
  .detail-title { font-size: 16px; margin: 0 0 8px; color: #E35205; }
  .detail-table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 10px; }
  .detail-table th, .detail-table td { border: 1px solid #333; padding: 5px 3px; vertical-align: top; word-wrap: normal; white-space: normal; font-size: 8px; line-height:9px; }
  .detail-table thead th { background: #E35205; color: #fff; text-align: left;  }
  .text-right { text-align: right; }
  .nowrap { white-space: nowrap; }
  .page-break { page-break-before: always; }
    </style>
</head>

<body>
    @php
    // 1 = India, 2 = US/Non-India
    $customerType = (int)($customer_type
        ?? $invoice->customer_type
        ?? optional($invoice->customer)->company_type
        ?? 1);
    $invoiceType = (int)$invoice->invoice_type ?? null;    
    @endphp
    <table class="header-table">
        <!-- Header -->
        <tr>
            <td width="50%" style="padding-top: 10px; padding-bottom: 10px; border-bottom: 0px solid black;">
                <img src="{{ public_path('assets/logo/spring-board-logo.png') }}" width="200"><br>
                <div class="company-info">
                    @if($customerType === 1 || ($customerType === 2 && $invoiceType === 1))
                        <strong>Springbord Systems Private Limited</strong><br>
                        12th Floor, Phase - II, TICEL BIO PARK,<br>
                        Model No.: 1203 No 5, CSIR Road, Taramani,<br>
                        Chennai - 600 013 Tamil Nadu, India.<br>
                        Tel: +91-044-2225-9700
                    @else
                        <strong>Springbord Smartshore LLC</strong><br>
                        100 Broad Street,<br>
                        Eatontown, New Jersey, 07724
                    @endif
                </div>
            </td>
            <td width="50%" class="invoice-title"
                style="padding-top: 10px; padding-bottom: 10px; text-align:right; border-bottom: 0px solid black;">
                INVOICE
            </td>
        </tr>
        @if($customerType === 1)
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
               GSTIN: {{ $company['gst'] }}
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
               PAN: {{ $company['pan'] }}
            </td>
        </tr>
        @endif

        <!-- Bill To / Invoice Details -->
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Bill To:</strong><br>
                {{$invoice->customer_name}}<br>
                 {{$invoice->customer_address}}
                <br>
                
                {{$invoice->customer_zipcode}}
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Invoice Details:</strong><br>
                Invoice No: {{ $invoice->invoice_no }}<br>
                Date: {{ $invoice->invoice_date ?? now()->toDateString() }}<br>
                Due Date: {{ $invoice->due_date ?? '—' }}<br>
                @if($customerType === 2 && $invoiceType === 1)
                Place of Supply: -
                @endif
            </td>
        </tr>
       
        @if($customerType === 1)
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black; border-top: 1px solid black;">
                <strong>GSTIN:</strong> {{ $company['gst'] }}
            </td>
        </tr>
        @endif
        @if($customerType === 2 && $invoiceType === 1)
        <tr>
            <td colspan=""
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; ">
                <strong>IEC Code:</strong> {{ config('companyinfo.iec_code') }}
            </td>
            <td colspan=""
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; ">
                <strong>Purpose Code:</strong> {{ $company['gst'] }}
            </td>
        </tr>
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 0px; padding-bottom: 10px; border-bottom: 1px solid black; ">
                <strong>LUT No & Description:</strong> {{ config('companyinfo.lut_no') }}
            </td>
        </tr>
        @endif
        <!-- Invoice Details Title -->
        <tr>
            <td colspan="2" style="text-align: left; padding-top: 40px; padding-bottom: 5px; color:#E35205;">
                Invoice Details ({{$invoice->currency_name ?? ''}}):
            </td>
        </tr>

        <!-- Product Table -->
        <tr>
            <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;">
                <table class="product-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #E35205; color: #fff; text-align: left;">
                            <th style="width: 5%;">S.No</th>
                            <th style="width: 45%;">Description</th>
                            <th style="width: 10%;">SAC</th>
                            <th style="width: 10%;">Qty</th>
                            <th style="width: 80px; text-align:center">Rate</th>
                            <th style="width: 120px; text-align:center">Taxable Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($lines as $line)
                            <tr>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-dark">{{ $i }}</td>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-light">{{ $line->description }}</td>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-dark">{{ $invoice->sac_number ?? "-" }}</td>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-light">{{ number_format($line->qty, 2) }}</td>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-dark" style="text-align:center">{{ $currencySymbol }} {{ number_format($line->rate, 2) }}</td>
                                <td class="{{ ($loop->last) ? 'last-row' : 'b' }} bg-light" style="text-align:center" >{{ $currencySymbol }}
                                    {{ number_format($line->value ?: ($line->qty * $line->rate), 2) }}
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <!-- Summary Table -->
        <tr>
            <td style="padding-top: 10px; padding-bottom: 10px;" width="40%"></td>
            <td style="padding-top: 10px; padding-bottom: 10px;" width="60%">
                <table class="summary-table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td class="bg-light" style="border-bottom: 1px solid black;">Gross Invoice Value</td>
                            <td class="bg-dark" style="border-bottom: 1px solid black; width:80px; text-align: center;"></td>
                            <td  class="bg-light" style="border-bottom: 1px solid black; width:120px; text-align: center;">{{ $currencySymbol }} {{$invoice->gross_total}}</td>
                        </tr>
                        <!-- <tr>
                            <td class="bg-light" style="border-bottom: 1px solid black;">Discount</td>
                            <td class="bg-dark" style="border-bottom: 1px solid black; width:80px; text-align: center;">XXX</td>
                            <td class="bg-light" style="border-bottom: 1px solid black; width:120px; text-align: center;">XXX</td>
                        </tr> -->
                        @if($customerType === 1)
                        <tr>
                            <td class="bg-light" style="border-bottom: 1px solid black;">GST</td>
                            <td class="bg-dark" style="border-bottom: 1px solid black; width:80px; text-align: center;">{{ config('companyinfo.gst_percent',18) }}%</td>
                            <td class="bg-light" style="border-bottom: 1px solid black; width:120px; text-align: center;">{{ $currencySymbol }} {{$invoice->tax_total}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="bg-light" style="font-weight: bold; color:#E35205;">Net Invoice Value</td>
                            <td class="bg-dark" style="font-weight: bold; color:#E35205; width:0px; text-align: center;"></td>
                            <td class="bg-light" style="font-weight: bold; color:#E35205; width:120px; text-align: center;">{{ $currencySymbol }} {{$invoice->total}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>


    </table>

    <table class="header-table">
        <!-- Net Amount in Words -->
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>
                    Net Amount in Words: {{ Helper::money_to_words($invoice->total,$invoice->currency_name, 'en') }}
                </strong>
            </td>
        </tr>
        @if($invoice->finance_notes)
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Additional Notes:</strong> {{$invoice->finance_notes}}
            </td>
        </tr>
        @endif
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <div>Account Name: {{ $bank->account_name ?? '—' }}</div>
                <div>Bank Name: {{ $bank->bank_name ?? '—' }}</div>
                <div>Swift Code:{{ $bank->swift_code ?? '—' }}</div>
                @if($customerType === 1 || ($customerType === 2 && $invoiceType === 1))
                    <div>IFSC Code:{{ $bank->ifsc_code ?? '—'}}</div>
                @endif
                @if($customerType === 2)
                    <div>Routing No: {{ $bank->routing_number ?? '—' }}</div>
                @endif
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <div>Account No:{{ $bank->account_number ?? '—' }}</div>
                <div>Branch:{{ $bank->branch_location ?? '—' }}</div>
                <div>ABA No: {{ $bank->aba_number ?? '—' }}</div>
                @if($customerType === 1)
                    <div>Routing No: {{ $bank->routing_number ?? '—' }}</div>
                @endif
            </td>
            
        </tr>
       
    </table>
    <table style="position: absolute; bottom:120px; top:auto; left:0;">
         <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 20px; padding-bottom: 10px; border-bottom: 0px solid black;">
                Authorized Signatory Name: <strong>{{ config('companyinfo.signatory') }}</strong>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 0px solid black;">
                Sign:<span style="border-bottom:#000 solid 1px; width: 200px; display:inline-block"></span>
            </td>
        </tr>
    </table>
   @if(isset($rows) && count($rows))
  <div class="page-break"></div>

  @if(isset($projectCategory) && $projectCategory === 3)
    {{-- Billing Details Table for Category 3 --}}
    <div style="margin-bottom: 10px;">
      <h3 class="detail-title">Billing Details</h3>
    </div>
    <table class="detail-table">
      <colgroup>
        <col style="width:10%">   {{-- S.No --}}
        <col style="width:50%">  {{-- Projects --}}
        <col style="width:15%">  {{-- Billable Units --}}
        <col style="width:15%">  {{-- Unit Rate --}}
        <col style="width:20%">  {{-- Billable Value --}}
      </colgroup>
      <thead>
        <tr>
          <th>S.No</th>
          <th>Projects</th>
          <th class="text-right">Billable Units</th>
          <th class="text-right">Unit Rate</th>
          <th class="text-right">Billable Value</th>
        </tr>
      </thead>
      <tbody>
        @php $rowsTotal = 0.0; @endphp
        @foreach($rows as $r)
          @php
            $billableValue = (float)($r->billable_value ?? 0);
            $rowsTotal += $billableValue;
          @endphp
          <tr>
            <td>{{ $r->sno ?? $loop->iteration }}</td>
            <td>{{ $r->project_name ?? 'Data Collection of Indian Companies' }}</td>
            <td class="text-right">{{ number_format($r->billable_units ?? 0, 0) }}</td>
            <td class="text-right">{{ $currencySymbol ?? '' }} {{ number_format($r->unit_rate ?? 0, 2) }}</td>
            <td class="text-right">{{ $currencySymbol ?? '' }} {{ number_format($billableValue, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="text-right" style="font-weight:bold; background:#eee">Total</td>
          <td class="text-right nowrap" style="font-weight:bold; background:#eee">
            {{ $currencySymbol ?? '' }} {{ number_format($rowsTotal, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  @else
    {{-- Detailed Rows Table for Other Categories --}}
  <table class="detail-table">
    {{-- Carefully chosen widths (sum = 100%) --}}
    <colgroup>
      <col style="width:7%">   {{-- Month-Year (short) --}}
      <col style="width:10%">  {{-- Client Name --}}
      <col style="width:12%">  {{-- Project Name --}}
      <col style="width:12%">  {{-- Property Name --}}
      <col style="width:8%">   {{-- Tenant Name --}}
      <col style="width:16%">  {{-- Address (longest text) --}}
      <col style="width:5%">   {{-- City (N/A—kept small) --}}
      <col style="width:5%">   {{-- State (N/A—kept small) --}}
      <col style="width:5%">   {{-- Country (N/A—kept small) --}}
      <col style="width:7%">   {{-- Intake Status --}}
      <col style="width:6%">   {{-- Delivery Date --}}
      <col style="width:6%">   {{-- Type of Work --}}
      <col style="width:3%">   {{-- Language (code) --}}
      <col style="width:8%">   {{-- Cost (currency) --}}
    </colgroup>

    <thead>
      <tr>
        <th>Month-Year</th>
        <th>Client Name</th>
        <th>Project Name</th>
        <th>Property Name</th>
        <th>Tenant Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Country</th>
        <th>Intake Status</th>
        <th>Delivery Date</th>
        <th>Type of Work</th>
        <th>Language</th>
        <th class="text-right">Cost</th>
      </tr>
    </thead>

    <tbody>
      @php $rowsTotal = 0.0; @endphp
      @foreach($rows as $r)
        @php
          $monthYear     = (string)($r->month_year ?? '');
          $clientName    = (string)($r->client_name ?? '');
          $projectName   = (string)($r->project_name ?? '');
          $propertyName  = (string)($r->property_name ?? '');
          $tenantName    = (string)($r->tenant_name ?? '');
          $address       = (string)($r->address ?? '');
          $city          = (string)($r->city ?? '');
          $state         = (string)($r->state ?? '');
          $country       = (string)($r->country ?? '');
          $status        = (string)($r->intake_status ?? '');
          $deliveryDate  = $r->delivery_date ? (is_string($r->delivery_date) ? $r->delivery_date : \Carbon\Carbon::parse($r->delivery_date)->format('Y-m-d')) : '';
          $typeOfWork    = (string)($r->type_of_work ?? '');
          $language      = (string)($r->language ?? '');
          // If you want line VALUE rather than RATE, switch to (float)($r->value ?? 0)
          $costNum       = (float)($r->cost ?? 0);
          $rowsTotal    += $costNum;
        @endphp
        <tr>
          <td class="nowrap">{{ $monthYear }}</td>
          <td>{{ $clientName }}</td>
          <td>{{ $projectName }}</td>
          <td>{{ $propertyName }}</td>
          <td>{{ $tenantName }}</td>
          <td>{{ $address }}</td>
          <td>{{ $city }}</td>
          <td>{{ $state }}</td>
          <td>{{ $country }}</td>
          <td>{{ $status }}</td>
          <td class="nowrap">{{ $deliveryDate }}</td>
          <td>{{ $typeOfWork }}</td>
          <td class="nowrap">{{ $language }}</td>
          <td class="text-right nowrap">{{ $currencySymbol ?? '' }} {{ number_format($costNum, 2) }}</td>
        </tr>
      @endforeach
    </tbody>

    <tfoot>
      <tr>
        <td colspan="13" class="text-right" style="font-weight:bold; background:#eee">Total</td>
        <td class="text-right nowrap" style="font-weight:bold; background:#eee">
          {{ $currencySymbol ?? '' }} {{ number_format($rowsTotal, 2) }}
        </td>
      </tr>
      </tfoot>
    </table>
  @endif
@endif

</body>

</html>