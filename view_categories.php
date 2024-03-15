<?php
// Include the database connection script
include_once 'db_connect.php';

// Query to retrieve distinct categories from the database
$sql = "SELECT DISTINCT category FROM recipes";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <style>
    body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa; /* Optional: Change background color */
            font-family: 'Times New Roman', Times, serif;
            background-image: url('dessert7.jpg'); background-size: cover;
        }

        .container {
            text-align: center;
        }

        h1 {
            margin-bottom: 150px;
            background-color: rgb(43, 35, 75);
            color: #ddd;
            width: 100%;
            height: 70px;
            padding: 7px;
        }

        .btn {
            display: inline-block;
            margin-bottom: 50px;
            background-size: cover;
            background-position: center;
            padding: 15px 30px;
            background-color: rgb(43, 35, 75);
        }

        section {
            padding: 10px;

        }
        
        .btn {
            width: 200px;

        }
        .recipe-category {
			font-family:times-new-roman;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .recipe-category-item {
			font-family:times-new-roman;
            width: 48%;
            margin-bottom: 20px;
            border: solid #142649;
           
            border-radius: 4px;
            overflow: hidden;
        }

        .recipe-category-item img {
            width: 100%;
            height: auto;
        }

        .recipe-category-item h3 {
            padding: 10px;
            margin: 0;
            color: wheat;
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
    <div class="container">
        <br><br>
        <?php
        // Start the session
        session_start();

        // Determine the URL to redirect to based on the user's role
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $redirect_url = 'admin.php';
        } else {
            $redirect_url = 'index.php';
        }
        ?>
        <!-- Close button with onclick event for redirection -->
        <span class="close" onclick="window.location.href='<?php echo $redirect_url; ?>'">&times;</span><br>
        <h1>Recipe Category</h1>
        <section>
            <div class="recipe-category">
                <div class="recipe-category-item">
                    <img src="image4.jpg" alt="Non-veg"></a>
                    <a href="view_recipes.php?category=nonveg" class="btn btn-primary">Non-Veg</a>
                </div>
                <div class="recipe-category-item">
                    <img src="image3.jpg" alt="Veg">
                    <a href="view_recipes.php?category=veg" class="btn btn-primary">Veg</a>
                </div>
            <!-- Add more category items as needed -->
            </div>
        </section>
            <?php
            // Display dynamically retrieved categories as buttons
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $category = strtolower($row["category"]);
                    // Skip the hardcoded "Veg" and "Non-Veg" categories
                    if ($category !== "veg" && $category !== "nonveg") {
                        echo '<a href="view_recipes.php?category=' . $category . '" class="btn btn-primary mr-2">' . ucfirst($category) . '</a>';
                    }
                }
            } else {
                echo "No categories found.";
            }
            ?>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
