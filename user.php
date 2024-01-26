<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your E-commerce Site</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="form-toggle">
        <span id="loginTab" class="active-tab">Login</span>
        <span id="signupTab">Sign Up</span>
    </div>

    <div class="form-container" id="loginForm">
        <h2>Login</h2>
        <form action="" method="post">
            <input type="email" id="loginEmail" name="loginEmail" placeholder="Email" required />
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>
    </div>

    <div class="form-container" id="signupForm" style="display: none">
        <h2>Sign Up</h2>
        <form action="" method="post">
            <input type="text" id="firstName" name="firstName" placeholder="First Name" required />
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required />
            <input type="tel" id="phone" name="phone" placeholder="Phone" required />
            <input type="email" id="signupEmail" name="signupEmail" placeholder="Email" required />
            <input type="password" id="signupPassword" name="signupPassword" placeholder="Password" required />
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loginTab = document.getElementById("loginTab");
            const signupTab = document.getElementById("signupTab");
            const loginForm = document.getElementById("loginForm");
            const signupForm = document.getElementById("signupForm");

            loginTab.addEventListener("click", function() {
                loginForm.style.display = "block";
                signupForm.style.display = "none";
                loginTab.classList.add("active-tab");
                signupTab.classList.remove("active-tab");
            });

            signupTab.addEventListener("click", function() {
                loginForm.style.display = "none";
                signupForm.style.display = "block";
                loginTab.classList.remove("active-tab");
                signupTab.classList.add("active-tab");
            });
        });
    </script>
</body>

</html>


<?php
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

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Function to hash the password (for security)
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loginEmail'], $_POST['loginPassword'])) {
        // Login form submitted
        $loginEmail = sanitizeInput($_POST['loginEmail']);
        $loginPassword = sanitizeInput($_POST['loginPassword']);

        // Implement login logic (validate against database, check password, etc.)
        // Fetch user data from the database based on the entered email
        $selectQuery = "SELECT * FROM users WHERE email = '$loginEmail'";
        $result = $conn->query($selectQuery);

        if ($result->num_rows > 0) {
            // User found, check password
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            if (password_verify($loginPassword, $storedPassword)) {
                $_SESSION['current_user'] = $row['user_id'];
                $_SESSION['current_user_name'] = $row['first_name'];
                // Perform further actions after successful login

                // Check if the redirect URL is set in the session
                if (isset($_SESSION['redirect_url'])) {
                    // Get the stored redirect URL
                    $redirect_url = $_SESSION['redirect_url'];

                    // Redirect the user back to the original page
                    header("Location: $redirect_url");
                    exit();
                } else {
                    // If no redirect URL is set, redirect to a default page
                    header("Location: index.php");
                    exit();
                }
            } else {
                echo '<div class="error-message">Invalid password!</div>';
            }
        }
    } elseif (isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['signupEmail'], $_POST['signupPassword'])) {
        // Signup form submitted
        $firstName = sanitizeInput($_POST['firstName']);
        $lastName = sanitizeInput($_POST['lastName']);
        $phone = sanitizeInput($_POST['phone']);
        $signupEmail = sanitizeInput($_POST['signupEmail']);
        $signupPassword = hashPassword($_POST['signupPassword']); // Hash the password before storing

        // Insert user data into the database
        $insertQuery = "INSERT INTO users (first_name, last_name, phone, email, password)
                        VALUES ('$firstName', '$lastName', '$phone', '$signupEmail', '$signupPassword')";

        if ($conn->query($insertQuery) === TRUE) {
            // User registered successfully
            echo '<div class="success-message">User registered successfully!<br>Kindly Login to your Account</div>';
        } else {
            // Error in registration
            $message = "Error: " . $insertQuery . "<br>" . $conn->error;
            $messageClass = "error-message";
        }
    }

    // Close the database connection
    $conn->close();
}
?>