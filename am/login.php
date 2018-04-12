<?php
// Start the session
session_start();
?>
<HTML>
    <HEAD>
        <TITLE>Log In</TITLE>
        <META charset="UTF-8">
        <META name="viewport" content="width=device-width, initial-scale=1.0">
        <LINK href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet"/>
        <LINK HREF="/stylesheets/index.css" REL="stylesheet"/>
        <LINK HREF="/stylesheets/styles.css" REL="stylesheet"/>
    </HEAD>
    <BODY>
        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/">Camagru</A>
                <DIV ID="to-right">
                    <A class="tabs" HREF="/am/signup.php">Sign Up</A>
                </DIV>
            </NAV>
        </HEADER>

        <SECTION>
            <FORM METHOD="POST">
                <DIV class="container">
                    <H1>Log In Form</H1>
                    <HR>
                    <label for="name"><b>Username:</b></label>
                    <input type="name" name="name" id="name" value placeholder="username" required/>
                    <label for="password"><b>Password:</b><label>
                    <input type="password" name="password" id="password" value placeholder="password" required/>
                    <P style="padding-top: -10px"> Forgot your password? <a class="quiet" href="/am/forgot.php">Reset it</a>.</P>
                    <input type="submit" id="button" value="Log In">
                    <P> Don’t have an account? <a class="quiet" href="/am/signup.php">Sign Up</a>.</P>
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
include('../config/DBConnect.php'); // retrieve conn object
include('../lib/test_input.php'); // Protection against injection

$pass = "";
$user = "";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $user = test_input( $_POST['name'] );
    $pass = md5( test_input( $_POST['password'] ));

    $sql = "SELECT state, username, password FROM " . $DB_NAME . "." . $CRED_TABLE . ";";

    foreach ($conn->query($sql) as $raw) {
        if ($pass == $raw['password'] && $user == $raw['username']) {
            if ( $raw['state'] == 1 ) {
                $_SESSION["username"] = $user;
                echo "<script>window.location = '/main.php';</script>";
            } else {
                echo "Activate your account by clicking the link in the e-mail.";
            }
        }
    }

    $conn = NULL;
}
?>
