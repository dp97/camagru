<?PHP
// Start the session.
session_start();

// Checking if user is logged in
if ( !isset($_SESSION['username']) ) {
    echo "<script>window.location = '/am/login.php';</script>";
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Security functions.
    require '../lib/test_input.php';

    // Connect to database.
    require '../config/DBConnect.php';

    // Securing the comment for xss.
    $cmnt = test_input( $_POST["comment"] );
    $photoID = test_input( $_POST["photoID"] );
    $owner = test_input( $_POST["owner"] );

    // Preparing the requests.
    $sql1 = "INSERT INTO db_main.comments (comment, username, photoID) VALUES ( '$cmnt', '". $_SESSION['username'] ."', '$photoID' );";

    $sql2 = "UPDATE ". $DB_NAME .".". $IMG_TABLE ." SET c_comments = c_comments + 1 WHERE photoID = '$photoID';";

    $sql3 = "SELECT email, notifications FROM db_main.credentials WHERE username = '$owner';";
    $notify = $conn->prepare( $sql3 );
    
    // Uploading comment to Database.
    $conn->exec( $sql1 );
    $conn->exec( $sql2 );
    $notify->execute();

    // Get result.
    $notify_state = $notify->fetchAll();

    // Disconnecting.
    $conn = null;

    // SEND NOTIFICATION email.
    if ($notify_state[0]['notifications'] == "on") {
        $email = $notify_state[0]['email'];
        $msg = "User ". $_SESSION['username'] ." just commented on your photo:\n\n". $cmnt;
        $header = "From:no-reply@camagru.com";

        // sending mail....
        mail( $email, "Camagru", $msg, $header );
    }
}

// redirect to gellery page...
header('location: /gallery.php');
?>
