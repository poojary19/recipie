<?php
// Include the database connection script
include 'db_connect.php';

// Check if recipe_id is set in the query string
if (isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    // Query to retrieve the recipe details based on recipe_id
    $sql = "SELECT * FROM recipes WHERE recipe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch recipe details
    $recipe = $result->fetch_assoc();

    // Close statement
    $stmt->close();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $recipeName = trim($_POST["recipe_name"]);
    $ingredients = trim($_POST["ingredients"]);
    $instructions = trim($_POST["instructions"]);
    $stateOfOrigin = trim($_POST["state_of_origin"]);
    $category = $_POST["category"];
    $recipeVideoLink = isset($_POST["recipe_video_link"]) ? trim($_POST["recipe_video_link"]) : null;
    
    // Check if a new image file is uploaded
    if ($_FILES["recipe_image"]["size"] > 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["recipe_image"]["name"]);
        move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $targetFile);
        $recipeImage = $targetFile;
    } else {
        // Keep the existing image if no new image is uploaded
        $recipeImage = $recipe['recipe_image'];
    }
    
    // Update the recipe in the database
    $stmt = $conn->prepare("UPDATE recipes SET name=?, ingredients=?, instructions=?, state_of_origin=?, category=?, recipe_image=?, recipe_video_link=? WHERE recipe_id=?");
    $stmt->bind_param("sssssssi", $recipeName, $ingredients, $instructions, $stateOfOrigin, $category, $recipeImage, $recipeVideoLink, $recipe_id);
    
    if ($stmt->execute()) {
        echo "Recipe updated successfully.";
        // Redirect to view_recipes.php or any other page you prefer
        // Redirect to update success page
        header("Location: update_success.php");
        exit();

    } else {
        echo "Error updating recipe: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Edit Recipe</title>
    <style>
        
        body {
            font-family:'Times New Roman', Times, serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            background-image: url('dessert13.jpg'); background-size: cover;

        }

        header {
            background-color:  rgb(43, 35, 75);
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 120px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        img {
            max-width: 300%;
            height: auto;
            margin-bottom: 20px;
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
<body>
    <header>
        <h1>Edit Recipe</h1>
    </header>

    <form style="text-align:left;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?recipe_id=' . $recipe_id; ?>" method="post" enctype="multipart/form-data">
        <span class="close" onclick="window.location.href='index.html'">&times;</span><br>
        
        <label for="recipe_name"><h3>Recipe Name:</h3></label>
        <input type="text" id="recipe_name" name="recipe_name" value="<?php echo $recipe['name']; ?>" required><br>
        
        <label for="ingredients"><h3>Ingredients:</h3></label>
        <textarea id="ingredients" name="ingredients" required><?php echo $recipe['ingredients']; ?></textarea><br>
        
        <label for="instructions"><h3>Instructions:</h3></label>
        <textarea id="instructions" name="instructions" required><?php echo $recipe['instructions']; ?></textarea><br>
        
        <label for="state_of_origin"><h3>State of Origin:</h3></label>
        <input type="text" id="state_of_origin" name="state_of_origin" value="<?php echo $recipe['state_of_origin']; ?>" required><br>
        
        <label for="recipe_image"><h3>Recipe Image:</h3></label>
        <img src="<?php echo $recipe['recipe_image']; ?>" alt="Current Recipe Image" style="max-width: 300px; max-height: 300px;">
        <input type="file" id="recipe_image" name="recipe_image" accept="image/*"><br>
        
        <label for="recipe_video_link"><h3>Recipe Video Link (optional):</h3></label>
        <input type="text" id="recipe_video_link" name="recipe_video_link" value="<?php echo $recipe['recipe_video_link']; ?>"><br>
        
        <label for="category"><h3>Category:</h3></label>
        <select id="category" name="category" required>
            <option value="veg" <?php if ($recipe['category'] == 'veg') echo 'selected'; ?>>Vegetarian</option>
            <option value="nonveg" <?php if ($recipe['category'] == 'nonveg') echo 'selected'; ?>>Non-Vegetarian</option>
        </select><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>
