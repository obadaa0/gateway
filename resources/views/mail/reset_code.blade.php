<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>رمز إعادة تعيين كلمة المرور</title>
</head>
<body style="font-family: 'Tahoma', sans-serif; background-color: #f4f6f8; padding: 40px; margin: 0; direction: rtl;">

    <table width="100%" style="max-width: 600px; margin: auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="background-color: #007BFF; padding: 20px; text-align: center;">
                <h2 style="color: white; margin: 0;">رمز التحقق</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <p style="font-size: 16px; color: #333;">مرحباً <strong>{{ $user->name }}</strong>،</p>

                <p style="font-size: 16px; color: #333;">
                    لقد طلبت إعادة تعيين كلمة المرور. رمز التحقق الخاص بك هو:
                </p>

                <div style="text-align: center; margin: 30px 0;">
                    <span style="display: inline-block; background-color: #007BFF; color: white; font-size: 24px; padding: 12px 24px; border-radius: 5px;">
                        {{ $code }}
                    </span>
                </div>

                <p style="font-size: 14px; color: #888;">إذا لم تطلب ذلك، يمكنك تجاهل هذه الرسالة.</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #888;">
                &copy; {{ date('Y') }} {{ config('app.name') }} - جميع الحقوق محفوظة.
            </td>
        </tr>
    </table>

</body>
</html>
