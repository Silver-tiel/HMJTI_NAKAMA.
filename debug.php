<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Info</h1>";
echo "PHP Version: " . phpversion() . "<br>";

try {
    require_once 'classes/Database.php';
    echo "Database.php loaded successfully.<br>";
    $db = new Database();
    echo "Database connected successfully.<br>";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "Fatal Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "<br>";
}

try {
    require_once 'classes/Auth.php';
    echo "Auth.php loaded successfully.<br>";
    $auth = new Auth($db->conn);
    echo "Auth instantiated successfully.<br>";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "Fatal Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "<br>";
}

echo "<h3>If you see this, the basic classes are working!</h3>";
?>
