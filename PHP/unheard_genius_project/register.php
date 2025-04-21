<link rel="stylesheet" type="text/css" href="r.css">

<?php
require_once "config.php";

$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT user_id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            }
        }
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    }

    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            $param_username = $username;
            $param_email = $email;
            $param_password = $password;
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php?msg=Registration successful. Please login.");
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<form method="post" action="">
    <h2>Register Here</h2>
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $username; ?>">
    <span><?php echo $username_err; ?></span>
    <br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $email; ?>">
    <span><?php echo $email_err; ?></span>
    <br>
    <label>Password:</label>
    <input type="password" name="password">
    <span><?php echo $password_err; ?></span>
    <br>
    <input type="submit" value="Register">
    <a href="login.php">Already have an account? Login</a>
</form>