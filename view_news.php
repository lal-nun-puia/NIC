<?php
session_start();
$conn = new mysqli("localhost", "root", "", "news7_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if ID is present
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid news ID.";
    exit();
}

$id = intval($_GET['id']); // sanitize

$sql = "SELECT * FROM articles WHERE id = $id";
$result = $conn->query($sql);

// ✅ Check for query error
if (!$result) {
    echo "❌ Query failed: " . $conn->error;
    exit();
}

// ✅ Check if article exists
if ($result->num_rows == 0) {
    echo "❌ Article not found.";
    exit();
}

$row = $result->fetch_assoc();
?>

<h2><?php echo htmlspecialchars($row['title']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

<?php if (!empty($row['image'])): ?>
    <img src="<?php echo htmlspecialchars($row['image']); ?>" width="300">
<?php endif; ?>

<?php if (!empty($row['video'])): ?>
    <p><a href="<?php echo htmlspecialchars($row['video']); ?>" target="_blank">Watch Video</a></p>
<?php endif; ?>

<a href="news.php">⬅ Back to news</a>