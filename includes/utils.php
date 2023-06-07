<?php

//Setting Global variables
$_CONTENT_TYPE_ = ["movie" => 1, "web series" => 2, "tv series" => 3];
$_USER_INTEREST_ = ["excited" => 1, "high" => 2, "medium" => 3, "low" => 4];
$_GENRE_ = ["Drama" => 1,
            "Action" => 2,
            "Comedy" => 3,
            "Horror" => 4,
            "Supernatural" => 5,
            "Adventure" => 6,
            "Fantasy" => 7,
            "History" => 8,
            "Documentary" => 9,
            "Fiction" => 10,
            "Thriller" => 11];
$_STATUS_ = ["Unwatched" => 1, "Watched" => 2, "Removed" => 3];

//function to set html header in all the files
function html_head($charset="UTF-8", $title="", $css_path="") {
 
    /*
    Input:
        charset: charset for mthe html document
        title: title for the html document
        css_path: style sheet path if any for the page
        
    Action: echoes a string that has the full generated <head> tag for the page
    */

    $head_str = "<meta charset='$charset'>";
    $head_str .= "<title>$title</title>";
    $head_str .= "<link rel='stylesheet' href='$css_path'>";
    $head_str .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'></script>";
    echo $head_str;

}

//function to create pdo object to access mysql database
function create_pdo($login_file_path) {
    /* 
    Input:
        login_file_path: path for file that has the database login credentials

    Returns:
        pdo: creates and returns a pdo object based on the login credentials
                or throws an exception in case of any errors in the process.
    */

    require_once $login_file_path;

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

}

//function to sanitize user input string
function santizeString($var) {
    /*
    Input:
        var: an user input string to be sanitized for database queries

    Returns:
        var: returns the sanitized version of the same input string
    */
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

//funtion to check for username
function check_for_username($pdo, $username) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        username: user input username
    
    Returns:
        row: checks for the username in the user table of the database
            & returns the user data if the username is found
    */

    $stmt = $pdo->prepare("SELECT * FROM user WHERE username=?");
    $stmt->bindParam(1, $username, PDO::PARAM_STR, 25);
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    return $row;

}

//function to get selected fields of userdata from the user table
function get_user_data($pdo, $uid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        uid: user id of the user

    Return:
        row: returns a array of query selected fields of user data for the id specific user
    */

    $stmt = $pdo->prepare("SELECT username, first_name, last_name, email FROM user where user_id = ?");
    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->execute([$uid]);
    $row = $stmt->fetch();
    return $row;

}

//function to redirect pages
function redirect($refresh, $url) {
    /*
    Input:
        refresh: time in miliseconds to wait before page redirect
        url: target page url

    Action:
        echoes a script that executes a javascript asynchronous function 
        to redirect the page to target page after the refresh time ia passed
    */

    $redirect_js = <<<REDIRECTJS
        <script>

        var url = "$url";
        var refresh = $refresh;
        async function redirect(url, refresh) {
            
            await new Promise(r => setTimeout(r, refresh));
            $(location).attr('href', url);
          
        }

        redirect(url, refresh);

        </script>
    REDIRECTJS;

    echo $redirect_js;

}

//function to add userdata to the user table
function add_user($pdo, $fn, $ln, $un, $pw, $em) {

    /*
    Input:
        pdo: created pdo object with login credentials to access database
        fn: first name of the user
        ln: last name of the user
        un: username of the user
        pw: hashed password of the user
        em: email of the user

    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to add the userdata to the user table
    */

    $stmt = $pdo->prepare('INSERT INTO user VALUES(NULL, ?, ?, ?, ?, ?)');

    $stmt->bindParam(1, $un, PDO::PARAM_STR,  25);
    $stmt->bindParam(2, $pw, PDO::PARAM_STR,  255);
    $stmt->bindParam(3, $em, PDO::PARAM_STR,  50);
    $stmt->bindParam(4, $fn, PDO::PARAM_STR,  15);
    $stmt->bindParam(5, $ln, PDO::PARAM_STR,  15);

    $stmt->execute([$un, $pw, $em, $fn, $ln]);
 
}

//function to add navigation menu to other pages
function add_nav_menu() {
    /*
    Action:
        builds and echos string to add the navigation menu to the page
    */

    $menu_str = "<nav class='nav-menu-container'>";
    $menu_str .= "<a class='nav-links' href='homepage.php'><div class='nav-logo'></div></a>";
    $menu_str .= "<div class='nav-menu-button-container'>";
    $menu_str .= "<a class='nav-links' href='homepage.php'><div class='nav-menu-button' id='nav-home'>Watch<i>List</i></div></a>";
    $menu_str .= "<a class='nav-links' href='recommend.php'><div class='nav-menu-button' id='nav-recs'>Recommendations</div></a>";
    $menu_str .= "<a class='nav-links' href='account.php'><div class='nav-menu-button' id='nav-account'>Account</div></a>";
    $menu_str .= "</div> </nav>";

    echo $menu_str;

}

//function to add page footer to other pages
function add_footer() {
    /*
    Action:
        builds and echos string to add footer to the page
    */

    $footer_str = <<<FOOTER
        <div class="footer-container">
            <a href="#"><div class="footer-logo"></div></a>
            <div id="copyright">©2023 Gaurav K. Patil</div>
        </div>
    FOOTER;

    echo $footer_str;

}

//function to check if the content exists in the content table
function check_content($pdo, $cn, $ctid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        cn: content name
        ctid: content type id

    Return:
        row: returns the content from the content table if it exists
                or returns empty array
    */

    $stmt = $pdo->prepare("SELECT * FROM content WHERE content_name = ? AND content_type_id = ?");

    $stmt->bindParam(1, $cn, PDO::PARAM_STR);
    $stmt->bindParam(2, $ctid, PDO::PARAM_INT);

    $stmt->execute([$cn, $ctid]);
    $row = $stmt->fetch();
    return $row;

}

//function to add content
function add_content($pdo, $cn, $ctid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        cn: content name
        ctid: content type id
        
    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to add the content data to the content table
    */

    $stmt = $pdo->prepare("INSERT INTO content VALUES (NULL, ?, ?)");

    $stmt->bindParam(1, $cn, PDO::PARAM_STR, 50);
    $stmt->bindParam(2, $ctid, PDO::PARAM_INT);

    $stmt->execute([$cn, $ctid]);

}

//function to add content to user specific watchlist
function add_content_to_list($pdo, $uid, $cid, $uint) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        uid: user id of the user
        cid: content id
        uint: user interest id
    
    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to add the data to the user_list table
    */

    $stmt = $pdo->prepare("INSERT INTO user_list VALUES (NULL, ?, ?, 1, ?, now(), now(), NULL, NULL, NULL, NULL)");
    
    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->bindParam(2, $cid, PDO::PARAM_INT);
    $stmt->bindParam(3, $uint, PDO::PARAM_INT);

    $stmt->execute([$uid, $cid, $uint]);

}

//function to check if content exists in users list
function check_list_for_content($pdo, $uid, $cid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        uid: user id of the user
        cid: content id

    Return:
        row: returns the content data from the user list 
                if the content exists in the user's list
    */

    $stmt = $pdo->prepare("SELECT * FROM user_list WHERE user_id=? AND content_id=?");

    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->bindParam(2, $cid, PDO::PARAM_INT);

    $stmt->execute([$uid, $cid]);
    $row = $stmt->fetch();
    return $row;

}

//function to add a genre for the specific user content
function add_content_genre($pdo, $ucid, $gid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id
        gid: genre id

    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to add the genre data to the content 
        from user's list in the user_content_genre table
    */

    $stmt = $pdo->prepare("INSERT INTO user_content_genre VALUES (NULL, ?, ?)");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);
    $stmt->bindParam(1, $gid, PDO::PARAM_INT);

    $stmt->execute([$ucid, $gid]);

}

//function to build array of genre id's from array of genres
function build_genre_ids($genres, $global_genres) {
    /*
    Input:
        genre: user input genre array
        global_genres: Global associative array of set genres and genre ids
        
    Return:
        builds and returns an array of genre id's for the corresponding user input genres
    */

    $genre_ids = [];
    foreach ($genres as $genre) {

        $gid = $global_genres[$genre];
        array_push($genre_ids, $gid);

    }

    return $genre_ids;

}

//function to add all genres for the specific user content
function add_genres($pdo, $ucid, $genre_ids) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id
        genre_ids: array of genre id's for the user input genres

    Action:
        adds all genre selected by users for the content 
        in the user_content_genre table
    */

    foreach ($genre_ids as $gid) {

        add_content_genre($pdo, $ucid, $gid);

    }

}

//function to get the users watchlist data
function fetch_watchlist($pdo, $uid, $sc) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        uid: user id of the user
        sc: status code of the wanted contents
    
    Return:
        builds and returns an array of all data fields from all the joined tables in the query
    */

    $query = "SELECT * FROM user_list ";
    $query .= "JOIN content ON user_list.content_id = content.content_id ";
    $query .= "JOIN user_interest ON user_list.user_interest_id = user_interest.user_interest_id ";
    $query .= "JOIN content_type ON content.content_type_id = content_type.content_type_id ";
    $query .= "WHERE user_id = ? AND status_code = ?";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->bindParam(2, $sc, PDO::PARAM_INT);

    $stmt->execute([$uid, $sc]);
    $row = $stmt->fetchAll();
    return $row;

}

//function to conver string ot first upper case string
function display_UC($word) {
    /*
    Input:
        word: input string
    
    Return:
        out: converts & returns the input string where 
                first letter in every word is upper case
    */

    $out = ucwords(strtolower($word));
    return $out;

}

//function to get the genres of a user content
function get_content_genre($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id
        
    Return:
        array of all the data fields form the all the 
        joined tables for the specific user content id
    */

    $query = "SELECT * FROM user_list ";
    $query .= "JOIN user_content_genre ON user_list.user_content_id = user_content_genre.user_content_id ";
    $query .= "JOIN genre ON user_content_genre.genre_id = genre.genre_id ";
    $query .= "WHERE user_list.user_content_id = ?";

    $stmt = $pdo = $pdo->prepare($query);

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);
    $row = $stmt->fetchAll();
    
    return $row;

}

//function to build genre list from database genres
function build_content_genre_list($dbgenresfetch) {
    /*
    Input:
        dbgenresfetch: fetched data of genres from database
    
    Return:
        a list of genres form the fetched data
    */

    $genre_list = [];

    foreach ($dbgenresfetch as $genre) {

        $genre_name = $genre["genre_name"];
        array_push($genre_list, $genre_name);

    }

    return $genre_list;

}

//function to set variables from user forms and global variables
function set_variables_from_list_form($form, $_CONTENT_TYPE_) {
    /*
    Input:
        form: form method array
        _CONTENT_TYPE_: content type global variable

    Return:
        return an array which contains content name, conntent type, 
        content type id & "status" array from form 
    */

    $content_name = $form["content_name"];
    $content_type = $form["content_type"];
    $content_name = strtolower($content_name);
    $content_type = strtolower($content_type);
    $content_type_id = $_CONTENT_TYPE_[$content_type];
    $status = $form["status"];
    return [$content_name, $content_type, $content_type_id, $status];

}

//fucntion to get user content id for a specific content
function get_user_content_id($pdo, $content_name, $content_type_id, $logged_user_id) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        content_name: name of the content
        content_type_id: content type id
        logged_user_id: user id of the logged user

    Return:
        returns an array of content_id and user_content_id
    */

    $row = check_content($pdo, $content_name, $content_type_id);
    $content_id = $row["content_id"];
    $row = check_list_for_content($pdo, $logged_user_id, $content_id);
    $user_content_id = $row["user_content_id"];
    return [$content_id, $user_content_id];

}

//function to build the user watchlist
function build_watchlist($fetched_data, $pdo) {
    /*
    Input:
        fetched_data: fetched data for the specific user
        pdo: created pdo object with login credentials to access database

    Action:
        builds & echoes string that generates html for the user watchlist
    */

    foreach ($fetched_data as $content) {

        $content_name = $content["content_name"];
        $user_interest = $content["interest_category"];
        $content_type = $content["content_type_name"];
        $content_date_added = $content["date_added"];
        $ucid = $content["user_content_id"];

        $content_name = display_UC($content_name);
        $user_interest = display_UC($user_interest);
        $content_type = display_UC($content_type);
        $content_date_added = display_UC($content_date_added);

        $dbgenres = get_content_genre($pdo, $ucid);
        $genre_list = build_content_genre_list($dbgenres);
        $content_genre = "[ ";

        if (!empty($genre_list)) {
            
            foreach($genre_list as $genre_name) {

                $content_genre .= "| $genre_name ";
    
            }

            $content_genre .= "| ]";

        } else  {

            $content_genre .= " none ]";

        }

        $out_str = <<<WATCHLISTITEMS
            <div class='watchlist-content'>
                <div class='watchlist-item'>
                    <div class='item-content content-name'>
                        $content_name
                    </div>
                    <div class='item-content content-type'>
                        $content_type
                    </div>
                    <div class='item-content content-genre'>
                        $content_genre
                    </div>
                    <div class='item-content user-interest'>
                        Interest: $user_interest
                    </div>
                    <img class="item-content expand-button" src='./assets/icons/up_arrow.svg'>
                    </img>
                </div>
                <div class='hidden'>
                    <form class='item-form' action="" method="POST" autocomplete="false">
                        <div class='item-content date-added'>
                            Date Added: $content_date_added
                        </div>
                        <div class='item-content watched-box'>
                            Watched: <input type="checkbox" value="watched" name="status[]">
                        </div>
                        <div class='item-content removed-box'>
                            Remove: <input type="checkbox" value="removed" name="status[]">
                        </div>
                        <div class='item-content submit-button'>
                            <input type="hidden" name="content_name" value="$content_name">
                            <input type="hidden" name="content_type" value="$content_type">
                            <input id='item-submit'  type="submit" value="Confirm" name="submit">
                        </div>
                    </form>
                </div>
            </div>
        WATCHLISTITEMS;

        echo $out_str;

    }

}

//function to build the user list for watched content
function build_watched_list($fetched_data) {
    /*
    Input:
        fetched_data: fetched data for the specific user

    Action:
        builds & echoes string that generates html for the user list for watched content
    */

    foreach ($fetched_data as $content) {

        $content_name = $content["content_name"];
        $user_interest = $content["interest_category"];
        $content_type = $content["content_type_name"];
        $content_date_watched = $content["date_watched"];

        $content_name = display_UC($content_name);
        $user_interest = display_UC($user_interest);
        $content_type = display_UC($content_type);
        $content_date_watched = display_UC($content_date_watched);

        $out_str = <<<WATCHEDLISTITEMS
            <div class='watched-list-content'>
                <div class='watched-list-item'>
                    <div class='item-content content-name'>
                        $content_name
                    </div>
                    <div class='item-content content-type'>
                        $content_type
                    </div>
                    <div class='item-content'>
                        Date Watched: $content_date_watched
                    </div>
                    <img class="item-content expand-button-watched" src='./assets/icons/up_arrow.svg'>
                    </img>
                </div>
                <div class='hidden'>
                    <form class='item-form' action="" method="POST" autocomplete="false">
                        <div class='item-content rewatch-box'>
                            Watch Again: <input type="checkbox" value="rewatch" name="status[]">
                        </div>
                        <div class='item-content removed-box'>
                            Remove: <input type="checkbox" value="removed" name="status[]">
                        </div>
                        <div class='item-content submit-button'>
                            <input type="hidden" name="content_name" value="$content_name">
                            <input type="hidden" name="content_type" value="$content_type">
                            <input id='item-submit'  type="submit" value="Go" name="submit">
                        </div>
                    </form>
                </div>
            </div>
        WATCHEDLISTITEMS;

        echo $out_str;

    }

}

//function to build the user list for removed content
function build_removed_list($fetched_data) {
    /*
    Input:
        fetched_data: fetched data for the specific user

    Action:
        builds & echoes string that generates html for the user list for removed content
    */

    foreach ($fetched_data as $content) {

        $content_name = $content["content_name"];
        $user_interest = $content["interest_category"];
        $content_type = $content["content_type_name"];
        $content_date_removed = $content["date_removed"];

        $content_name = display_UC($content_name);
        $user_interest = display_UC($user_interest);
        $content_type = display_UC($content_type);
        $content_date_removed = display_UC($content_date_removed);

        $out_str = <<<REMOVEDLISTITEMS
            <div class='removed-list-content'>
                <div class='removed-list-item'>
                    <div class='item-content content-name'>
                        $content_name
                    </div>
                    <div class='item-content content-type'>
                        $content_type
                    </div>
                    <div class='item-content'>
                        Date Removed: $content_date_removed
                    </div>
                    <img class="item-content expand-button-removed" src='./assets/icons/up_arrow.svg'>
                    </img>
                </div>
                <div class='hidden'>
                    <form class='item-form' action="" method="POST" autocomplete="false">
                        <div class='item-content rewatch-box'>
                            Add to WatchList: <input type="checkbox" value="watch" name="status[]">
                        </div>
                        <div class='item-content removed-final'>
                            Delete Permenently: <input type="checkbox" value="deleted" name="status[]">
                        </div>
                        <div class='item-content submit-button'>
                            <input type="hidden" name="content_name" value="$content_name">
                            <input type="hidden" name="content_type" value="$content_type">
                            <input id='item-submit'  type="submit" value="Go" name="submit">
                        </div>
                    </form>
                </div>
            </div>
        REMOVEDLISTITEMS;

        echo $out_str;

    }

}

//function to return boolean values for item in array 
function bool_in_array($needle, $haystack) {
    /*
    Input:
        needle: item to find in array
        haystack: the array where item needs to be found

    Returns:
        returns true of item is in the array else returns false
    */

    if (in_array($needle, $haystack)) {

        return true;

    } else {

        return false;
    
    }

}

//function to update content status to watched
function update_status_to_watched($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id

    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to change the content status to watched
    */

    $stmt = $pdo->prepare("UPDATE user_list SET status_code = 2, date_watched = now(), time_watched = now() WHERE user_list.user_content_id = ?");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);

}

//function to update content status to removed
function update_status_to_removed($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id

    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to change the content status to removed
    */

    $stmt = $pdo->prepare("UPDATE user_list SET status_code = 3, date_removed = now(), time_removed = now() WHERE user_list.user_content_id = ?");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);

}

//function to update content status to unwatched
function update_status_to_unwatched($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id

    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to change the content status to unwatched
    */

    $stmt = $pdo->prepare("UPDATE user_list SET status_code = 1, date_watched = null, time_watched = null, date_removed = null, time_removed = null WHERE user_list.user_content_id = ?");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);

}

//function to delete genres for a user content
function delete_genres($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id
    
    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to delete the content genres from user_content_genre table
    */

    $stmt = $pdo->prepare("DELETE FROM user_content_genre WHERE user_content_id = ?");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);

}

//function to delete user content
function delete_permamently($pdo, $ucid) {
    /*
    Input:
        pdo: created pdo object with login credentials to access database
        ucid: user content id
    
    Action:
        prepares pdo binds the inputs with respective parameters 
        and executes query to delete the content from the users watchlist       
    */

    delete_genres($pdo, $ucid);

    $stmt = $pdo->prepare("DELETE FROM user_list WHERE user_content_id = ?");

    $stmt->bindParam(1, $ucid, PDO::PARAM_INT);

    $stmt->execute([$ucid]);

}

//function to build list of user contents
function build_content_list($fetched_data) {
    /*
    Input:
        fetched_data: fetched user watchlist data

    Return:
        returns a array of user content names
    */

    $content_list = [];

    foreach ($fetched_data as $content) {

        $content_name = $content["content_name"];
        array_push($content_list, $content_name);

    }

    return $content_list;

}

//function to choose one random content
function one_random_choice($list) {
    /*
    Input:
        list: list of all content names of user watchlist

    Return:
        return one random item from the list
    */

    $num_items = count($list);
    $rand_key = rand(0, $num_items - 1);
    $choice = $list[$rand_key];
    
    return $choice;

}

//function to choose three random contents
function three_random_choices($list) {
    /*
    Input:
        list: list of all content names of user watchlist

    Return:
        return three random item from the list
    */

    $three_choices =  [];

    if (count($list) < 3) {

        $rand_keys = array_rand($list, count($list));

    } else {

        $rand_keys = array_rand($list, 3);

    }

    foreach($rand_keys as $key) {

        $choice = $list[$key];
        $choice = display_UC($choice);
        array_push($three_choices, $choice);

    }

    return $three_choices;

}

//function to return the votes distribution
function n_random_rolls($list, $rolls) {
    /*
    Input:
        list: list of all content names of user watchlist
        rolls: total number of votes to distribute

    Return:
        returns associative array with content name and its votes
    */

    $num_items = count($list);
    $count_list = [];

    foreach (range(0, $rolls) as $roll) {

        $rand_key = rand(0, $num_items - 1);
        $content_name = $list[$rand_key];
        $content_name = display_UC($content_name);

        if (array_key_exists($content_name, $count_list)) {

            $count_list[$content_name] += 1;

        } else {

            $count_list[$content_name] = 1;

        }

    }

    asort($count_list);

    return $count_list;

}

//function to display three choices
function display_three_choices($list) {
    /*
    Input:
        list: list of the three chosen content names of user watchlist

    Action:
        builds and echoes a string that generates html for the three choices
    */

    foreach($list as $item) {

        $out_str = <<<THREECHOICES
            <div class='choice-item'>
                $item
            </div>
        THREECHOICES;

        echo $out_str;

    }

}

//function to set js variable to be used in other js scripts
function set_js_variable($var) {
    /*
    Input:
        var: the variable to be set

    Action:
        builds and echoes a script to set the js variable
    */

    $out_str = "<script>";
    //$out_str .= "try ";
    $out_str .= "var list_json = $var;";
    //$out_str .= "console.log(list_json);";
    //$out_str .= "}";
    $out_str .= "</script>";

    echo $out_str;

}

//function to sort array in desc order
function order_desc_array($array) {
    /*
    Input:
        array: array to be sorted

    Return:
        out: returns desc sorted array
    */

    $out = $array;
    arsort($out);
    return $out;

}

//function to display poll results
function display_poll_results($results) {
    /*
    Input:
        results: list of the content names and their votes from user watchlist

    Action:
        builds and echoes a string that generates html for the poll results
    */

    $title_string = <<<TITLE
        <div class="poll-item">
            <div class="poll-item-title">
                Title
            </div>
            <div class="poll-item-votes">
                Votes
            </div>
        </div>
    TITLE;

    echo $title_string;

    foreach($results as $key => $value) {

        $out_str = <<<POLLRESULTS
            <div class="poll-item">
                <div class="poll-item-title">
                    $key
                </div>
                <div class="poll-item-votes">
                    $value
                </div>
            </div>
        POLLRESULTS;

        echo $out_str;
    }

}

//function to build account details
function build_account_details($username, $fname, $lname, $uemail) {
    /*
    Input:
        username: username of the logged user
        fname: first name of the logged user
        lname: last name of the logged user
        uemail: email of the logged user

    Action:
        builds and echoes a string that generates html for account details
    */

    $out_str = <<<ACCDETAILS
        <div class="account-info-details">
            <div class="detail-item" id="uname">
                <span class="account-field">Username: </span><span class="account-field-value">$username</span>
            </div>
            <div class="detail-item" id="fname">
                <span class="account-field">First Name: </span><span class="account-field-value">$fname</span>
            </div>
            <div class="detail-item" id="lname">
                <span class="account-field">Last Name: </span><span class="account-field-value">$lname</span>
            </div>
            <div class="detail-item" id="uemail">
                <span class="account-field">Email: </span><span class="account-field-value">$uemail</span>
            </div>
        </div>
    ACCDETAILS;

    echo $out_str;

}

?>