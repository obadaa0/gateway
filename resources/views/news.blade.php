<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأخبار الأسبوعية</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f0f2f5;
            padding: 30px;
            margin: 0;
            color: #333;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #4b107f;
        }

        h2 {
            color: #4b107f;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin-top: 0;
        }

        ul {
            padding-right: 20px;
            list-style-type: square;
            color: #333;
        }

        li {
            margin-bottom: 15px;
            background-color: #f0f2f5;
            padding: 12px 18px;
            border-right: 4px solid #4b107f;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        li:hover {
            background-color: #e2e6ea;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .highlight {
            font-weight: bold;
            color: #4b107f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>مرحبًا <span class="highlight">{{ $user->name }}</span>،</h2>
        <p>يسعدنا أن نشاركك <strong>ملخص الأخبار الأسبوعية</strong>، التي تم جمعها بعناية من مصادرنا الموثوقة:</p>

        <ul>
            @foreach(explode("\n", $news) as $item)
            @if(trim($item) !== '')
            <li>{{ $item }}</li>
            @endif
            @endforeach
        </ul>

        <p>نتمنى لك أسبوعًا آمنًا ومليئًا بالمعلومات المفيدة.</p>

        <div class="footer">
            فريق النشرة الإخبارية<br>
            © {{ now()->year }} جميع الحقوق محفوظة{{ config('app.name')}}
        </div>
    </div>
</body>

</html>
