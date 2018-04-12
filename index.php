<?PHP
session_start();

// Initiate DB
include 'config/setup.php';

// Simple security check.
if ( isset($_SESSION["username"]) ) {
    header('Location: main.php');
}
?>
<HTML>
    <HEAD>
        <TITLE>Camagru</TITLE>
        <META charset="UTF-8">
        <META name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet"/>
        <LINK TYPE="text/css" REL="stylesheet" HREF="/stylesheets/styles.css" />
    </HEAD>
    <BODY>
    <!-- HEADER -->
        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/index.html">Camagru</A>
            </NAV>
        </HEADER>
        
        <H1 ID="wellcome" >Welcome Guest!</H1>
            <A CLASS="tabs register-button" HREF="/am/signup.php" ><b>Register</b></A>
            <A CLASS="tabs login-button" HREF="/am/login.php" >Login</A>
            <A CLASS="tabs gallery-button" HREF="/gallery.php" >Gallery</A>

    <!-- Footer -->
        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov, Inc. All rights reserved.</P>
            </DIV>
        </FOOTER>
    </BODY>
</HTML>