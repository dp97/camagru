<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>Settings</TITLE>
        <META charset="UTF-8">
        <META name="viewport" content="width=device-width, initial-scale=1.0" />
        <LINK href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet" />
        <LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/styles.css" />
    </HEAD>
    <BODY>

        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/">Camagru</A>
                <DIV ID="to-right">
                    <A CLASS="tabs" HREF="/am/login.php">Login</A>
                </DIV>
            </NAV>
        </HEADER>

        <!--verify user email.-->
        <?PHP
        include '../config/DBConnect.php';//Connect to database
        include '../lib/test_input.php';

        function err( $msg ) {
            echo "<p>Error verifying your e-mail. '$msg'<br>If this persist, please contact us.</p>";
            $conn = NULL;
            die();
        }

        if ( !isset( $_GET['email'] ) )
            err('no email variable.');
        if ( !isset( $_GET['verify'] ) )
            err('no verification code.');

        $email = test_input( $_GET['email'] );
        $verify = test_input( $_GET['verify'] );

        // Constructing the request.
        $sql = "SELECT hash, state FROM db_main.credentials WHERE email = '$email';";
        // Preparing the request.
        $request = $conn->prepare( $sql );
        // Executing the request.
        $request->execute();
        // Fetching the result.
        $result = $request->fetchAll();

        if ( $result[0]['hash'] == $verify ) {
            if ( $result[0]['state'] == 1 ) {
                err("Account already activated.");
            }
            //Constructing the request.
            $sql = "UPDATE db_main.credentials SET state = 1 WHERE email = '$email';";
            // Executing the request.
            $conn->exec( $sql );

            echo "<p>Success! Now you can log in.</p>";
        } else {
            err('Code does not match.');
        }
        // Disconnecting...
        $conn = NULL;
        ?>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov. All rights reserved.</P>
            </DIV>
        </FOOTER>
    </BODY>
</HTML>
