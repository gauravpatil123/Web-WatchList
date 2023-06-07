<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
    <?php
    require_once "./includes/utils.php";
    html_head("utf-8", $title="Login", $css_path="stylesheets/login.css");
    ?>
    </head>
    <body>
        <?php
            $login_message = "";
            if (isset($_POST["submit"]) && 
            isset($_POST["uname"]) && !empty($_POST["uname"]) && 
            isset($_POST["upass"]) && !empty($_POST["upass"])) {

                //create pdo
                $pdo = create_pdo("includes/login_credentials.php");

                //sanitize userinputs
                $username = santizeString($_POST["uname"]);
                $user_password = santizeString(($_POST["upass"]));

                //check if username exists
                $row = check_for_username($pdo, $username);

                //check if the password matches for the username
                if (!empty($row)) {

                    $passwordfromdb = $row["user_pw"];

                    if (password_verify($user_password, $passwordfromdb)) {

                        //Success message if any

                        //Session variables
                        $_SESSION["user_id"] = $row["user_id"];

                        //Success Redirect
                        redirect(0, "homepage.php");

                    } else {

                        // Failure message --> decide a place to inject the message
                        $login_message = "Incorrrect Username or Password";

                    }

                } else {

                    // Failure message --> decide a place to inject the message
                    $login_message = "Incorrrect Username or Password";

                }

            } else if (isset($_POST["submit"]) && 
            isset($_POST["uname"]) && empty($_POST["uname"]) && 
            isset($_POST["upass"]) && empty($_POST["upass"])) {

                //Error message if input fields are empty while submission
                $login_message = "Enter Username & Password";

            }

        ?>
        <div class='login-container'>
            <div class="logo-login"></div>
            <div class='form-title'>Login 
                <div class="login-error-message">
                    <?php echo $login_message;?>
                </div>
            </div>
            <div class='login-form-container'>
                <form class='login-form' action="" method="POST" autocomplete="false">
                    <div class='login-form-items'>Username: <input type="text" name="uname"></div>
                    <div class='login-form-items'>Password: <input type="password" name="upass"></div>
                    <div class='login-form-items'><input id='login-submit'  type="submit" value="Login" name="submit"></div>
                    <div class='login-form-items'><a id='user-signup' href="signup.php">Signup</a></div>
                </form>
            </div>
        </div>
    </body>
</html>

