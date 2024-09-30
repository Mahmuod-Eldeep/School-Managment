<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h1 {
            color: #4caf50;
        }
        .content p {
            font-size: 18px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Confirmation</h1>
        </div>
        <div class="content">
                        <h1>Welcome, {{ $user->name }}</h1>
            <h1>School fees have been successfully paid.</h1>


            <p>The amount paid {{ $data->InvoiceValue }} </p>
            <p>We are happy to serve you and look forward to providing more services to you.</p>
        </div>
        <div class="footer">
            <p>&copy; School-Management 2024</p>
        </div>
    </div>
</body>
</html>
