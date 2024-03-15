<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs to prevent SQL injection
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $feedback = $conn->real_escape_string($_POST['remark']);
    
    // Insert feedback into the database
    $sql = "INSERT INTO feedback (name, emailid, feedback) VALUES ('$name', '$email', '$feedback')";
    if ($conn->query($sql) === TRUE) {
        // Redirect to a new page after successful submission
        header("Location: feedback_success.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="style.css" rel="stylesheet">
    <script>
        // Function to clear form fields
        function clearForm() {
            document.getElementById("name").value = "";
            document.getElementById("email").value = "";
            document.getElementById("remark").value = "";
        }
    </script>
</head>
<body style="background-image: url('dessert10.jpg'); background-size: cover;">

    <div style="width: 50%; margin: 0 auto; background-color: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 40px; text-align:center;">
        <span class="close" onclick="window.location.href='index.php'">&times;</span>
        <h1>Feedback</h1>
        <form action="feedback.php" method="POST" style="width: 70%;"> <!-- Added action and method attributes -->
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <label for="name" style="width: 80px;">Name:</label>
                <input type="text" id="name" name="name" style="width: 250px;">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <label for="email" style="width: 80px;">Email:</label>
                <input type="email" id="email" name="email" style="width: 240px;">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <label for="remark" style="width: 80px;">Remark:</label>
                <textarea id="remark" name="remark" rows="4" cols="50" style="width: 250px; height: 100px;"></textarea>
            </div>
            <div>
                <button type="submit" style="padding: 10px 20px; background-color: rgb(43, 35, 75); color: #fff; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>

