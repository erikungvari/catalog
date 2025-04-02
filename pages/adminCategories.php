<?php
session_start();
include "../db/config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $checkQuery = "SELECT COUNT(*) FROM categories WHERE name = ?";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([$category_name]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $error = "Kategorie již existuje!";
        } else {
            $query = "INSERT INTO categories (name) VALUES (?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$category_name]);
            header("Location: adminCategories.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
    $category_id = intval($_POST['category_id']);
    $new_name = trim($_POST['new_category_name']);
    if (!empty($new_name)) {
        $checkQuery = "SELECT COUNT(*) FROM categories WHERE name = ? AND id != ?";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([$new_name, $category_id]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $error = "Tento název kategorie už existuje!";
        } else {
            $query = "UPDATE categories SET name = ? WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$new_name, $category_id]);
            header("Location: adminCategories.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
    $category_id = intval($_POST['category_id']);
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$category_id]);
    header("Location: adminCategories.php");
    exit();
}

$query = "SELECT * FROM categories ORDER BY name";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Správa kategorií</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4">Správa kategorií</h1>

        <?php if (!empty($error)): ?>
            <p class="bg-red-500 text-white p-2 rounded mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Nová kategorie:</label>
            <input type="text" name="category_name" class="border p-2 rounded w-full" required>
            <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Přidat</button>
        </form>

        <h2 class="text-2xl font-semibold mt-4 mb-2">Existující kategorie</h2>
        <ul class="space-y-2">
            <?php foreach ($categories as $category): ?>
                <li class="bg-gray-50 p-4 rounded shadow flex justify-between items-center">
                    <span><?= htmlspecialchars($category['name']) ?></span>

                    <div class="flex gap-2">
                        <form method="POST" class="flex items-center gap-2">
                            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                            <input type="text" name="new_category_name" placeholder="Nový název" class="border p-1 rounded">
                            <button type="submit" name="edit_category" class="bg-yellow-500 text-white px-3 py-1 rounded">Upravit</button>
                        </form>

                        <form method="POST" onsubmit="return confirm('Opravdu chcete tuto kategorii smazat?');">
                            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                            <button type="submit" name="delete_category" class="bg-red-500 text-white px-3 py-1 rounded">Smazat</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="admin.php" class="text-blue-500 mt-4 block">Zpět na administraci</a>
    </div>
</body>
</html>
