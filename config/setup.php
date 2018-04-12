<?PHP
require('database.php');

$DB_NAME = "db_main";
$IMG_TABLE = "images";
$CRED_TABLE = "credentials";
$CMNT_TABLE = "comments";

$create = "CREATE DATABASE IF NOT EXISTS " . $DB_NAME;
$credential_table = "
CREATE TABLE IF NOT EXISTS " . $DB_NAME . "." . $CRED_TABLE . " (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    state TINYINT(2) NOT NULL DEFAULT 0,
    hash VARCHAR(32) NOT NULL,
    notifications CHAR(3) NOT NULL DEFAULT 'on',
    username VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(128) NOT NULL,
    reg_date TIMESTAMP
);";
$photo_table = "
CREATE TABLE IF NOT EXISTS " . $DB_NAME . "." . $IMG_TABLE . " (
    photoID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image LONGBLOB NOT NULL,
    owner VARCHAR(255) NOT NULL,
    c_comments INT(6) DEFAULT 0,
    likes INT(6) DEFAULT 0,
    date TIMESTAMP
);";
$comment_table = "
CREATE TABLE IF NOT EXISTS " . $DB_NAME . "." . $CMNT_TABLE ." (
    commentID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    comment TEXT NOT NULL,
    username VARCHAR(255),
    photoID INT(6) UNSIGNED,
    FOREIGN KEY (photoID) REFERENCES ". $DB_NAME . "." . $IMG_TABLE ."(photoID)
);";

try {
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # Creating The DataBase with Credentials
    $conn->exec($create);
        
    # Creating Table with credentials
    $conn->exec($credential_table);
    
    # Creating Photo Table
    $conn->exec($photo_table);
    
    # Creating Comment Table
    $conn->exec($comment_table);
    
    }
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>