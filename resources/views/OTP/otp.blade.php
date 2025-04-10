<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .email-container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: auto;
        }

        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            background: #eaf4ff;
            display: inline-block;
            border-radius: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2>Hello, [{{$first_name}}]!</h2>
        <p>Your verification code is:</p>
        <div class="verification-code">{{$code}}</div>
        <p>Please enter this code to verify your account. This code is valid for 10 minutes.</p>
        <p class="footer">If you didn’t request this, you can safely ignore this email.</p>
    </div>
</body>

</html>