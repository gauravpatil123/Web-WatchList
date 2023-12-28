# WatchList
A personalized Watch*List* for people to manage & keep track of their content.

**Contents**
- About
- PHP Webpages & Scripts
- Includes
- Scripts
- Stylesheets
- Assets
- Website Features

**About**</br>
This directory contains the assets, js scripts, stylesheets & php pages & scripts for the Watch*List* website.

Try [Watch*List*](https://webdevdbcourses.prattsi.org/~gpatil/watchlist/index.php) yourself.

**PHP Webpages & Scripts**</br>
- "index.php"<br>
This script generates the login page which is also the landing page of the website.

- "signup.php"<br>
This script generates the signup page for new users to crete their login credentials.

- "homepage.php"<br>
This script generates the homepage for the logged in user. This page is also the Watch*List* page for the logged user.

- "add_content.php"<br>
This script generates the add content module on the homepage/watchlist page for letting the user add new titles in their list.

- "watchlist.php"<br>
This script generates the Watch*List* module on the users homepage for the user to interact with their specific list items.

- "recommend.php"<br>
This script generates the recommendations page, where the website recommends titles to the user based on their Watch*List*.

- "account.php"<br>
This script generates the acocunt page which has user information and logout button.

- "otherlist_watched.php"<br>
This script generates the module for watched titles by the user on the users account page.

- "otherlist_removed.php"<br>
This script generates the module for removed titles by the user on the users account page.

**Includes**</br>
- "utils.php"<br>
This scripts contains all the global variables & helper functions & query functions that are used through the website's php pages.

**Scripts**</br>
- "utils.js"<br>
This script conatils js functions required by othe js scripts on the website.

- "watchlist.js"<br>
This script contains the eventlisteners for the watchlist component on the homepage of the website.

- "account.js"<br>
This script contains the eventlisteners for the account page on the website.

**Stylesheets**</br>
- "css_global_variables.css"<br>
This stylesheet contains all the font links and gobal css variables to be used in other stylesheets for the website.

- "navigation_menu.css"<br>
This stylesheet contains the css for the main navgation menu for the website.

- "footer.css"<br>
This stylesheet contains the css for the footer of the website.

- "login.css", "signup.css", "homepage.css", "recommend.css", "account.css"<br>
These pages contain the css for the respective webpages on the website.

**Assets**</br>
- fonts</br>
Contains all the fonts used in the website

- icons</br>
Contains all icons used in the website

- logos</br>
Contains all the logos used in the website

**Website Features**</br>
  1. User have to create personal account on the website for their personal Watch*List* on the ```signup.php``` page.
  2. User can add new titles to their watch list which include titlename, genres, content type & the user's interest in the title on the ```homepage.php``` page.
  3. User can view the Wacth*List* with all its content details and interact with its contents to change the status on ```watchlist.php``` page.
  4. User can get random recommendations based on their Watch*List* or run a poll amoungst the titles of their watchlist on the ```recommend.php``` page.
  5. Users can view the watched titles on their account page and interact with the content items to change their status on the ```account.php``` page.
  6. Users can view the removed titles on their acouunt page and interact with the content items to change their status on the ```account.php``` page.
