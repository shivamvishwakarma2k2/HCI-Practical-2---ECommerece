<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <title>Genz Clothings</title>
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
    </header>

    <div class="cover-section">
        <div class="cover-images-container">
            <div class="cover-image" id="image1">
                <img src="img/female kurta.jpg" alt="Kurta's" />
                <div class="overlay">
                    <p>Classy<br />Traditional<br />Kurta's</p>
                </div>
            </div>

            <div class="cover-image" id="image1">
                <img src="img/men white tshirt.jpg" alt="Mens T-Shirt" />
                <div class="overlay">
                    <p>Men's<br />Plain<br />T-Shirt's</p>
                </div>
            </div>

            <div class="cover-image" id="image1">
                <img src="img/women red tshirt.jpg" alt="Women's T-Shirts" />
                <div class="overlay">
                    <p>Stylish <br />Women's<br />T-Shirts</p>
                </div>
            </div>
        </div>

        <div class="discounted-offer">
            <p>
                Special Offer Up to 50% OFF on Selected Items!<br />
                Hurry up!
            </p>
        </div>
    </div>

    <div class="product-page">
        <div class="filter-panel">
            <h2>Filter Options</h2>
            <div class="filter-group">
                <label>
                    Men <input type="checkbox" name="category" value="men" />
                </label>
                <label>
                    Women<input type="checkbox" name="category" value="women" />
                </label>
            </div>

            <div class="filter-group">
                <label>
                    <span>Size:</span>
                    <select name="size">
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </select>
                </label>
            </div>
            <div class="filter-group">
                <label>
                    <span>Color:</span>
                    <select name="color">
                        <option value="red">Red</option>
                        <option value="blue">Blue</option>
                        <option value="green">Green</option>
                    </select>
                </label>
            </div>
            <div class="filter-group">
                <label>
                    <span>Price Range:</span>
                    <input type="range" name="price" min="0" max="100" step="10" value="50" />
                    <span class="price-value">$50</span>
                </label>
            </div>
        </div>

        <div class="product-listing-panel">
            <div class="product-card">
                <img src="img/unisex hoodies.jpg" alt="Hoodie's" />
                <div class="product-details">
                    <div class="product-name">Unisex Hoodie</div>
                    <div class="product-price">499 Rs</div>
                    <button class="view-details-btn" onclick="redirectToProductPage()">
                        View Product
                    </button>
                </div>
            </div>

            <div class="product-card">
                <img src="img/men white tshirt.jpg" alt="Product 2" />
                <div class="product-details">
                    <div class="product-name">Men's White T-shirt</div>
                    <div class="product-price">299 Rs</div>
                    <button class="view-details-btn">View Product</button>
                </div>
            </div>

            <div class="product-card">
                <img src="img/unisex jeans.jpg" alt="Jeans's" />
                <div class="product-details">
                    <div class="product-name">Jeans (M/F)</div>
                    <div class="product-price">499 Rs</div>
                    <button class="view-details-btn">View Product</button>
                </div>
            </div>

            <div class="product-card">
                <img src="img/mens jacket.jpg" alt="Jacket" />
                <div class="product-details">
                    <div class="product-name">Jacket</div>
                    <div class="product-price">999 Rs</div>
                    <button class="view-details-btn">View Product</button>
                </div>
            </div>

            <div class="product-card">
                <img src="img/men shirt.jpg" alt="Women's Dress" />
                <div class="product-details">
                    <div class="product-name">Shirt</div>
                    <div class="product-price">399 Rs</div>
                    <button class="view-details-btn">View Product</button>
                </div>
            </div>

            <div class="product-card">
                <img src="img/women dress.jpg" alt="Women's Dress" />
                <div class="product-details">
                    <div class="product-name">Dress</div>
                    <div class="product-price">699 Rs</div>
                    <button class="view-details-btn">View Product</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function redirectToProductPage() {
        var productPageUrl = "product.php";
        window.location.href = productPageUrl;
    }
</script>

</html>