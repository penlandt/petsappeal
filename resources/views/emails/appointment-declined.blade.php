<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Appointment Declined</title>
</head>
<body>
    <p>Dear {{ $clientName }},</p>

    <p>We regret to inform you that your appointment request for <strong>{{ $petName }}</strong> on <strong>{{ $start->format('F j, Y') }}</strong> at <strong>{{ $start->format('g:i A') }}</strong> has been declined.</p>

    @if(!empty($declineReason))
        <p><strong>Reason given:</strong></p>
        <p>{{ $declineReason }}</p>
    @endif

    <p>If you have any questions or would like to reschedule, please contact us at your convenience.</p>

    <p>Thank you for understanding.</p>

    <p>Sincerely,<br />
    {{ $appointment->location->name ?? 'Your Location' }}</p>
</body>
</html>
