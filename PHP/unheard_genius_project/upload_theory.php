<?php
session_start();

// Check if the user is logged in; if not, redirect to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
?>
<?php


$title = $description = $category = "";
$success_msg = $error_msg = "";

// Handle submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $category = $_POST["category"];

    if (!empty($title) && !empty($description) && !empty($category)) {
        $user_id = $_SESSION["user_id"];

        $sql = "INSERT INTO theory (title, description, category_id, user_id) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssii", $title, $description, $category, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Theory uploaded successfully.";
                $title = $description = "";
            } else {
                $error_msg = "Error uploading theory.";
            }
        }
    } else {
        $error_msg = "Please fill in all fields.";
    }
}

// Get categories
$categories = [];
$cat_sql = "SELECT * FROM category";
$cat_result = mysqli_query($link, $cat_sql);
if ($cat_result && mysqli_num_rows($cat_result) > 0) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Theory</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        form { background: #fff; padding: 20px; border-radius: 12px; width: 500px; margin: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 10px; }
        .message { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Upload New Theory</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
    <br><br>

    <form action="upload_theory.php" method="post">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">

        <label>Description:</label>
        <textarea name="description" rows="5"><?php echo htmlspecialchars($description); ?></textarea>

        <label>Category:</label>
        <select name="category">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Upload Theory">
        <p class="message"><?php echo $success_msg; ?></p>
        <p class="error"><?php echo $error_msg; ?></p>
    </form>
</body>
</html>
