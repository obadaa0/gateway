<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تغيير كلمة المرور</title>
</head>
<body style="font-family: 'Tahoma', sans-serif; background-color: #f4f6f8; padding: 40px; margin: 0; direction: rtl;">

    <table width="100%" style="max-width: 600px; margin: auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="background-color: #007BFF; padding: 20px; text-align: center;">
                <h2 style="color: white; margin: 0;">تغيير كلمة المرور</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <p style="font-size: 16px; color: #333;">مرحباً <strong>{{ $user->name }}</strong>،</p>

                <p style="font-size: 16px; color: #333;">
                    نود إعلامك بأنه تم تغيير كلمة المرور الخاصة بك بنجاح. إذا كنت أنت من قمت بهذا التغيير، فلا داعي لأي إجراء إضافي.
                </p>

                <p style="font-size: 16px; color: #333;">
                    أما إذا لم تقم بهذا التغيير، فننصحك بالتواصل مع فريق الدعم فوراً لاتخاذ الإجراءات اللازمة.
                </p>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="http://localhost:5173/" style="background-color: #007BFF; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-size: 16px;">
                        العودة إلى الموقع
                    </a>
                </div>

                <p style="font-size: 14px; color: #888;">شكراً لاستخدامك خدماتنا.</p>
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
