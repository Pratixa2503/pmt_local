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
    </style>
</head>

<body>
    <table class="header-table">
        <!-- Header -->
        <tr>
            <td width="50%" style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <img src="{{ public_path('assets/logo/spring-board-logo.png') }}" width="200"><br>
                <div class="company-info">
                    Springbord Systems Private Limited<br>
                    12th Floor, Phase - II, TICEL BIO PARK,<br>
                    Model No.: 1203 No 5, CSIR Road, Taramani,<br>
                    Chennai - 600 013 Tamil Nadu, India.<br>
                    Tel: +91-044-2225-9700
                </div>
            </td>
            <td width="50%" class="invoice-title"
                style="padding-top: 10px; padding-bottom: 10px; text-align:right; border-bottom: 1px solid black;">
                INVOICE
            </td>
        </tr>

        <!-- <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
               GSTIN: {{ $company['gst'] }}
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
               PAN: {{ $company['pan'] }}
            </td>
        </tr> -->

        <!-- Bill To / Invoice Details -->
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Bill To:</strong><br>
                Customer Name<br>
                Customer Address Line 1<br>
                Customer Address Line 2<br>
                City - ZIP
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Invoice Details:</strong><br>
                Invoice No: {{ $invoice->invoice_no }}<br>
                Date: {{ $invoice->invoice_date ?? now()->toDateString() }}<br>
                Due Date: {{ $invoice->due_date ?? '—' }}
            </td>
        </tr>

        <!-- Invoice Details Title -->
        <tr>
            <td colspan="2" style="text-align: left; padding-top: 40px; padding-bottom: 5px; color:#E35205;">
                <strong>Invoice Details (USD):</strong>
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
                        @foreach($lines as $line)
                            <tr>
                                <td class="b bg-dark">{{ $line->sno }}</td>
                                <td class="b bg-light">{{ $line->description }}</td>
                                <td class="b bg-dark">{{ $line->sac }}</td>
                                <td class="b bg-light">{{ number_format($line->qty, 2) }}</td>
                                <td class="b bg-dark" style="text-align:center">{{ $currencySymbol }} {{ number_format($line->rate, 2) }}</td>
                                <td class="b bg-light" style="text-align:center" >{{ $currencySymbol }}
                                    {{ number_format($line->value ?: ($line->qty * $line->rate), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="last-row bg-dark">{{ $line->sno }}</td>
                                <td class="last-row bg-light">{{ $line->description }}</td>
                                <td class="last-row bg-dark">{{ $line->sac }}</td>
                                <td class="last-row bg-light">{{ number_format($line->qty, 2) }}</td>
                                <td class="last-row bg-dark" style="text-align:center">{{ $currencySymbol }} {{ number_format($line->rate, 2) }}</td>
                                <td class="last-row bg-light" style="text-align:center" >{{ $currencySymbol }}
                                    {{ number_format($line->value ?: ($line->qty * $line->rate), 2) }}
                                </td>
                            </tr>
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
                            <td class="bg-dark" style="border-bottom: 1px solid black; width:80px; text-align: center;">XXX</td>
                            <td  class="bg-light" style="border-bottom: 1px solid black; width:120px; text-align: center;">XXX</td>
                        </tr>
                        <tr>
                            <td class="bg-light" style="border-bottom: 1px solid black;">Discount</td>
                            <td class="bg-dark" style="border-bottom: 1px solid black; width:80px; text-align: center;">XXX</td>
                            <td class="bg-light" style="border-bottom: 1px solid black; width:120px; text-align: center;">XXX</td>
                        </tr>
                        <tr>
                            <td class="bg-light" style="font-weight: bold; color:#E35205;">Net Invoice Value</td>
                            <td class="bg-dark" style="font-weight: bold; color:#E35205; width:0px; text-align: center;">XXX</td>
                            <td class="bg-light" style="font-weight: bold; color:#E35205; width:120px; text-align: center;">XXX</td>
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
                    Net Amount in Words:
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <strong>Additional Notes:</strong> (Any comments from your Finance team)
            </td>
        </tr>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <div>Account Name:</div>
                <div>Bank Name:</div>
                <div>Swift Code:</div>
                <div>Routing No:</div>
            </td>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid black;">
                <div>Account No:</div>
                <div>Branch:</div>
                <div>ABA No:</div>
                
            </td>
        </tr>
        <tr>
            <td colspan="2"
                style="vertical-align: top; padding-top: 20px; padding-bottom: 10px; border-bottom: 0px solid black;">
                Authorized Signatory Name:
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; border-bottom: 0px solid black;">
                Sign:<span style="border-bottom:#000 solid 1px; width: 200px; display:inline-block"></span>
            </td>
        </tr>
    </table>
</body>

</html>