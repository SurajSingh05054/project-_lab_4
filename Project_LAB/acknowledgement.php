<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental - Booking Acknowledgment</title>
    <?php session_start(); ?>
    <!-- Add your CSS styling here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Acknowledgment</h1>
        <p>Thank you for choosing our car rental service. We are delighted to acknowledge your booking. Below are the details of your reservation:</p>

        <h2>Order Acknowledgment</h2>
        <ul>
            <li><strong>Name:</strong> <?php echo $_SESSION["name"]; ?></li>
            <li><strong>Car Model:</strong> <?php echo $_SESSION["vehicle"]; ?></li>
            <li><strong>Pickup Date:</strong> <?php echo $_SESSION["pickup"]; ?></li>
            <li><strong>Return Date:</strong> <?php echo $_SESSION["ret"]; ?></li>
        </ul>

        <p>Your booking is now confirmed, and you will receive a confirmation email shortly. Should you have any questions or require assistance with your reservation, please don't hesitate to contact our customer support team.</p>

        <p>Thank you for choosing us, and we look forward to serving you!</p>
    </div>
    <?php session_destroy(); ?>
</body>
</html>
