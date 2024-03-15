<?php
session_start(); // Start the session

// Determine the URL to redirect to based on the user type
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $redirect_url = 'admin.php';
} else {
    $redirect_url = 'index.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .review-section {
            margin-bottom: 10px;
            width: 50%;
            margin: 0 auto; 
            background-color: wheat;
            padding: 20px; 
            border-radius: 40px; 
            text-align:left;
            font-style: italic;
            font-family: 'Times New Roman', Times, serif;
            padding-left: 80px;
        }
        .review-heading {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .review-content {
            font-size: 20px;
            line-height: 1.5;
        }
        .close {
            color: #142649;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        </style>
</head>
<body style="background-image: url('dessert3.jpg'); background-size: cover;">
<span class="close" onclick="window.location.href='<?php echo $redirect_url; ?>'">&times;</span><br>

    <h1 style="text-align: center;font-size: 30px; font-family:'Times New Roman', Times, serif;">Reviews On Website</h1>

    <?php
    // Include the database connection file
    include 'db_connect.php';

    // Query to select all feedbacks from the feedback table
    $sql = "SELECT * FROM feedback";
    $result = $conn->query($sql);

    // Check if there are any feedbacks
    if ($result->num_rows > 0) {
        // Output each feedback in a styled section
        while($row = $result->fetch_assoc()) {
            echo '<div class="review-section">';
            echo '<h2 class="review-heading">Review ' . $row["id"] . '</h2>';
            echo '<p class="review-content"><strong>Name:</strong> ' . $row["name"] . '<br>';
            echo '<strong>Email:</strong> ' . $row["emailid"] . '<br>';
            echo '<strong>Feedback:</strong> ' . $row["feedback"] . '</p>';
            echo '</div>'.'<br>';
        }
    } else {
        echo '<p>No reviews available.</p>';
    }

    // Close the database connection
    $conn->close();
    ?>

</body>
</html>