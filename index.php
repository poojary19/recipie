<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="section">
   
        <div class="section1">
            <div class="header">
                <h1>Welcome to Recipe Website</h1>
            </div>
            <div class="menu-button">
                <button class="btn btn-primary oval-btn" onclick="toggleMenu()">Menu</button>
                <div class="menu-options" id="menuOptions">
                    <a href="about_us.html">About Us</a>
                    <a href="contact.html">Contact</a>
                    <a href="feedback.php">Feedback</a>
                    <a href="review.php">Review</a> <!-- Added review option -->
                    <!-- Check if user is logged in as admin, then show the logout button -->
                    <?php
                    session_start(); // Start the session
                    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Admin Login</a> <!-- New login option -->
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="section2">
            <!-- Overlay for blur effect -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
            <script src="script.js"></script>
        </div>

        <div class="section3">
            <div class="container">
                <div class="button-container">
                    <a href="view_categories.php" class="btn btn-primary">View Categories</a>
                    <a href="add_recipe.php" class="btn btn-success">Add Recipe</a>
                </div>
            </div>
        </div>

        <div class="section4">
            <footer>
                <p>©RECIPE LIST. All rights reserved</p>
            </footer>
        </div>
    </div>

    <script>
        function toggleMenu() {
        var menuOptions = document.querySelector('.menu-options');
        menuOptions.style.display = (menuOptions.style.display === 'block') ? 'none' : 'block';
    }

        window.onclick = function(event) {
            if (!event.target.matches('.oval-btn')) {
                var menuOptions = document.getElementById("menuOptions");
                if (menuOptions.classList.contains('show')) {
                    menuOptions.classList.remove('show');
                }
            }
        }
    </script>

</body>
</html>
