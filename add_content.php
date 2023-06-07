<?php

    require_once "./includes/utils.php";
            
    //session variable read
    $logged_user_id = $_SESSION["user_id"];
    //echo "Welcome user $logged_user_id";

    if (!empty($_SESSION["user_id"])) {

        //user logged in still so can continue on this page

        //create pdo
        $pdo = create_pdo("includes/login_credentials.php");

        //add content form setup
        if (isset($_POST["submit"]) && 
            isset($_POST["cname"]) && !empty($_POST["cname"]) &&
            isset($_POST["uint"]) && !empty($_POST["uint"]) &&
            isset($_POST["ctype"]) && !empty($_POST["ctype"]) &&
            isset($_POST["genres"]) && !empty($_POST["genres"])) {

            //sanitize user input and set user variables
            $content_name = santizeString($_POST["cname"]);
            $user_interest = santizeString($_POST["uint"]);
            $content_type = santizeString(($_POST["ctype"]));
            $content_genres = [];

            //populate content_genre array with sanitized user input genres
            foreach ($_POST["genres"] as $genre) {

                $genre = santizeString($genre);
                array_push($content_genres, $genre);

            }

            // convert inputs to small case & getting relevant ids
            $content_name = strtolower($content_name);
            $content_type = strtolower($content_type);
            $user_interest = strtolower($user_interest);
            $content_type_id = $_CONTENT_TYPE_[$content_type];
            $user_interest_id = $_USER_INTEREST_[$user_interest];
            $content_genre_ids = build_genre_ids($content_genres, $_GENRE_);

            //check if content exist check for name and type
            $row = check_content($pdo, $content_name, $content_type_id);

            if (empty($row)) {

                //insert content
                add_content($pdo, $content_name, $content_type_id);
                
                //get content information
                $row = check_content($pdo, $content_name, $content_type_id);
                $content_id = $row["content_id"];

                //add content to user list
                add_content_to_list($pdo, $logged_user_id, $content_id, $user_interest_id);
                $user_content_in_list = check_list_for_content($pdo, $logged_user_id, $content_id);
                $user_content_id = $user_content_in_list["user_content_id"];
                //echo $user_content_id;

                //add genres to content
                add_genres($pdo, $user_content_id, $content_genre_ids);



            } else {

                //get content information
                $content_id = $row["content_id"];

                //check if content in user list
                $user_content_in_list = check_list_for_content($pdo, $logged_user_id, $content_id);

                if (empty($user_content_in_list)) {

                    //add content to user list if not already
                    add_content_to_list($pdo, $logged_user_id, $content_id, $user_interest_id);
                    $user_content_in_list = check_list_for_content($pdo, $logged_user_id, $content_id);
                    $user_content_id = $user_content_in_list["user_content_id"];

                    //add genres to content
                    add_genres($pdo, $user_content_id, $content_genre_ids);

                } else {

                    //prompt user message to say its already on the list
                    $content_message = "$content_name already on the List";
                    echo "$content_message";

                }

            }

        }

    } else {

        //user not logged in redirect to login screen
        redirect(3000, "index.php");

    }

?>