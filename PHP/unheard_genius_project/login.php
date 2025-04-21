
<?php
session_start();
require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if there are no validation errors
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement to check user credentials
        $sql = "SELECT user_id, username, password, is_admin FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind the username to the prepared statement
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result to check if username exists
                mysqli_stmt_store_result($stmt);

                // Check if the username exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $is_admin);

                    // Fetch the result
                    if (mysqli_stmt_fetch($stmt)) {
                        // Check if password is correct
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["is_admin"] = $is_admin;

                            // Redirect user to the dashboard page
                            header("location: dashboard.php");
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Login</h2>
    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo $username; ?>">
    <span><?php echo $username_err; ?></span>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password">
    <span><?php echo $password_err; ?></span>
    <br>
    <input type="submit" value="Login">
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</form>


</body>
</html>
