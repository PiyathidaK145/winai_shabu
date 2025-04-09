<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

// Ensure role_id is passed in the URL
if (isset($_GET['role_id'])) {
    $roleId = intval($_GET['role_id']); // Safely cast to an integer

    // Create the MySQL connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL DELETE statement
    $stmt = $conn->prepare("DELETE FROM role WHERE role_id = ?");
    
    // Bind the role_id to the prepared statement
    $stmt->bind_param("i", $roleId); // "i" for integer type

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the roles page after successful deletion
        header("Location: Role_employee.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Role ID is missing.";
}
?>