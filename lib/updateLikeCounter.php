<?PHP
session_start();

include('test_input.php');

if ( !isset($_GET['photoID']) || !isset($_SESSION['username']) || empty($_SESSION['username']) ) {
    die("Forbidden.");
}
echo $_SESSION['username'];

// Connect to Database.
include('../config/DBConnect.php');

// Getting the photo id
$photoID = test_input($_GET["photoID"]);

// Preparing the request.
$sql = "UPDATE " . $DB_NAME . ".". $IMG_TABLE ." SET likes = likes + 1 WHERE photoID = ".$photoID.";";

// Sending the request.
$conn->exec( $sql );

// Disconect from database.
$conn = null;
?>
