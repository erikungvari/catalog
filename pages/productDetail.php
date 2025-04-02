<?php
session_start();
include "../db/config.php";

if (!isset($_GET['id'])) {
    die("Produkt nebyl nalezen.");
}

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? $_SESSION['user']['username'] : '';
$isAdmin = $isLoggedIn && $_SESSION['user']['username'] == 'admin';

$product_id = intval($_GET['id']); 

$query = "SELECT p.id, p.name, p.description, p.price, p.category_id, c.name AS category 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produkt nebyl nalezen.");
}

$categoryQuery = "SELECT * FROM categories ORDER BY name";
$categoryStmt = $pdo->query($categoryQuery);
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update']) && $isAdmin) {
    $newName = $_POST['name'];
    $newDescription = $_POST['description'];
    $newPrice = $_POST['price'];
    $newCategory = $_POST['category_id'];

    $updateQuery = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([$newName, $newDescription, $newPrice, $newCategory, $product_id]);

    header("Location: productDetail.php?id=" . $product_id);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) && $isAdmin) {
    $deleteQuery = "DELETE FROM products WHERE id = ?";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->execute([$product_id]);
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="text-lg text-gray-700 mb-2"><?= htmlspecialchars($product['description']) ?></p>
        <p class="text-xl font-bold">Cena: <?= $product['price'] ?> Kč</p>
        <p class="text-md text-gray-500">Kategorie: <?= htmlspecialchars($product['category']) ?></p>

        <?php if ($isAdmin): ?>
            <h2 class="text-2xl font-semibold mt-6">Upravit produkt</h2>
            <form method="POST" class="mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Název</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="border p-2 rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Popis</label>
                    <textarea name="description" class="border p-2 rounded w-full" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cena</label>
                    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" class="border p-2 rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kategorie</label>
                    <select name="category_id" class="border p-2 rounded w-full">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded">Uložit změny</button>
            </form>

            <form method="POST" onsubmit="return confirm('Opravdu chcete tento produkt smazat?');">
                <input type="hidden" name="delete" value="1">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Odstranit produkt</button>
            </form>
        <?php endif; ?>

        <a href="index.php" class="text-blue-500 mt-4 block">Zpět na katalog</a>
    </div>
</body>
</html>
