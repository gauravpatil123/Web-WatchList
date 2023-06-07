<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
    <?php
    require_once "./includes/utils.php";
    html_head("utf-8", $title="Account", $css_path="stylesheets/account.css");
    ?>
    </head>
    <body>
        <?php
            add_nav_menu();
        ?>
        <?php

            //assign session variable
            $logged_user_id = $_SESSION["user_id"];

            if (!empty($_SESSION["user_id"])) {

                //account page
                //error message prompt
                $account_message = "";

                if (isset($_POST["logout"])) {

                    //end session for logout process & redirect to login page
                    $_SESSION = array();
                    setcookie(session_name(), '', time() - 2592000, '/');
                    session_destroy();
                    redirect(1000, "index.php");

                }

                //create pdo object
                $pdo = create_pdo("includes/login_credentials.php");

                //get user data and prepare for display
                $user_data = get_user_data($pdo, $logged_user_id);
                $username = $user_data["username"];
                $fname = $user_data["first_name"];
                $lname = $user_data["last_name"];
                $fname = display_UC($fname);
                $lname = display_UC($lname);
                $uemail = $user_data["email"];

            } else {

                //redirect to login page user not logged in
                redirect(3000, "index.php");

            }
            
        ?>
        <div class="account-page">
            <div class="account-info-container">
                <div class="account-info-title">Account Details</div>
                <?php
                    //vuild html display for account details
                    build_account_details($username, $fname, $lname, $uemail);
                ?>
                <form class='account-form' action="" method="POST" autocomplete="false">
                    <div class='about-form-items'>
                        <input id="logout-submit"  type="submit" value="logout" name="logout">
                    </div>
                </form>
            </div>
            <div class="other-lists-container">
                <div class="other-lists" id="watched-list">
                    <div class="other-list-title">
                        Watched List
                    </div>
                    <div class="other-list">
                        <?php
                            //watched list component
                            require_once "./otherlist_watched.php";
                        ?>
                    </div>
                </div>
                <div class="other-lists" id="removed-list">
                    <div class="other-list-title">
                        Removed List
                    </div>
                    <div class="other-list">
                        <?php
                            //removed list component
                            require_once "./otherlist_removed.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            add_footer();
        ?>
        <script type="module" src="./scripts/account.js"></script>
    </body>
</html>