<?PHP
// Starting the session.
session_start();

include('test_input.php');

// Checking requires variable.
if ( !isset( $_GET["photoID"] ) )
    die('No photoID.');
    
// checking if user is logged in.
if ( !isset( $_SESSION['username'] ) )
    die("User not logged in.");
    
// Connect to Database.
include '../config/DBConnect.php' ;

// Getting the photo id
$photoID = test_input($_GET["photoID"]);

// First delete all depending comments!!
$sql = "DELETE FROM db_main.comments WHERE photoID = '$photoID';";

// Sending the request.
$conn->exec( $sql );

// THEN Deleting the image....
$sql = "DELETE FROM db_main.images WHERE photoID = '$photoID';";

// Sending the request.
$conn->exec( $sql );

// Disconnecting from database.
$conn = null;
?>