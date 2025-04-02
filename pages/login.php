<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "../db/config.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
    } else {
        $error = "Neplatné uživatelské jméno nebo heslo!";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-sm mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Přihlášení</h1>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Uživatelské jméno</label>
                <input type="text" name="username" id="username" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                <input type="password" name="password" id="password" class="border p-2 rounded w-full" required>
                <a href="register.php" class="text-blue-500 mt-2 block">Registrovat se</a>
                <a href="index.php" class="text-blue-500 mt-2 block">Prohlížet</a>
            </div>
            <?php if (isset($error)) { echo '<p class="text-red-500 text-sm">' . $error . '</p>'; } ?>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Přihlásit se</button>
        </form>
    </div>
</body>
</html>
