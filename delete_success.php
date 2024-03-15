<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            text-align: center;
            padding-top: 50px;
            background-image: url('dessert3.jpg'); background-size: cover;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recipe deleted successfully!</h2>
        <p>You will be redirected to the view categories page shortly...</p>
    </div>
</body>
</html>

<?php
// Redirect to view recipes page after 3 seconds
header("refresh:3;url=view_categories.php");
exit();
?>
