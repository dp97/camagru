<?PHP
require('database.php');

$DB_NAME = "db_main";
$IMG_TABLE = "images";
$CRED_TABLE = "credentials";

// Set the connection to database.
try {
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>