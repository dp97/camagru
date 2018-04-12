<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>Reset Password</TITLE>
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
                    <A class="tabs" HREF="/am/login.php">Log In</A>
                    <A class="tabs" HREF="/am/signup.php">Sign Up</A>
                </DIV>
            </NAV>
        </HEADER>

        <SECTION>
            <FORM METHOD="POST">
                <DIV class="container">
                    <H1>Reset your Password</H1>
                    <HR>
                    <P>Submit your email address and we’ll send you a link to reset your password.</P>
                    <label for="email"><b>E-mail:</b></label>
                    <input type="email" name="email" id="email" value="" placeholder="Enter email" required/>
                    <input type="submit" id="button" value="Submit">
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
include '../lib/test_input.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $email = test_input( $_POST['email'] );

    change_pass( $email );
}

function change_pass( $user_mail ) {
    $resetURL = "http://" . $_SERVER['SERVER_NAME'] . ":8080/am/reset.php?email=$user_mail";
    $subject = "Reset password instructions";
    $message = "\n\nHello " . $user_mail . "!
    \n\nSomeone has requested a link to change your password. You can do this through the link below.
    \n\nIf you didn't request this, please ignore this email.
    \n\n<a href='$resetURL'>Change my password</a>
    \n\nYour password won't change until you access the link above and create a new one.";
    $header = "From:no-reply@camagru.com";

    if ( mail( $user_mail, $subject, $message, $header ) ) {
        echo "Instructions sent to " . $user_mail;
    } else {
        echo "Error: E-mail not sent.";
    }
}
?>
