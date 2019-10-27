<?php

/* 
**  ============
**  PlaatDishes
**  ============
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 1996-2019 PlaatSoft
*/
  
/*
** ------------------
** GENERAL
** ------------------
*/

$lang['TITLE'] = 'PlaatDishes';
$lang['LINK_COPYRIGHT'] = '<a class="normal_link" href="http://www.plaatsoft.nl/">PlaatSoft</a> 1996-'.date("Y").' - All Copyright Reserved ';
$lang['THEME_TO_LIGHT'] = 'Light theme';
$lang['THEME_TO_DARK'] = 'Dark theme';
$lang['ENGLISH'] = 'English';
$lang['DUTCH'] = 'Dutch';

/*
** ------------------
** LINKS
** ------------------
*/

$lang['LINK_HOME']          = i('home'). 'Home'; 
$lang['LINK_SAVE']          = i('edit') . 'Save'; 
$lang['LINK_CANCEL']        = i('times') . 'Cancel';
$lang['LINK_LOGIN']         = 'Login';
$lang['LINK_LOGOUT']        = 'Logout';
$lang['LINK_PREV']          = 'Previous';
$lang['LINK_NEXT']          = 'Next';
$lang['LINK_RELEASE_NOTES'] = 'Release Notes';
$lang['LINK_OVERVIEW']      = 'Overview';
$lang['LINK_USERS']         = 'Users';

/*
** ------------------
** LOGIN
** ------------------
*/

$lang['LABEL_USERNAME'] = 'Username';
$lang['LABEL_PASSWORD'] = 'Password';

$lang ['CONGIG_BAD' ] = 'The following file "config.php" is missing in installation directory.<br/><br/>
plaatdishes can not  work without!<br/><br/>
Rename config.php.sample to config.inc, update the database settings en press F5 in your browser!';

$lang['DATABASE_CONNECTION_FAILED' ] = 'The connection to the database failed. Please check if config.php settings are right!';

/*
** ------------------
** HOME
** ------------------
*/

$lang['LABEL_ID'] = 'Id';
$lang['LABEL_NAME'] = 'Name';
$lang['LABEL_COINS'] = 'Score';
$lang['LABEL_DATE'] = 'Date';
$lang['LABEL_AMOUNT'] = 'Amount';
$lang['LABEL_EXTRA'] = 'Extra';
$lang['LABEL_OVERVIEW'] = 'Overview';

$lang['LABEL_DISH_HELPER'] = 'Next Dish cleaner';

$lang['LABEL_DISH_SIZE'] = 'Dish Size';
$lang['LABEL_PREPARE_QUALITY'] = 'Prepare Quality';
$lang['LABEL_CLEANING_QUALITY'] = 'Dish Cleaning Quality';
$lang['LABEL_CLEANUP_QUALITY'] = 'Cleanup Quality';

/*
** ------------------
** USERS
** ------------------
*/

$lang['LABEL_USERS'] = 'Users';
$lang['LABEL_USER'] = 'User';

$lang['LABEL_EMAIL'] = 'Email';
$lang['LABEL_ACTIVE'] = 'Active';
$lang['LABEL_LAST_LOGIN'] = 'Last Login';
$lang['LABEL_ADMIN'] = 'Admin';


$lang['NAME_TO_SHORT'] = 'Name to short';
$lang['EMAIL_INVALID'] = 'Email invalid';
$lang['USERNAME_TO_SHORT'] = 'Username to short';
$lang['USERNAME_EXIST'] = 'Username exist';
$lang['PASSWORD_TO_SHORT'] = 'Password to short';
$lang['USER_SAVED'] = 'User information saved';

/*
** ------------------
** THE END
** ------------------
*/

?>