<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
    <?php
    require_once "./includes/utils.php";
    html_head("utf-8", $title="WatchList - Home", $css_path="stylesheets/recommend.css");
    ?>
    </head>
    <body>
        <?php
            add_nav_menu();
        ?>
        <?php

            //session variable read
            $logged_user_id = $_SESSION["user_id"];

            //check if session in progess
            if (!empty($_SESSION["user_id"])) {
            
                //create pdo object
                $pdo = create_pdo("includes/login_credentials.php");

                //fetch watchlist data of the logged users
                $watchlist_data = fetch_watchlist($pdo, $logged_user_id, 1);

                //build the list of titles from users list
                $content_list = build_content_list($watchlist_data);

                //get first choice and for mat for display
                $random_choice = one_random_choice($content_list);
                $random_choice = display_UC($random_choice);

                //get three random choices
                $three_choices = three_random_choices($content_list);

                //$rerolls = n_random_rolls($content_list, 0);

                //votes form
                if (isset($_POST["submit"]) && isset($_POST["tvotes"]) && !empty($_POST["tvotes"])) {

                    $poll_message = "";
                    $total_votes = santizeString($_POST["tvotes"]);

                    if(!is_numeric($total_votes)) {
                        $poll_message = "Invalid entry. Please enter a valid number.";
                    }

                    $total_votes = (int)$total_votes;

                    //distribute votes to all titles in the users content list
                    $rerolls = n_random_rolls($content_list, $total_votes);
                    // $json_list = json_encode($rerolls);
                    $reroll_set = true;

                }

            } else {

                //redirect if user is not logged in
                redirect(3000, "index.php");

            }

        ?>
        <div class="random-first-choice">
            <div class="random-first-choice-item title">
                Random First Choice
            </div>
            <div id="first-choice-item" class="random-first-choice-item">
                <?php
                    //display first choice
                    echo $random_choice;
                ?>
            </div>
        </div>
        <div class="random-three-choices">
            <div class="random-three-choices-item title">
                Random Three Choices
            </div>
            <div class="three-choices">
                <?php

                    //display three random choices
                    display_three_choices($three_choices);

                ?>
            </div>
        </div>
        <div class="n-votes-poll">
            <div class="n-votes-poll-item title">
                Poll Votes
            </div>
            <div class="n-votes-poll-input">
                <form class="poll-form" action="" method="POST" autocomplete="false">
                    <div class="poll-form-items">
                        Enter Total Votes: <input type="text" name="tvotes">
                        <input id="votes-submit" type="submit" value="Poll" name="submit">
                    </div>
                </form>
                <?php
                echo $poll_message;
                ?>
                <div class="poll-results-container">
                    <div id="poll-list" class="poll-results">
                       <?php
                       
                            //poll display
                            if($reroll_set) {

                                $desc_rerolls = order_desc_array($rerolls);

                                //display poll results
                                display_poll_results($desc_rerolls);

                            }

                       ?> 
                    </div>
                </div>
            </div>
        </div>
    <?php
        add_footer();
    ?>
    </body>
</html>