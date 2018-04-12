<?PHP
//Starting session.
session_start();

// Simple security check.
if (!isset($_SESSION["username"])) {
    header('Location: /index.php');
}

require 'test_input.php';

// Updating notifications preferences of user.
if ( isset( $_GET['notify'] ) ) {
    if ( empty( $_GET['notify'] ) ) {
        die('e');
    }
    updateData( "notifications", test_input( $_GET['notify'] ) );
}
else if ( isset( $_GET['nun'] ) ) {
    if ( empty( $_GET['nun'] ) ) {
        die('e');// empty
    } else if ( checkIfUniqueUsrname( test_input( $_GET['nun'] )) == false ) {
        die("nuu");// not unique username
    }
    
    updateData( "username", test_input( $_GET['nun'] ) );
    
    // Updating Session variable as well.
    $_SESSION['username'] = $value;
}
else if ( isset( $_GET['nem'] ) ) {
    if ( empty( $_GET['nem'] ) ) {
        die('e');
    }
    updateData( "email", test_input( $_GET['nem'] ) );
}
else if ( isset( $_GET['np'] ) && isset( $_GET['npc'] ) ) {
    $np = test_input( $_GET['np'] );
    $npc = test_input( $_GET['npc'] );
 
    if ( strlen( $np ) < 6 ) {
        echo "pts";// password too short
    }
    else if ( $np == $npc ) {
        updateData( "password", $np );
    } else {
        echo "pnm";// passwords does not match
    }
}

function updateData( $column, $value ) {
    // Connect to DB.
    require '../config/DBConnect.php';
    
    // First check if DB $value is the same
    $sql = "SELECT ". $column ." FROM db_main.credentials WHERE username = '". $_SESSION['username'] ."';";
    
    // prepare...
    $req = $conn->prepare( $sql );
    
    // Executing...
    $req->execute();
    
    // fetching...
    $result = $req->fetchAll();
    
    // Comparing values...
    if ( $value == $result[0][$column] ) {
        $conn = NULL;
        die("sv");// same value
    }
    
    // Preparing the request.
    $sql = "UPDATE db_main.credentials SET ".$column." = '$value' WHERE username = '". $_SESSION['username'] ."';";
    
    // Sending the request.
    $conn->exec( $sql );
    
    // Disconnecting..
    $conn = NULL;
    
    echo 'success';
}

function checkIfUniqueUsrname( $usrnm ) {
    // Connect to DB.
    require '../config/DBConnect.php';
    
    // Preparing the request.
    $sql = "SELECT username FROM db_main.credentials WHERE username = '$usrnm';";
    
    // Preparing the request.
    $state = $conn->prepare( $sql );
    
    // Executing the request.
    $state->execute();
    
    // Fetching the result...
    $result = $state->fetchAll();
    
    // Disconnecting..
    $conn = NULL;
    
    // Checking...
    if ( empty( $result ) ) {
        return true;
    }
    return false;
}
?>