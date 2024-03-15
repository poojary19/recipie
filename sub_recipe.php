<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Recipes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif; /* Specify font family */
            background-color: #f0f0f0; /* Background color */
            padding: 20px; /* Add padding */
            font-family: 'Times New Roman', Times, serif;
            background-image: url('desser5.jpg'); 
            background-size: cover;
        }

        .container {
            max-width: 90%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        .close-button {
            color: white; 
            float: right; 
            font-size: 28px; 
            font-weight: bold; 
            cursor: pointer;
            padding-right: 10px;
        }
        .recipe-section {
            display: flex;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .recipe-image {
            flex: 1;
        }
        .recipe-details {
            flex: 2;
            padding-left: 20px;
        }
        .recipe-details p {
            margin-bottom: 10px;
        }
        .button-container {
            margin-top: 20px;
        }
        .approval-button,
        .reject-button {
            margin-right: 10px;
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            text-align: center;
            text-decoration: none; 
            display: inline-block; 
            border-radius: 5px;
        }

        .header {
            margin-bottom: 50px; /* Add margin to separate header from buttons */
            color: #f0e1e1;
            font-size: 10;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-style: italic;
            text-align: center;
            padding: 2%;
            background-color:rgb(53, 53, 78);
        }

        p {
            color: black;
            font-size: 15px;
        }

        .feed0 {
            width:300px;
            background-color: bisque;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }

        .feed1 {
            width:500px;
            background-color: bisque;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }

        .feed2 {
            width:300px;
            background-color: bisque;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }
        
    </style>
</head>
<body>
    <!-- Display submitted recipes with approval/rejection options -->
    <div class="container">
        <span class="close-button" onclick="window.location.href='admin.php'">&times;</span>
        <div class="header">
            <h1>Submitted Recipe</h1>
        </div>
        <?php
        // Include the database connection script
        include 'db_connect.php';

        // Query to select all submitted recipes from the submitted_recipes table
        $sql = "SELECT * FROM submitted_recipes";
        $result = $conn->query($sql);

        if ($result !== false && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='recipe-section'>";
                echo "<div class='recipe-image'>";
                echo "<section class='feed0'>";
                // Display recipe image
                if (!empty($row['recipe_image'])) {
                    echo '<img src="' . $row['recipe_image'] . '" alt="Recipe Image" style="max-width: 100%; max-height: 250px;">';
                }
                echo "</section>";
                echo "</div>";
                echo "<div class='recipe-details'>";
                echo "<section class='feed1'>";
                echo "<h2>" . $row["name"] . "</h2>";
                echo "<p><strong>Ingredients: </strong>" . $row["ingredients"] . "</p>";
                echo "<p><strong>Instructions: </strong>" . $row["instructions"] . "</p>";
                echo "<p><strong>State of Origin: </strong>" . $row["state_of_origin"] . "</p>";
                echo "<p><strong>Category: </strong>" . $row["category"] . "</p>";

                // Display recipe video link
                if (!empty($row['recipe_video_link'])) {
                    echo '<p><strong>Video Link: <a href="' . $row['recipe_video_link'] . '" target="_blank">Open in new window</a></strong></p>';
                }
                echo "</section>";
                echo "</div>";

                 echo "<div class='recipe-details'>";
                 echo "<section class='feed2'>";
                // Display nutritional values if available
                echo "<h3>Nutritional Values:</h3>";
                echo "<p><strong>Carbohydrate:</strong> {$row['carbohydrate']} g</p>";
                echo "<p><strong>Fat:</strong> {$row['fat']} g</p>";
                echo "<p><strong>Protein:</strong> {$row['protein']} g</p>";
                echo "<p><strong>Calories:</strong> {$row['calories']} kcal</p>";

                echo "<div class='button-container'>";
                echo "<a class='approval-button' href='approve_recipe.php?id=" . $row["id"] . "'>Approve</a>";
                echo "<a class='reject-button' href='reject_recipe.php?id=" . $row["id"] . "'>Reject</a>";
                echo "</section>";
                echo "</div>";
                echo "</div>"; // end of recipe-details
                echo "</div>"; // end of recipe-section
            }
        } else {
            echo "<h3>No submitted recipes.</h3>";
        }
        ?>
    </div>
</body>
</html>
