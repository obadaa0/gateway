<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Restored Notification</title>
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
            color: #5cb85c;
            border-bottom: 2px solid #5cb85c;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #5cb85c;
        }
        .button {
            display: inline-block;
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
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
        <h2>Account Restored Notification</h2>
    </div>

    <div class="content">
        <p>Dear {{ $user->firstname }} {{ $user->lastname }},</p>

        <p>We are pleased to inform you that your account <strong>has been restored</strong> following our review of your appeal.</p>

        <p>Your account access has been reinstated because:</p>
        <ul>
            <li>You demonstrated understanding of our community guidelines</li>
            <li>You showed commitment to positive participation</li>
            <li>The required waiting period has been completed</li>
        </ul>

        <p>We appreciate your cooperation and welcome you back to our platform.</p>

        <a href="localhost:3000/" class="button">Login to Your Account</a>

        <p>Please note that any future violations may result in permanent account termination.</p>
    </div>

    <div class="footer">
        <p>If you didn't request this unblock, please contact our support team immediately.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }} - جميع الحقوق محفوظة.</p>
    </div>
</body>
</html>
