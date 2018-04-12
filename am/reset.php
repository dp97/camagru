<?PHP

if ( $_SERVER['REQUEST_METHOD'] != "POST" && !isset( $_GET['email']) ) {
    die("not authorized.");
}

if ( isset( $_POST['pass'] ) && isset( $_POST['cpass'] ) && isset( $_POST['email'] )) {
    include '../lib/test_input.php';

    $p = test_input( $_POST['pass'] );
    $cp = test_input( $_POST['cpass'] );
    $email = test_input( $_POST['email'] );

    if ( strlen($p) < 8 ) {
        die('ts');//too short
    }
    if ( $p != $cp ) {
        die("pnm");// passwords not match.
    }
    $np = md5($p);

    // Connecting to database...
    require '../config/DBConnect.php';
    // Preparing the request.
    $sql = "UPDATE db_main.credentials SET password = '$np' WHERE email = '$email';";
    // Sending the request.
    $conn->exec( $sql );
    // Disconnecting..
    $conn = NULL;

    die("success");
    exit;
}
?>
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
                </DIV>
            </NAV>
        </HEADER>

        <SECTION>
            <DIV class="container">
                <H1>Reset your Password</H1>
                <HR>
                <P id='rstmsg'>Enter a new password.</P>
                <p hidden id="email"><?PHP echo $_GET['email']; ?></p>
                <input type="password" name="password" id="password" value="" placeholder="Type new password" required/>
                <input type="password" name="cpassword" id="cpassword" value="" placeholder="Retype new password" required/>
                <button class="tabs change-button" id="confirm">Confirm</button>
            </DIV>
        </SECTION>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov, Inc. All rights reserved.</P>
            </DIV>
        </FOOTER>

        <script>
            var button = document.getElementById('confirm');

            button.onclick = function() {
                var ajax = new XMLHttpRequest();
                var pass = document.getElementById('password');
                var cpass = document.getElementById('cpassword');
                var email = document.getElementById('email');
                var msg = document.getElementById('rstmsg');

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText == "success") {
                            msg.innerHTML = "Changes saved.";
                            window.location = '/am/login.php';
                        } else if (this.responseText == "ts") {
                            msg.innerHTML = "password too short.";
                        } else if (this.responseText == "pnm") {
                            msg.innerHTML = "passwords dont match.";
                        }
                    }
                };
                ajax.open("POST", "/am/reset.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("pass=" + pass.value + "&cpass=" + cpass.value + "&email=" + email.innerHTML);
            };
        </script>
    </BODY>
</HTML>
