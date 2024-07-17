<!DOCTYPE html>
<html>
<head>
    <title>Report for {{ $record->assured }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ public_path('css/insurance.css') }}">
</head>
<body>
    <h1>Insurance Report <br>{{ $record->arpr_num }}</h1>

    <img class="report-logo" src="{{public_path('images/aap-logo.png')}}" alt="aap-logo">
    <!-- General Details -->
    <p class="printed-date">Date Printed: {{$record->created_at->now()}}</p>
    <div class="section">
        <div class="section-title">General Information</div>
        <div class="item"><strong>ARPR Number:</strong> {{ $record->arpr_num }}</div>
        <div class="item"><strong>ARPR Date:</strong> {{ $record->arpr_date }}</div>
        <div class="item"><strong>Assured:</strong> {{ $record->assured }}</div>
        <div class="item"><strong>Policy Number:</strong> {{ $record->policy_num }}</div>
        <div class="item"><strong>Sale Person:</strong> {{ $record->sale_person }}</div>
    </div>
    <!-- Insurance Details -->
    <div class="section">
        <div class="section-title">Insurance Details</div>
        <div class="item"><strong>Insurance Type:</strong> {{ $record->insurance_prod->getLabel() }}</div>
        <div class="item"><strong>Insurance Type:</strong> {{ $record->insurance_type->getLabel() }}</div>
        <div class="item"><strong>Inception Date:</strong> {{ $record->inception_date }}</div>
        <div class="item"><strong>Policy Status:</strong> {{ $record->policy_status->getLabel() }}</div>
    </div>
    <!-- Payment Details -->
    <div class="section">
        <div class="section-title">Payment Details</div>
        <div class="item"><strong>Terms:</strong> {{ $record->terms->getLabel() }}</div>
        <div class="item"><strong>Gross Premium:</strong> {{ $record->gross_premium }}</div>
        <div class="item"><strong>Mode of Payment:</strong> {{ $record->payment_mode->getLabel() }}</div>
        <div class="item"><strong>Total Payment:</strong> {{ $record->total_payment }}</div>
        <div class="item"><strong>Payment Balance:</strong> {{ $record->payment_balance }}</div>
        <div class="item"><strong>Payment Status:</strong> {{ $record->payment_status->getLabel() }}</div>
    </div>
    <!-- Vehicle Details -->
    @if($record->plate_num || $record->car_details)
    <div class="section">
        <div class="section-title">Vehicle Details</div>
        <div class="item"><strong>Plate Number:</strong> {{ $record->plate_num }}</div>
        <div class="item"><strong>Car Details:</strong> {{ $record->car_details }}</div>
        <div class="item"><strong>Mortgagee:</strong> {{ $record->financing_bank }}</div>
    </div>
    @endif

    <!-- Remarks -->
    <!-- <div class="section">
        <div class="section-title">Remarks</div>
        <div class="item"><strong>Remit Date:</strong> {{ $record->remit_date }}</div>
        <div class="item"><strong>Cashier Remarks:</strong> {{ $record->cashier_remarks }}</div>
        <div class="item"><strong>Account Remarks:</strong> {{ $record->acct_remarks }}</div>
    </div> -->
</body>
</html>