<link rel="stylesheet" type="text/css" href="style.css">

<?php
session_start();
require_once "config.php";

$sql = "SELECT t.theory_id, t.title, t.description, t.content, t.created_at, u.username 
        FROM theory t
        JOIN users u ON t.user_id = u.user_id
        WHERE u.is_admin = 1";  // Only fetch theories from admin users

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Admin Theories</h2>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<div class='theory'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p><strong>By: </strong>" . $row['username'] . "</p>";
            echo "<p><strong>Description: </strong>" . $row['description'] . "</p>";
            echo "<p><strong>Content: </strong>" . $row['content'] . "</p>";
            echo "<p><strong>Posted on: </strong>" . $row['created_at'] . "</p>";
            echo "</div>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>No theories found for admin users.</p>";
    }
} else {
    echo "<p>Error: Could not execute query. " . mysqli_error($link) . "</p>";
}

mysqli_close($link);
?>
