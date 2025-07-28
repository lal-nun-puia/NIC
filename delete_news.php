<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: news.php?error=access_denied");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "news7_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get image path
    $stmt = $conn->prepare("SELECT image FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image'];

        // Delete image file if it exists and is safe
        if (!empty($imagePath) && file_exists($imagePath) && strpos(realpath($imagePath), realpath(__DIR__)) === 0) {
            unlink($imagePath);
        }

        // Delete the article
        $deleteStmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
    }

    header("Location: news.php?success=deleted");
    exit();
} else {
    echo "Invalid request.";
}
?>