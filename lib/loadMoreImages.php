<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    loadMoreImages($_GET['from']);
}


function loadMoreImages($id) {
    // Connect to Database.
    include('../config/DBConnect.php');

    // Logged user.
    $current_user = NULL;
    if ( isset($_SESSION['username']) ) {
        $current_user = $_SESSION['username'];
    }

    // Make request to upload image to Database.
    $sql = "SELECT photoID, image, owner, likes, c_comments
            FROM db_main.images
            WHERE photoID > $id
            LIMIT 6;";
    // Preparing the request.
    $state = $conn->prepare( $sql );
    // Executing the request.
    $state->execute();
    // Fetching the results...
    $result = $state->fetchAll();
    // Disconnecting...
    $conn = null;

    echo json_encode( $result );
}
?>
