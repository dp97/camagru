<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>Sign Up</TITLE>
        <META charset="UTF-8">
        <META name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet">
        <LINK HREF="/stylesheets/index.css" REL="stylesheet"/>
        <LINK HREF="/stylesheets/styles.css" REL="stylesheet"/>
    </HEAD>
    <BODY>
        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/">Camagru</A>
                <DIV ID="to-right">
                    <A class="tabs" HREF="/am/login.php">Log In</A>
                </DIV>
            </NAV>
        </HEADER>
        <SECTION>
            <FORM METHOD="POST">
                <DIV class="container">
                    <H1>Sign Up Form</H1>
                    <HR>
                    <label for="username"><b>Userame:</b></label>
                    <input type="name" name="name" id="name" value placeholder="Enter username" required/>
                    <label for="email"><b>E-mail:</b></label>
                    <input type="email" name="email" id="email" value placeholder="Enter email" required/>
                    <label for="password"><b>Password:</b><label>
                    <input type="password" name="password" id="password" value pattern=".{6,}" placeholder="Enter Password" title="Password should be 6 characters long!" required/>
                    <label for="conf_password"><b>Confirm Password:</b><label>
                    <input type="password" name="conf_password" id="conf_password" value placeholder="Confirm Password" required/>
                    <input type="submit" id="button" value="Sign Up">
                    <P class="quiet"> Already have an account? <a class="quiet" href="/am/login.php">Log In</a>.</P>
                </DIV>
            </FORM>
        </SECTION>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov, Inc. All rights reserved.</P>
            </DIV>
        </FOOTER>
    </BODY>
</HTML>
<?PHP
include('../config/DBConnect.php'); // Connect to DataBase
include('../lib/test_input.php'); // Protection against injection

function myerr( $msg ) {
    echo $msg;
    $conn = NULL;
    die();
}

function check_email( $needle, $db) {
    $sql = "SELECT email FROM db_main.credentials;";

    foreach ($db->query( $sql ) as $row) {
        if ($needle === $row['email'])
            return true;
    }
    return false;
}

$emailErr = $passErr = $conf_passErr = "";
$email = $name = $pass = $conf_pass = "";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $name = test_input( $_POST["name"] );
    $email = test_input( $_POST["email"] );
    $pass = test_input( $_POST["password"] );
    $conf_pass = test_input( $_POST["conf_password"] );
    $code = md5( rand( 0, 1000 ) );

    if ( checkIfUniqueUsrname($name) == false) {
        $conn = NULL;
        die('username already taken.');
    }
    if ( check_email( $email, $conn ) )
        myerr("This email is already in use.");
    if ( $pass !== $conf_pass )
        myerr("Passwords doesn't match.");

    // queue to register an user
    $sql = "INSERT INTO db_main.credentials (hash, username, email, password) VALUES ( '$code', '$name', '$email', '". md5( $pass ) ."' );";

    $conn->exec( $sql );
    $conn = NULL;

    // Sending a mail.

    $verifyURL = "http://" . $_SERVER['SERVER_NAME'] . ":8080/am/verify.php?email=$email&verify=$code";

    $msg = "Thanks for signing up!\nTo activate your account click the link below!\n<a href='$verifyURL'>Activate my account!</a>";
    $header = "From:no-reply@camagru.com";

    if (mail( $email, "Camagru", $msg, $header ) == false) {
        echo '------------------------------';
    }

    echo "<script>window.location = '/am/login.php';</script>";
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
