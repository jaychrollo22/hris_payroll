<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }

        .calling-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .company-logo img {
            width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .first-name {
            margin-right: 5px;
        }

        .company {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .position {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .contact-number {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .status-active {
            font-size: 16px;
            color: green; /* Change color based on status */
        }
        .status-inactive {
            font-size: 16px;
            color: red; /* Change color based on status */
        }


    </style>
</head>
<body>
    @yield('content')
</body>
</html>
