<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
    <?php
    require_once "./includes/utils.php";
    html_head("utf-8", $title="WatchList - Home", $css_path="stylesheets/homepage.css");
    ?>
    </head>
    <body>
        <?php
            add_nav_menu();
        ?>
        <div class='add-content-container'>
            <div class='form-title'>Add Content 
                <div class="content-error-message">
                    <?php 
                        $content_message = "";
                        echo "$content_message";
                    ?>
                </div>
            </div>
            <div class='content-form-container'>
                <form class='content-form' action="" method="POST" autocomplete="false">
                    <div class='content-form-items'><div class='content-form-sub-items'>Name</div> 
                        <input id="content-name-field" class='content-form-sub-items' type="text" name="cname">
                    </div>
                    <div class='content-form-items'>
                        <div class='content-form-sub-items'>Type</div> 
                        <select id='content-type-field'class='content-form-sub-items' size="1" name="ctype">
                            <option value="Movie">Movie</option>
                            <option value="Web Series">Web Series</option>
                            <option value="TV Series">TV Series</option>
                        </select>
                    </div>
                    <div class='content-form-items'>
                        <div class='content-form-sub-items'>Interest</div> 
                        <select id ="content-interest-field" class='content-form-sub-items' size="1" name="uint">
                            <option value="Excited">Excited</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class='content-form-items'>
                        <div class='content-form-sub-items'>Genre(s)</div> 
                        <select id ="content-genres-field" class='content-form-sub-items' size="3" name="genres[]" multiple="multiple">
                            <option value="Drama">Drama</option>
                            <option value="Action">Action</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Horror">Horror</option>
                            <option value="Supernatural">Supernatural</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Documentary">Documentary</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Thriller">Thriller</option>
                        </select>
                    </div>
                    <div class='content-form-items'><input id='content-submit'  type="submit" value="+ Add" name="submit"></div>
                </form>
            </div>
        </div>
        <?php
            //addcontent component
            require_once "add_content.php";
        ?>
        <div class='watchlist-container'>
            <div class='watchlist-title'>
                Watch<i>List</i>
            </div>
            <div class='watchlist'>
                <?php
                    //watchlist component
                    require_once "watchlist.php";
                ?>
            </div>
        </div>
        <?php
            add_footer();
        ?>
        <script type="module" src="./scripts/watchlist.js"></script>
    </body>
</html>