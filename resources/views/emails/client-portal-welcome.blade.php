<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ $companyName }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; color: #333; padding: 20px;">

    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('storage/company-assets/' . $clientUser->company_id . '_logo.png') }}"
             alt="Company Logo"
             style="max-width: 200px; max-height: 100px;">
    </div>

    <h2>Welcome to {{ $companyName }}!</h2>

    <p>Hi {{ $clientUser->client->first_name ?? 'there' }},</p>

    <p>Your client portal account has been created. You can use the link below to log in and manage your pet's appointments, view history, and more.</p>

    <p><strong>Login URL:</strong><br>
        <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    <p><strong>Email:</strong> {{ $clientUser->email }}<br>
       <strong>Temporary Password:</strong> {{ $plainPassword }}</p>

    <p>Please log in and change your password right away to keep your account secure.</p>

    <p>If you have any questions, feel free to contact us.</p>

    <p>— The {{ $companyName }} Team</p>
</body>
</html>
