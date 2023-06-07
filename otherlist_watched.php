<?php

    require_once "./includes/utils.php";

    //fetch user list for watched titles
    $watched_data = fetch_watchlist($pdo, $logged_user_id, 2);

    //build the watchlist for display
    build_watched_list($watched_data);

    if (isset($_POST["submit"]) && isset($_POST["status"]) && !empty($_POST["status"])) {

        //set variables from form
        [$content_name, $content_type, $content_type_id, $status] = set_variables_from_list_form($_POST, $_CONTENT_TYPE_);
        $wants_rewatch = bool_in_array("rewatch", $status);
        $has_removed = bool_in_array("removed", $status);
        $logged_user_id = $_SESSION["user_id"];

        //get relavent ids for database queries
        [$content_id, $user_content_id] = get_user_content_id($pdo, $content_name, $content_type_id, $logged_user_id);

        //change status and put title to users watchlist
        if ($wants_rewatch) {

            //update status to rewatch and set respectives dates and times
            update_status_to_unwatched($pdo, $user_content_id);

        }

        //change status to removed
        if ($has_removed) {

            //update status to removed
            update_status_to_removed($pdo, $user_content_id);

        }

        //page refresh to reflect database changes
        redirect(0, "account.php");

    }

?>