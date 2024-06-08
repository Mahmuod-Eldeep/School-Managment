<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome & Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #777777;
            font-size: 12px;
            padding: 10px;
            border-top: 1px solid #dddddd;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Welcome, {{ $user->name }}</h1>
        </div>
        <div class="content">
            <p>We are excited to have you on board.</p>
            <p>To reset your password, please click the button below:</p>
              <a href="{{ url('127.0.0.1:8000', $user->remember_token) }}" class="button">Reset Password</a>
            <p>If the button doesn't work, copy and paste the following link into your web browser:</p>
          <p><a href="{{ url('http://127.0.0.1:8000/api/rest/' . $user->remember_token) }}">{{ url('http://127.0.0.1:8000/api/rest/') . $user->remember_token }}</a></p>

        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
