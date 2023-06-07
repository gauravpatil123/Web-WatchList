<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
    <?php
    require_once "./includes/utils.php";
    html_head("utf-8", $title="Signup", $css_path="stylesheets/signup.css");
    ?>
    </head>
    <body>
        <?php
            //error message prompt
            $signup_message = "";

            if (isset($_POST["submit"]) && 
                isset($_POST["fname"]) && !empty($_POST["fname"]) &&
                isset($_POST["lname"]) && !empty($_POST["lname"]) &&
                isset($_POST["uname"]) && !empty($_POST["uname"]) &&
                isset($_POST["upass"]) && !empty($_POST["upass"]) &&
                isset($_POST["cupass"]) && !empty($_POST["cupass"]) &&
                isset($_POST["uemail"]) && !empty($_POST["uemail"])) {

                    // create pdo
                    $pdo = create_pdo("includes/login_credentials.php");

                    //sanitize user inputs
                    $user_fname = santizeString($_POST["fname"]);
                    $user_lname = santizeString($_POST["lname"]);
                    $username = santizeString($_POST["uname"]);
                    $user_password = santizeString($_POST["upass"]);
                    $confirm_pass = santizeString($_POST["cupass"]);
                    $user_email = santizeString($_POST["uemail"]);

                    //check if username exist
                    $row = check_for_username($pdo, $username);

                    if (empty($row)) {

                        // sign up the user
                        if ($user_password === $confirm_pass) {

                            $hash = password_hash($user_password, PASSWORD_DEFAULT);
                            add_user($pdo, $user_fname, $user_lname, $username, $hash, $user_email);
                            $signup_message = "Your account has been created. Please login from login screen if page doesn't redirect automatically.";
                            redirect(3000, "index.php");

                        } else {

                            // error prompt if passwords doesnt match
                            $signup_message = "Passwords do not match please enter passwords again!!";

                        }

                    } else {

                        // error prompt id username is not available
                        $signup_message = "Username not available!!! Please choose another username.";

                    }

                }


        ?>
        <div class='signup-container'>
            <div class="logo-signup"></div>
            <div class='signup-form-title'>Sign Up 
                <div class="signup-error-message">
                    <?php echo $signup_message;?>
                </div>
            </div>
            <div class='signup-form-container'>
                <form class='signup-form' action="" method="POST" autocomplete="false">
                    <div class='signup-form-items'>First Name: <input type="text" name="fname"></div>
                    <div class='signup-form-items'>Last Name: <input type="text" name="lname"></div>
                    <div class='signup-form-items'>Username: <input type="text" name="uname"></div>
                    <div class='signup-form-items'>Password: <input type="password" name="upass"></div>
                    <div class='signup-form-items'>Confirm Password: <input type="password" name="cupass"></div>
                    <div class='signup-form-items'>Email: <input type="text" name="uemail"></div>
                    <div class='signup-form-items'><input id='signup-submit'  type="submit" value="submit" name="submit"></div>
                    <div class='signup-form-items'><a id='user-login' href="index.php">Login</a></div>
                </form>
            </div>
        </div>
    </body>
</html>