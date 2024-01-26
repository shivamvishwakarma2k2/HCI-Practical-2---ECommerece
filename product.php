<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />

    <title>Genz Clothings</title>
    <style></style>
</head>

<body>
    <header class="header-bar">
        <div class="header-content">
            <div class="company-name">GenZ Collections</div>
            <nav class="nav-bar">
                <ul class="nav-options">
                    <li><a href="#">Men</a></li>
                    <li><a href="#">Women</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-right">
            <input type="text" class="search-bar" placeholder="Search..." />
            <div class="user-account-icon">
                <?php
                if (!isset($_SESSION['current_user'])) {
                    // Store the current page URL in the session
                    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
                    echo '<a href="user.php">Login</a>';
                } else {
                    // Database connection details
                    $host = 'localhost';
                    $username = 'root';
                    $password = '';
                    $database = 'genz_collections';

                    // Create a connection
                    $conn = new mysqli($host, $username, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $user_id = (int)$_SESSION['current_user'];


                    // Fetch user data from the database based on the entered email
                    $selectQuery = "SELECT first_name FROM users WHERE user_id = $user_id";
                    $result = $conn->query($selectQuery);

                    // Fetch the result
                    $row = $result->fetch_assoc();

                    // Check if a row is found
                    if ($row) {
                        // Now, $row['first_name'] contains the first name of the user with the given ID
                        echo '<a href="user.php">' . $row['first_name'] . '</a>';
                    } else {
                        echo '<a href="user.php">Login</a>';
                    }

                    // Free the result set
                    $result->free_result();

                    // Close the connection
                    $conn->close();
                }
                ?>
            </div>
            <form accept="" method="post">
                <button type="submit" name="logout_button" class="logout"><img src="img/logout.png" class="icon-png" /></button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout_button'])) {

                // Unset all session variables
                session_unset();
                // Destroy the session
                session_destroy();

                // Redirect to another page
                header("Location: index.php");
                exit();
            }

            ?>
        </div>
        </div>
    </header>

    <div class="product-card-details">
        <div class="product-details-container">
            <div class="product-image-panel">
                <!-- Assuming you have four sample product images -->
                <img class="product-image" src="img/unisex hoodies.jpg" alt="Product 1" />
                <img class="product-image" src="img/hoodie 2.jpg" alt="Product 2" />
                <img class="product-image" src="img/hoodie 3.jpg" alt="Product 3" />
                <img class="product-image" src="img/hoodie 4.jpg" alt="Product 4" />
            </div>
            <div class="product-details-panel">
                <h2>Women's Grey Oversized Hoodies</h2>
                <ul class="product-details-list">
                    <li>Price - Rs499</li>
                    <li>Seller - Changu Kapda Bandar</li>
                    <li>Country of Origin of - India</li>
                    <li>Material - Polyester Blend</li>
                    <li>Size - M/L/XL</li>
                    <li>Color -</li>
                </ul>
                <form action="" method="post">
                    <input type="hidden" name="product_id" value="101">
                    <button type="submit" class="buy-button" value="1">Buy Now</button>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if ($_SESSION['current_user'] === null) {
                        header("Location: user.php");
                    } else {
                        // Database connection details
                        $host = 'localhost';
                        $username = 'root';
                        $password = '';
                        $database = 'genz_collections';

                        // Create a connection
                        $conn = new mysqli($host, $username, $password, $database);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Get product_id from the form submission
                        $product_id = (int)$_POST['product_id'];

                        // Get user_id from the session
                        $user_id = $_SESSION['current_user'];

                        // Insert order into the orders table
                        $insertOrderQuery = "INSERT INTO orders (user_id, product_id) VALUES ($user_id, $product_id)";

                        // Perform the insertion
                        if ($conn->query($insertOrderQuery) === TRUE) {

                            // Prepare the SQL statement
                            $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");

                            // Bind the parameter
                            $stmt->bind_param("i", $user_id); // "i" stands for integer, change it if user_id is not an integer

                            // Execute the statement
                            $stmt->execute();

                            // Bind the result variables
                            $stmt->bind_result($first_name, $last_name);

                            // Fetch the result
                            $stmt->fetch();

                            // Order insertion successful
                            echo '<div class="confirmation-message">Your Order is Placed<br>Dear, ' . $first_name . ' ' . $last_name . '</div>';

                            // Close the statement and the connection
                            $stmt->close();
                            $conn->close();
                        } else {
                            // Error in order insertion
                            echo '<div class="error-message">Your Order is Not Placed</div>';
                        }
                    }
                }
                ?>


            </div>
        </div>
    </div>
</body>

</html>