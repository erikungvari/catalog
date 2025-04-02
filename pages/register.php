<?php
session_start();
include "../db/config.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $errors[] = "Všechna pole musí být vyplněna.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Hesla se neshodují.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$username, $hashedPassword])) {
            $_SESSION['user'] = ['username' => $username];
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Registrace selhala. Zkuste to znovu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Registrace</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 p-4 mb-4 rounded">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Uživatelské jméno</label>
                <input type="text" name="username" id="username" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                <input type="password" name="password" id="password" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Potvrzení hesla</label>
                <input type="password" name="confirm_password" id="confirm_password" class="border p-2 rounded w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Registrovat</button>
        </form>

        <p class="mt-4 text-center">
            Už máte účet? <a href="login.php" class="text-blue-500">Přihlaste se zde</a>
        </p>
    </div>
</body>
</html>
