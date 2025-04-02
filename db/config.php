<?php
$host = 'aws-0-eu-central-1.pooler.supabase.com'; 
$dbname = 'postgres';   
$username = 'postgres.djngsmjvxtgvyadrsgdb';    
$password = 'Catalog6996FFd'; 

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba připojení: " . $e->getMessage());
}
?>
