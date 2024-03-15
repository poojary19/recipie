<?php
// Include the database connection script
include 'db_connect.php';

// Check if the recipe ID is provided via GET request
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitize the recipe ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Retrieve the recipe details from the submitted_recipes table
    $sql = "SELECT * FROM submitted_recipes WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Retrieve the recipe details
        $row = $result->fetch_assoc();
        
        // Insert the recipe details into the recipes table
        $name = $row['name'];
        $ingredients = $row['ingredients'];
        $instructions = $row['instructions'];
        $state_of_origin = $row['state_of_origin'];
        $category = $row['category'];
        $recipe_video_link = $row['recipe_video_link'];
        $recipe_image = $row['recipe_image'];

        $insert_recipe_sql = "INSERT INTO recipes (name, ingredients, instructions, state_of_origin, category, recipe_video_link, recipe_image) VALUES ('$name', '$ingredients', '$instructions', '$state_of_origin', '$category', '$recipe_video_link', '$recipe_image')";
        if ($conn->query($insert_recipe_sql) === TRUE) {
            // Move nutritional values to the nutrition table
            $recipe_id = $conn->insert_id;
            $carbohydrate = $row['carbohydrate'];
            $fat = $row['fat'];
            $protein = $row['protein'];
            $calories = $row['calories'];

            $insert_nutrition_sql = "INSERT INTO nutrition (recipe_id, carbohydrate, fat, protein, calories) VALUES ('$recipe_id', '$carbohydrate', '$fat', '$protein', '$calories')";
            if ($conn->query($insert_nutrition_sql) === TRUE) {
                // Delete the recipe from the submitted_recipes table
                $delete_sql = "DELETE FROM submitted_recipes WHERE id='$id'";
                if ($conn->query($delete_sql) === TRUE) {
                    // Redirect to sub_success.php with success message
                    header("Location: sub_success.php");
                    exit();
                } else {
                    // Redirect to sub_recipe.php with error message
                    header("Location: sub_recipe.php?error=Failed to delete recipe from submitted_recipes table.");
                    exit();
                }
            } else {
                // Redirect to sub_recipe.php with error message
                header("Location: sub_recipe.php?error=Failed to insert nutritional values.");
                exit();
            }
        } else {
            // Redirect to sub_recipe.php with error message
            header("Location: sub_recipe.php?error=Failed to insert recipe into recipes table.");
            exit();
        }
    } else {
        // Redirect to sub_recipe.php with error message
        header("Location: sub_recipe.php?error=Recipe not found.");
        exit();
    }
} else {
    // Redirect to sub_recipe.php with error message
    header("Location: sub_recipe.php?error=Recipe ID not provided.");
    exit();
}
?>
