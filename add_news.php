<?php
session_start();

// ✅ Allow only admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

// ✅ Connect to database
$conn = new mysqli("localhost", "root", "", "news7_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $video = $conn->real_escape_string($_POST['video'] ?? '');
    $author_id = $_SESSION['user']['id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;

    // ✅ Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . time() . "_" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo "❌ Failed to upload image.";
            exit();
        }
    }

    // ✅ Insert into database
    $stmt = $conn->prepare("INSERT INTO articles (title, content, image, video, author_id, category_id) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        echo "❌ Prepare failed: " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssssii", $title, $content, $imagePath, $video, $author_id, $category_id);

    if ($stmt->execute()) {
        echo "✅ News added successfully. <a href='news.php'>Go back to News</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // ✅ Fetch categories for dropdown
    $categories = $conn->query("SELECT id, name FROM categories");
?>

<!-- ✅ Form for adding news -->
<form action="add_news.php" method="post" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="5" cols="40" required></textarea><br><br>

    <label>Image (JPG/PNG):</label><br>
    <input type="file" name="image" accept=".jpg, .jpeg, .png"><br><br>

    <label>YouTube Video URL (optional):</label><br>
    <input type="url" name="video" placeholder="https://www.youtube.com/watch?v=..."><br><br>

    <label>Category:</label><br>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php while ($cat = $categories->fetch_assoc()) : ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <input type="submit" value="Add News">
</form>

<?php } ?>