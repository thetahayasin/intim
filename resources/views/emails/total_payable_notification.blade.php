<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h1>Dear {{ $client->name }},</h1>
    <p>This is a notification of your total payable amount.</p>
    <p><strong>Total Payable Amount:</strong> {{ $totalPayable }}</p>
    <p>Thank you for your continued business!</p>
    <p>Best regards,</p>
    <p>Asif Associates</p>
</body>
</html>