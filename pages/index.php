<?php
session_start(); 
include "../db/config.php";

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? $_SESSION['user']['username'] : '';
$isAdmin = $isLoggedIn && $_SESSION['user']['username'] === 'admin'; 

$order = isset($_GET['order']) ? $_GET['order'] : 'name';
$category = isset($_GET['category']) ? $_GET['category'] : '?';

$query = "SELECT products.id, products.name, products.description, products.price, categories.name AS category FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE categories.id = $category ORDER BY $order ";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog produktů</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Katalog produktů</h1>

        <?php if ($isAdmin): ?>
            <p class="mb-4">Vítejte, <?php echo htmlspecialchars($username); ?>!</p>
            <div class="mb-4">
                <a href="admin.php" class="bg-blue-500 text-white px-4 py-2 rounded">Přejít na administraci</a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Odhlásit se</a>
            </div>
        <?php elseif ($isLoggedIn): ?>
            <div class="mb-4">
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Odhlásit se</a>
            </div>
        <?php else: ?>
            <div class="mb-4">
                <a href="login.php" class="bg-green-500 text-white px-4 py-2 rounded">Přihlásit se</a>
                <a href="register.php" class="bg-green-500 text-white px-4 py-2 rounded">Registrovat se</a>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label for="order" class="font-medium">Řadit podle:</label>
            <select id="order" class="border p-2 rounded" onchange="window.location.href='?order='+this.value">
                <option value="name" <?php echo $order == 'name' ? 'selected' : ''; ?>>Název</option>
                <option value="price" <?php echo $order == 'price' ? 'selected' : ''; ?>>Cena</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($products as $product): ?>
                <div class="border rounded-lg p-4 bg-white shadow">
                    <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="font-bold">Cena: <?php echo $product['price']; ?> Kč</p>
                    <p class="text-sm text-gray-500">Kategorie: <?php echo htmlspecialchars($product['category']); ?></p>
                    <a href="productDetail.php?id=<?php echo $product['id']; ?>" class="text-blue-500 mt-2 block">Zobrazit detail</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
