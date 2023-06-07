<?php

    require_once "./includes/utils.php";

    //fetch user data for removed titles
    $removed_data = fetch_watchlist($pdo, $logged_user_id, 3);

    //build removed list for display
    build_removed_list($removed_data);

    if (isset($_POST["submit"]) && isset($_POST["status"]) && !empty($_POST["status"])) {

        //set variables from form
        [$content_name, $content_type, $content_type_id, $status] = set_variables_from_list_form($_POST, $_CONTENT_TYPE_);
        $add_to_watchlist = bool_in_array("watch", $status);
        $has_deleted = bool_in_array("deleted", $status);
        $logged_user_id = $_SESSION["user_id"];

        //get relevant ids for databse queries
        [$content_id, $user_content_id] = get_user_content_id($pdo, $content_name, $content_type_id, $logged_user_id);


        //change status and add to users watchlist
        if ($add_to_watchlist) {

            //update status to rewatch and set respectives dates and times
            update_status_to_unwatched($pdo, $user_content_id);

        }

        //delink title from users list and delete permanently for the user
        if ($has_deleted) {

            //remove permanently
            delete_permamently($pdo, $user_content_id);

        }

        //page refresh to reflect database changes
        redirect(0, "account.php");

    }

?>