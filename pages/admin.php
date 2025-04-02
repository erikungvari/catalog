<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4">Administrace</h1>
        <p>Vítejte, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
        <h2 class="text-2xl mt-4">Správa produktů</h2>
        <a href="addProduct.php" class="text-blue-500 mt-2 block">Přidat nový produkt</a>
        <a href="adminCategories.php" class="text-blue-500 mt-2 block">Spravovat kategorie</a>
        <a href="index.php" class="text-blue-500 mt-2 block">Zpět na katalog</a>
        <a href="logout.php" class="text-red-500 mt-2 block">Odhlásit se</a>
    </div>
</body>
</html>
