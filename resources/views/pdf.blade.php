<!DOCTYPE html>
<html>
<head>
    <title>Report for {{ $record->assured }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .section { margin-bottom: 20px; }
        .item { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Insurance Report</h1>

    <div class="section">
        <div class="item"><strong>ARPR Number:</strong> {{ $record->arpr_num }}</div>
        <div class="item"><strong>ARPR Date:</strong> {{ $record->arpr_date }}</div>
        <div class="item"><strong>Assured:</strong> {{ $record->assured }}</div>
        <div class="item"><strong>Policy Number:</strong> {{ $record->policy_num }}</div>
        <div class="item"><strong>Sale Person:</strong> {{ $record->sale_person }}</div>
    </div>

    <div class="section">
        <div class="item"><strong>Insurance Product:</strong> {{ $record->insurance_prod }}</div>
        <div class="item"><strong>Insurance Type:</strong> {{ $record->insurance_type }}</div>
        <div class="item"><strong>Inception Date:</strong> {{ $record->inception_date }}</div>
        <div class="item"><strong>Policy Status:</strong> {{ $record->policy_status }}</div>
    </div>

    <div class="section">
        <div class="item"><strong>Gross Premium:</strong> {{ $record->gross_premium }}</div>
        <div class="item"><strong>Payment Mode:</strong> {{ $record->payment_mode }}</div>
        <div class="item"><strong>Total Payment:</strong> {{ $record->total_payment }}</div>
        <div class="item"><strong>Payment Balance:</strong> {{ $record->payment_balance }}</div>
        <div class="item"><strong>Payment Status:</strong> {{ $record->payment_status }}</div>
    </div>

    @if($record->plate_num || $record->car_details)
    <div class="section">
        <div class="item"><strong>Plate Number:</strong> {{ $record->plate_num }}</div>
        <div class="item"><strong>Car Details:</strong> {{ $record->car_details }}</div>
    </div>
    @endif

    <div class="section">
        <div class="item"><strong>Remit Date:</strong> {{ $record->remit_date }}</div>
        <div class="item"><strong>Cashier Remarks:</strong> {{ $record->cashier_remarks }}</div>
        <div class="item"><strong>Account Remarks:</strong> {{ $record->acct_remarks }}</div>
    </div>
</body>
</html>