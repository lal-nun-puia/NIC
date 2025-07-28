<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "news7_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's role
$role = $_SESSION['user']['role'] ?? null;

// Get selected category from URL
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch categories
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);

// Fetch articles
if ($selected_category > 0) {
    $sql = "SELECT articles.*, categories.name AS category_name 
            FROM articles 
            LEFT JOIN categories ON articles.category_id = categories.id 
            WHERE articles.category_id = $selected_category 
            ORDER BY created_at DESC";
} else {
    $sql = "SELECT articles.*, categories.name AS category_name 
            FROM articles 
            LEFT JOIN categories ON articles.category_id = categories.id 
            ORDER BY created_at DESC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>News</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 0; display: flex; }
        .main { width: 75%; padding: 20px; }
        .sidebar { width: 25%; background: #f0f0f0; padding: 20px; }
        .news-card { border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px; }
        .news-card img { width: 100%; height: auto; max-height: 200px; object-fit: cover; }
        .news-card h3 { margin-top: 0; }
        a.news-link {
            text-decoration: none;
            color: black;
        }
        a.news-link:hover {
            color: #0077cc;
        }
        .admin-actions a {
            display: inline-block;
            background: crimson;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            margin-top: 10px;
            border-radius: 4px;
        }
        .add-news-btn {
            display: inline-block;
            margin-bottom: 20px;
            background: green;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="main">
    <?php if ($role === 'admin'): ?>
        <a class="add-news-btn" href="add_news.php">➕ Add News</a>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="news-card">
                <h3>
                    <a href="view_news.php?id=<?= $row['id'] ?>" class="news-link">
                        <?= htmlspecialchars($row['title']) ?>
                    </a>
                </h3>
                <?php if (!empty($row['image'])): ?>
                    <a href="view_news.php?id=<?= $row['id'] ?>">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="News Image">
                    </a>
                <?php endif; ?>
                <p>
                    <strong>Category:</strong> <?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?>
                </p>
                <p>
                    <?= substr(htmlspecialchars($row['content']), 0, 100) ?>...
                    <a href="view_news.php?id=<?= $row['id'] ?>">Read more</a>
                </p>

                <?php if ($role === 'admin'): ?>
                    <div class="admin-actions">
                        <a href="delete_news.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Are you sure you want to delete this article?');">
                           🗑 Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No articles found.</p>
    <?php endif; ?>
</div>

<div class="sidebar">
    <?php if (isset($_SESSION['user'])): ?>
    <a href="logout.php" style="display:inline-block; margin-bottom:15px; background:#333; color:#fff; padding:8px 12px; text-decoration:none; border-radius:4px;">
        🔓 Logout
    </a>
<?php endif; ?>
    <h3>Categories</h3>
    <ul>
        <li><a href="news.php">All</a></li>
        <?php if ($category_result && $category_result->num_rows > 0): ?>
            <?php while ($cat = $category_result->fetch_assoc()): ?>
                <li>
                    <a href="news.php?category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No categories</li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>

<?php $conn->close(); ?>