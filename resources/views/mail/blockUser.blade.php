<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Blocked Notification</title>
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
            color: #d9534f;
            border-bottom: 2px solid #d9534f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #d9534f;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Account Blocked Notification</h2>
    </div>

    <div class="content">
        <p>Dear {{ $user->firstname }} {{ $user->lastname }},</p>

        <p>We regret to inform you that your account has been <strong>permanently blocked</strong> from our platform.</p>

        <p>This action was taken due to violations of our Terms of Service and Community Guidelines, specifically:</p>
        <ul>
            <li>Breach of our privacy policy</li>
            <li>Unauthorized access attempts</li>
            <li>Violation of community standards</li>
        </ul>

        <p>This decision is final and cannot be appealed.</p>
    </div>

    <div class="footer">
        <p>For security reasons, all associated data will be permanently deleted within 30 days.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }} - جميع الحقوق محفوظة.</p>
    </div>
</body>
</html>
