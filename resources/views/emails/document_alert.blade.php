<!DOCTYPE html>
<html>
<head>
    <title>Contract Expiry Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #0d6efd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .details {
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .details table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    
    <p>Hello <strong>Team</strong>,</p>

    <div class="alert-box">
        <strong>⚠️ Alert:</strong> The attached Contract is scheduled to expire in <strong>{{ $daysUntilStart }} {{ $daysUntilStart == 1 ? 'day' : 'days' }}</strong>.
    </div>

    <div class="info-box">
        <h3>Contract Details</h3>
        <div class="details">
            <table>
                <tr>
                    <td>Customer:</td>
                    <td>{{ $document->customer->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Contact Number:</td>
                    <td>{{ $document->contact_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>{{ $document->description ?? 'N/A' }}</td>
                </tr>
                @if($contract)
                <tr>
                    <td>Contract Start Date:</td>
                    <td><strong>{{ $contract->contract_start_date ? \Carbon\Carbon::parse($contract->contract_start_date)->format('F d, Y') : 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td>Contract End Date:</td>
                    <td>{{ $contract->contract_end_date ? \Carbon\Carbon::parse($contract->contract_end_date)->format('F d, Y') : 'N/A' }}</td>
                </tr>
                @endif
                @if(isset($alertDays) && $alertDays)
                <tr>
                    <td>Alert Before:</td>
                    <td>{{ $alertDays }} Days</td>
                </tr>
                @elseif($alert && $alert->alert_days)
                <tr>
                    <td>Alert Before:</td>
                    <td>{{ is_array($alert->alert_days) ? implode(', ', $alert->alert_days) : $alert->alert_days }} Days</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    @if($document->description)
    <div style="margin: 20px 0;">
        <p><strong>Additional Information:</strong></p>
        <p>{{ $document->description }}</p>
    </div>
    @endif

    <p>Please review the contract details and ensure all necessary preparations are in place.</p>

    <div class="footer">
        <p>This is an automated alert from the PM Tool system.</p>
        <p>If you have any questions, please contact your project manager.</p>
        <p>Regards,<br>PM Tool Team</p>
    </div>
</body>
</html>

