<?php
// Include the database connection script
include 'db_connect.php';

// Check if recipe_id is set in the POST data
if(isset($_GET['recipe_id'])) {
    // Get the recipe ID from the POST data
    $recipe_id = $_GET['recipe_id'];

    // Prepare a delete statement
    $stmt = $conn->prepare("DELETE FROM recipes WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipe_id);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Redirect back to the view_recipes.php page
        header("Location: delete_success.php");
        exit();
    } else {
        echo "Error deleting recipe: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
