<?php
// Include the database connection script
include 'db_connect.php';

// Check if the recipe ID is provided via GET request
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitize the recipe ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete the recipe from the submitted_recipes table
    $sql = "DELETE FROM submitted_recipes WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        // Redirect to sub_successs.php with success message
        header("Location: sub_success.php?success=Recipe rejected successfully.");
        exit();
    } else {
        // Redirect to sub_recipe.php with error message
        header("Location: sub_success.php?error=Failed to reject recipe.");
        exit();
    }
} else {
    // Redirect to sub_recipe.php with error message
    header("Location: sub_success.php?error=Recipe ID not provided.");
    exit();
}
?>
