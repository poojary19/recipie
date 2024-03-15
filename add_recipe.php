<?php
// Include the database connection script
include 'db_connect.php';

// Define variables for form data
$recipeName = $ingredients = $instructions = $stateOfOrigin = $recipeVideoLink = $category = "";
$errors = [];

// Additional variables for nutritional values
$carbohydrate = $fat = $protein = $calories = "";

// Start the session
session_start();

// Check if the user is an admin
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    // Admin can directly add recipes to the database
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate form data
        $recipeName = trim($_POST["recipe_name"]);
        if (empty($recipeName)) {
            $errors[] = "Recipe name is required.";
        }

        $ingredients = trim($_POST["ingredients"]);
        if (empty($ingredients)) {
            $errors[] = "Ingredients are required.";
        }

        $instructions = trim($_POST["instructions"]);
        if (empty($instructions)) {
            $errors[] = "Instructions are required.";
        }

        $stateOfOrigin = trim($_POST["state_of_origin"]);

        $recipeVideoLink = isset($_POST["recipe_video_link"]) ? trim($_POST["recipe_video_link"]) : null;
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["recipe_image"]["name"]);

        $category = $_POST["category"];

        // Additional fields for nutritional values
        $carbohydrate = $_POST["carbohydrate"];
        $fat = $_POST["fat"];
        $protein = $_POST["protein"];
        $calories = $_POST["calories"];

        // Prepare and bind parameters for recipes
        $stmt_recipe = $conn->prepare("INSERT INTO recipes (name, ingredients, instructions, state_of_origin, category, recipe_image, recipe_video_link, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt_recipe->bind_param("sssssss", $recipeName, $ingredients, $instructions, $stateOfOrigin, $category, $targetFile, $recipeVideoLink);

        // Execute the query for recipes
        if ($stmt_recipe->execute()) {
            // Get the last inserted recipe ID
            $recipe_id = $stmt_recipe->insert_id;

            // Prepare and bind parameters for nutritional values
            $stmt_nutrition = $conn->prepare("INSERT INTO nutrition (recipe_id, carbohydrate, fat, protein, calories) VALUES (?, ?, ?, ?, ?)");
            $stmt_nutrition->bind_param("idddd", $recipe_id, $carbohydrate, $fat, $protein, $calories);

            // Execute the query for nutritional values
            if ($stmt_nutrition->execute()) {
                header("Location: update_success.php");
                exit();
            } else {
                $errors[] = "Error inserting nutritional values: " . $stmt_nutrition->error;
            }
            $stmt_nutrition->close(); // Close nutritional values statement
        } else {
            $errors[] = "Error inserting recipe: " . $stmt_recipe->error;
        }

        // Close statement for recipes
        $stmt_recipe->close();
    }
} else {
    // Non-admin users' recipes are added to the submitted_recipes table
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate form data
        $recipeName = trim($_POST["recipe_name"]);
        if (empty($recipeName)) {
            $errors[] = "Recipe name is required.";
        }

        $ingredients = trim($_POST["ingredients"]);
        if (empty($ingredients)) {
            $errors[] = "Ingredients are required.";
        }

        $instructions = trim($_POST["instructions"]);
        if (empty($instructions)) {
            $errors[] = "Instructions are required.";
        }

        $stateOfOrigin = trim($_POST["state_of_origin"]);

        $recipeVideoLink = isset($_POST["recipe_video_link"]) ? trim($_POST["recipe_video_link"]) : null;
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["recipe_image"]["name"]);

        $category = $_POST["category"];

        // Additional fields for nutritional values
        $carbohydrate = $_POST["carbohydrate"];
        $fat = $_POST["fat"];
        $protein = $_POST["protein"];
        $calories = $_POST["calories"];

        // Prepare and bind parameters for submitted recipes
        $stmt = $conn->prepare("INSERT INTO submitted_recipes (name, ingredients, instructions, state_of_origin, category, recipe_image, recipe_video_link, carbohydrate, fat, protein, calories) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $recipeName, $ingredients, $instructions, $stateOfOrigin, $category, $targetFile, $recipeVideoLink, $carbohydrate, $fat, $protein, $calories);

        // Execute the query for submitted recipes
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        // Close statement for submitted recipes
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Add Recipe</title>
    <style>
        header {
            font-family:times-new-roman;
            background-color: rgb(43, 35, 75);
            color: #fff;
            text-align: center;
            padding: 10px;
        }
        body{
            font-family:times-new-roman;
            margin: 0;
            padding: 0;
            background-color:#ee9999;
            background-image: url('dessert13.jpg'); background-size: cover;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color:white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        button {
            background-color: rgb(43, 35, 75);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: rgb(24, 21, 66);
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
        <h1>Add Recipe</h1>
    </header>

    <?php
    // Determine the URL to redirect to based on the user's role
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
        $redirect_url = 'admin.php';
    } else {
        $redirect_url = 'index.php';
    }
    ?>

    <form style="text-align:left;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <!-- Close button with onclick event for redirection -->    
    <span class="close" onclick="window.location.href='<?php echo $redirect_url; ?>'">&times;</span><br>

        <?php foreach ($errors as $error) : ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>

        <label for="recipe_name"><h3>Recipe Name:</h3></label>
        <input type="text" id="recipe_name" name="recipe_name" placeholder="Enter the Recipe Name" value="<?php echo htmlspecialchars($recipeName); ?>" required><br>
        
        <label for="ingredients"><h3>Ingredients:</h3></label>
        <textarea id="ingredients" name="ingredients" placeholder="Enter the Ingredients" required><?php echo htmlspecialchars($ingredients); ?></textarea><br>
        
        <label for="instructions"><h3>Instructions:</h3></label>
        <textarea id="instructions" name="instructions" placeholder="Enter the Instructions" required><?php echo htmlspecialchars($instructions); ?></textarea><br>
        
        <label for="state_of_origin"><h3>State of Origin:</h3></label>
        <input type="text" id="state_of_origin" name="state_of_origin" placeholder="Enter the State of Origin" value="<?php echo htmlspecialchars($stateOfOrigin); ?>" required><br>

        <label for="recipe_image"><h3>Recipe Image:</h3></label>
        <input type="file" id="recipe_image" name="recipe_image" accept="image/*" required><br>
        
        <label for="recipe_video_link"><h3>Recipe Video Link (optional):</h3></label>
        <input type="text" id="recipe_video_link" name="recipe_video_link" value="<?php echo htmlspecialchars($recipeVideoLink); ?>"><br>
        
        <label for="category"><h3>Category:</h3></label>
        <select id="category" name="category" placeholder="Click on the Recipe Category" required>
            <option value="">Select Category</option>
            <option value="veg" <?php if ($category === "veg") echo "selected"; ?>>Vegetarian</option>
            <option value="nonveg" <?php if ($category === "nonveg") echo "selected"; ?>>Non-Vegetarian</option>
        </select><br>

        <!-- Nutritional values -->
        <label for="carbohydrate"><h3>Carbohydrate (g):</h3></label>
        <input type="text" id="carbohydrate" name="carbohydrate" placeholder="Enter Carbohydrate" value="<?php echo htmlspecialchars($carbohydrate); ?>"><br>

        <label for="fat"><h3>Fat (g):</h3></label>
        <input type="text" id="fat" name="fat" placeholder="Enter Fat" value="<?php echo htmlspecialchars($fat); ?>"><br>

        <label for="protein"><h3>Protein (g):</h3></label>
        <input type="text" id="protein" name="protein" placeholder="Enter Protein" value="<?php echo htmlspecialchars($protein); ?>"><br>

        <label for="calories"><h3>Calories:</h3></label>
        <input type="text" id="calories" name="calories" placeholder="Enter Calories" value="<?php echo htmlspecialchars($calories); ?>"><br>
        
        
        <input type="submit" value="Submit">
    </form>

    <script>
        // Function to clear form fields
        function clearForm() {
            document.getElementById("recipe_name").value = "";
            document.getElementById("ingredients").value = "";
            document.getElementById("instructions").value = "";
            document.getElementById("state_of_origin").value = "";
            document.getElementById("recipe_image").value = "";
            document.getElementById("recipe_video_link").value = "";
            document.getElementById("category").value = "";
        }
    </script>
</body>
</html>
