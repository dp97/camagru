<?PHP
//Starting session.
session_start();

// Simple security check.
if (isset($_SESSION["username"])) {
    echo "<P>Hi, ". $_SESSION['username'];
} else {
    header('Location: /index.php');
}

function getNotifyState() {
    // Connect to DB.
    require '../config/DBConnect.php';

    // Preparing the request.
    $sql = "SELECT notifications FROM db_main.credentials WHERE username = '". $_SESSION['username'] ."';";

    // Preparing the request.
    $state = $conn->prepare( $sql );

    // Executing the request.
    $state->execute();

    // Fetching the result...
    $result = $state->fetchAll();

    // Disconnecting..
    $conn = NULL;

    return $result[0]['notifications'];
}
?>

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
                <A ID="title-button" HREF="/main.php">Camagru</A>
                <DIV ID="to-right">
                    <A CLASS="tabs" HREF="/am/logout.php">Logout</A>
                </DIV>
            </NAV>
        </HEADER>

        <div class="settings-container">
            <h1 class="txt-color title">Settings</h1>
            <p id="message" style="visibility: hidden;">update successful!</p>

            <div class="settings-fields">
                <label class="txt-color label-txt" for="username">New Username:</label>
                <input class="input-txt" id="newUsername" type="username" name="username" placeholder="new username"/>
                <button class="tabs change-button" id="changeUsername">Change</button>
                <br/>
            </div>

            <div class="settings-fields">
                <label class="txt-color label-txt" for="email">New email:</label>
                <input class="input-txt" id="newEmail" type="email" name="email" placeholder="new email"/>
                <button class="tabs change-button" id="changeEmail">Change</button>
                <br/>
            </div>

            <div class="settings-fields">
                <label class="txt-color label-txt" for="password">New password:</label>
                <input class="input-txt" id="newPassword" type="password" name="password" placeholder="new password"/>
                <input class="input-txt" id="newPasswordConf" type="password" name="cpassword" placeholder="confirm new password"/>
                <button class="tabs change-button" id="changePassword">Change</button>
                <br/>
            </div>

            <div class="settings-fields">
                <label for="notify">E-mail notifications </label>
                <?PHP
                    if ( getNotifyState() == "on" ) {
                        echo '<input class="txt-color" type="checkbox" id="notify" name="notify" checked/>';
                    } else {
                        echo '<input class="txt-color" type="checkbox" id="notify" name="notify"/>';
                    }
                ?>
            </div>

        </div>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov. All rights reserved.</P>
            </DIV>
        </FOOTER>

        <script>
            // Message banner to the user.
            var msg = document.getElementById('message');

            // Get notifications by mail option.
            var notify = document.getElementById('notify');

            notify.onchange = function() {
                var ajax = new XMLHttpRequest();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        if (this.responseText == "success") {
                            messageUser("Changes saved.", false);
                        }
                    }
                };
                if (notify.checked) {
                    ajax.open("GET", "/lib/updateInfo.php?notify=on", true);
                } else {
                    ajax.open("GET", "/lib/updateInfo.php?notify=off", true);
                }
                ajax.send();
            };
            // END NOTIFICATION OPTION

            // START _ USERNAME OPTION
            var changeUsr = document.getElementById('changeUsername');
            var newUsr = document.getElementById('newUsername');

            changeUsr.onclick = function() {
                var ajax = new XMLHttpRequest();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        newUsr.value = "";

                        if (this.responseText == "success") {
                            messageUser("Username changed Successfuly.", false);
                        } else if (this.responseText == "e") {
                            messageUser("Username cannot be empty.", true);
                        } else if (this.responseText == "sv") {
                            messageUser("no new username.", true);
                        } else if (this.responseText == "nuu") {
                            messageUser("username already taken.", true);
                        }
                    }
                };
                ajax.open("GET", "/lib/updateInfo.php?nun=" + newUsr.value, true);
                ajax.send();
            };
            // END _ USERNAME OPTION

            // START _ EMAIL OPTION
            var changeMail = document.getElementById('changeEmail');
            var newMail = document.getElementById('newEmail');

            changeMail.onclick = function() {
                var ajax = new XMLHttpRequest();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Clear fields.
                        newMail.value = "";

                        if (this.responseText == "success") {
                            messageUser("Email changed Successfuly.", false);
                        } else if (this.responseText == "e") {
                            messageUser("Email cannot be empty.", true);
                        } else if (this.responseText == "sv") {
                            messageUser("no new email.", true);
                        }
                    }
                };
                ajax.open("GET", "/lib/updateInfo.php?nem=" + newMail.value, true);
                ajax.send();
            };
            // END _ EMAIL OPTION

            // START _ PASSWORD OPTION
            var changePass = document.getElementById('changePassword');
            var newPass = document.getElementById('newPassword');
            var newPassC = document.getElementById('newPasswordConf');

            changePass.onclick = function() {
                var ajax = new XMLHttpRequest();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Clear field.
                        newPass.value = newPassC.value = "";


                        if (this.responseText == "pts") {
                            messageUser("password too short!", true);
                        } else if (this.responseText == "pnm") {
                            messageUser("password's doesn't match!", true);
                        } else if (this.responseText == "e") {
                            messageUser("Password cannot be empty.", true);
                        } else if (this.responseText == "sv") {
                            messageUser("no new paswword.", true);
                        } else {
                            messageUser("Password changed Successfuly.", false);
                        }
                    }
                };
                ajax.open("GET", "/lib/updateInfo.php?np=" + newPass.value + "&npc=" + newPassC.value, true);
                ajax.send();
            };
            // END _ PASSWORD OPTION

            function messageUser(value, error) {
                if (error == true) {
                    msg.style.color = "red";
                } else {
                    msg.style.color = "green";
                }
                msg.innerHTML = value;
                msg.style.visibility = "visible";

                setTimeout(hideMsgUsr, 9000);
            }

            function hideMsgUsr() {
                msg.style.visibility = "hidden";
            }

        </script>

    </BODY>
</HTML>
