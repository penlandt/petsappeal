<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subscription Renewal Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #fef9c3; color: #78350f; padding: 20px;">
    <h2 style="color: #92400e;">⏳ Your Subscription Will Renew Soon</h2>
    <p>Hi {{ $companyName }},</p>
    <p>This is a friendly reminder that your <strong>{{ $planName }}</strong> subscription will automatically renew on:</p>
    <p><strong>{{ \Carbon\Carbon::parse($renewalDate)->toFormattedDateString() }}</strong></p>
    <p>If you’d like to upgrade, downgrade, or cancel your subscription before then, please visit your <a href="{{ url('/billing/my-plan') }}">My Plan</a> page.</p>
    <br>
    <p>Thanks for being a part of the PETSAppeal community 🐾</p>
    <br>
    <p>— The PETSAppeal Team</p>
</body>
</html>
