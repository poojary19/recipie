<?php
// Include the database connection script
include 'db_connect.php';

// Start the session
session_start();

// Determine the URL to redirect to based on the user's role
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $redirect_url = 'admin.php';
    $is_admin = true;
} else {
    $redirect_url = 'index.php';
    $is_admin = false;
}

// Initialize the $search and $category variables
$search = '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif; /* Specify font family */
            background-color: #f0f0f0; /* Background color */
            padding: 20px; /* Add padding */
            
        }
        /* Style for search button */
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-button:hover {
            background-color: #4caf51;
        }

        .close-button {
            color: white; 
            float: right; 
            font-size: 28px; 
            font-weight: bold; 
            cursor: pointer;
            padding-right: 10px;
            padding-top: 20px;
        }

        form {
            max-width: 500px; /* Set maximum width for the form */
            margin: 0 auto; /* Center the form horizontally */
            background-color: #fff; /* Form background color */
            padding: 20px; /* Add padding */
            border-radius: 8px; /* Add border radius */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow */
        }

        input[type="text"],
        textarea,
        select {
            width: 100%; /* Set width to 100% */
            padding: 5px; /* Add padding */
            margin-bottom: 15px; /* Add margin bottom */
            border: 1px solid #ccc; /* Add border */
            border-radius: 5px; /* Add border radius */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }

        .feed0 {
            width:300px;
            background-color: ghostwhite;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }

        .feed1 {
            width:800px;
            background-color: ghostwhite;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }

        .feed2 {
            width:300px;
            background-color: ghostwhite;
            padding: 20px 20px 20px 20px;
            border-radius: 30px;
        }

</style>
</head>
<body>

<!-- Close button -->
<span style="color: #142649; float: right; font-size: 28px; font-weight: bold; cursor: pointer;" onclick="window.location.href='view_categories.php'">&times;</span><br>

<!-- Display search results -->
<?php
// Check if the search query and category are set
if (isset($_GET['search']) && isset($_GET['category'])) {
    $search = '%' . $_GET['search'] . '%'; // Add wildcards to search term
    $category = $_GET['category'];

    // Query to retrieve recipes based on the search query and category
    $sql = "SELECT * FROM recipes WHERE category = ? AND (name LIKE ? OR ingredients LIKE ?)";
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("sss", $category, $search, $search); // Binding parameters directly
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any search results
    if ($result->num_rows > 0) {
        // Loop through search results and display them
        while ($row = $result->fetch_assoc()) {
            // Fetch nutritional values for the current recipe
            $nutrition_sql = "SELECT * FROM nutrition WHERE recipe_id = ?";
            $nutrition_stmt = $conn->prepare($nutrition_sql);
            $nutrition_stmt->bind_param("i", $row['recipe_id']);
            $nutrition_stmt->execute();
            $nutrition_result = $nutrition_stmt->get_result();
            // Fetch the first (assuming unique) nutritional row if available
            $nutrition_row = $nutrition_result->fetch_assoc();
            ?>
            <!-- Your HTML structure for displaying the recipe -->
            <div style="display: flex; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <section class="feed0">
                        <?php
                        // Display recipe image
                        if (!empty($row['recipe_image'])) {
                            echo '<img src="' . $row['recipe_image'] . '" alt="Recipe Image" style="max-width: 100%;">';
                        }
                        ?>
                    </section>
                </div>
                <div style="flex: 2; padding-left: 20px; padding-right:20px; width:min-content;">
                    <section class="feed1">
                        <!-- Display recipe details -->
                        <h2>Recipe Name: <?php echo $row['name']; ?></h2>
                        <p><strong>Ingredients:</strong><br><?php echo $row['ingredients']; ?></p>
                        <p><strong>Instructions:</strong><br><?php echo nl2br($row['instructions']); ?></p>
                        <p><strong>State of Origin:</strong> <?php echo $row['state_of_origin']; ?></p>
                        <?php
                        // Display recipe video link
                        if (!empty($row['recipe_video_link'])) {
                            echo '<p><strong>Video Link: <a href="' . $row['recipe_video_link'] . '" target="_blank">Open in new window</a></strong></p>';
                        }
                        ?>
                        <?php if ($is_admin): ?>
                            <!-- Edit and delete buttons for admin -->
                            <div style="display: flex; margin-bottom: 20px;">
                                <div style="flex: 1; padding:0%">
                                    <a href="edit_recipe.php?recipe_id=<?php echo $row['recipe_id']; ?>" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;">Edit</a>
                                </div>
                                <div style="flex: 2;padding-right:70%">
                                    <a href="delete_recipe.php?recipe_id=<?php echo $row['recipe_id']; ?>" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;">Delete</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
                <div style="flex: 2; padding-left: 20px; padding-right:20px; width:min-content;">
                    <section class="feed2">
                        <?php
                        // Display nutritional values if available
                        if ($nutrition_row) {
                            echo "<h2>Nutritional Values:</h2>";
                            echo "<p><strong>Carbohydrate:</strong> {$nutrition_row['carbohydrate']} g</p>";
                            echo "<p><strong>Fat:</strong> {$nutrition_row['fat']} g</p>";
                            echo "<p><strong>Protein:</strong> {$nutrition_row['protein']} g</p>";
                            echo "<p><strong>Calories:</strong> {$nutrition_row['calories']} kcal</p>";
                        }
                        ?>
                    </section>
                </div>
            </div>
            <hr>
            <?php
        }
    } else {
        // If no search results found
        header("Location: sub_success.php");
    }
    // Close statement and result sets
    $stmt->close();
    $nutrition_stmt->close();
}

// Close database connection
$conn->close();
?>
</body>
</html>
