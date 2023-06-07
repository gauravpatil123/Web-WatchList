<?php

    require_once "./includes/utils.php";

    //check if seession is active and then proceed
    if (!empty($_SESSION["user_id"])) {

        //fetch the users watchlist
        $watchlist_data = fetch_watchlist($pdo, $logged_user_id, 1);

        //build the html for the users watchlist
        build_watchlist($watchlist_data, $pdo);

        if (isset($_POST["submit"]) && isset($_POST["status"]) && !empty($_POST["status"])) {
            
            //set variables from user forms
            [$content_name, $content_type, $content_type_id, $status] = set_variables_from_list_form($_POST, $_CONTENT_TYPE_);
            $has_watched = bool_in_array("watched", $status);
            $has_removed = bool_in_array("removed", $status);
            $logged_user_id = $_SESSION["user_id"];

            //get relavent ids for databse update queries
            [$content_id, $user_content_id] = get_user_content_id($pdo, $content_name, $content_type_id, $logged_user_id);

            //change status if title watched
            if ($has_watched) {
            
                update_status_to_watched($pdo, $user_content_id);

            }

            //change status if title is removed
            if ($has_removed) {

                update_status_to_removed($pdo, $user_content_id);


            }

            //page refresh to reflect change sin the database
            redirect(0, "homepage.php");

        }

    } else if (empty($_SESSION["user_id"])){

        //user not logged in redirect to login screen
        redirect(3000, "index.php");

    }

?>