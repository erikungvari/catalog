<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include "../db/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $query = "INSERT INTO products (name, description, price, category_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $description, $price, $category_id]);

    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat produkt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4">Přidat produkt</h1>
        <form method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Název produktu</label>
                <input type="text" name="name" id="name" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Popis produktu</label>
                <textarea name="description" id="description" class="border p-2 rounded w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Cena</label>
                <input type="number" name="price" id="price" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategorie</label>
                <select name="category_id" id="category_id" class="border p-2 rounded w-full">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM categories");
                        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $category['id'] . "'>" . htmlspecialchars($category['name']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option disabled>Chyba při načítání kategorií</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Přidat produkt</button>
        </form>
    </div>
</body>
</html>
