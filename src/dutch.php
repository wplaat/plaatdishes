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
$lang['ENGLISH'] = 'Engels';
$lang['DUTCH'] = 'Nederlands';

/*
** ------------------
** LINKS
** ------------------
*/

$lang['LINK_HOME']          = i('home'). 'Home'; 
$lang['LINK_SAVE']          = i('edit') . 'Bewaar'; 
$lang['LINK_CANCEL']        = i('times') . 'Cancel';
$lang['LINK_LOGIN']         = 'Login';
$lang['LINK_LOGOUT']        = 'Logout';
$lang['LINK_PREV']          = 'Vorige';
$lang['LINK_NEXT']          = 'Volgende';
$lang['LINK_RELEASE_NOTES'] = 'Release Notes';
$lang['LINK_OVERVIEW']      = 'Overzicht';
$lang['LINK_USERS']         = 'Gebruikers';
$lang['LINK_TRANSACTION']   = 'Transactie';
$lang['LINK_TRANSFER']      = 'Overmaken';
$lang['LINK_MARKET_PLACE']  = 'Marktplaats';
$lang['LINK_BUY'] 			= 'Kopen';

/*
** ------------------
** LOGIN
** ------------------
*/

$lang['LABEL_USERNAME'] = 'Gebruikersnaam';
$lang['LABEL_PASSWORD'] = 'Wachtwoord';

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
$lang['LABEL_NAME'] = 'Naam';
$lang['LABEL_POINTS'] = 'Punten';
$lang['LABEL_MONEY'] = 'Geld';
$lang['LABEL_AMOUNT'] = 'Aantal';
$lang['LABEL_DATE'] = 'Datum';
$lang['LABEL_EXTRA'] = 'Extra';
$lang['LABEL_OVERVIEW'] = 'Overzicht';

$lang['LABEL_DISH_HELPER'] = 'Volgende afwashulp';

$lang['LABEL_DISH_SIZE'] = 'Vaat grootte';
$lang['LABEL_PREPARE_QUALITY'] = 'Afruim kwaliteit';
$lang['LABEL_CLEANING_QUALITY'] = 'Afwas kwaliteit';
$lang['LABEL_CLEANUP_QUALITY'] = 'Opruim kwaliteit';

/*
** ------------------
** TRANSACTION
** ------------------
*/

$lang['LABEL_TRANSACTION'] = 'Transactie Punten';
$lang['LABEL_FROM'] = 'Van';
$lang['LABEL_TO'] = 'Naar';
$lang['LABEL_DESCRIPTION'] = 'Omschrijving';

$lang['USER_DOES_NOT_EXIST'] = 'User does not exist!';
$lang['AMOUNT_TO_SMALL'] = 'Amount is too small!';
$lang['AMOUNT_TO_BIG'] = 'Amount is too big!';
$lang['TOO_LESS_COINS'] = 'Too less coins!';
$lang['PAYMENT_DONE'] = 'Payment done!';
$lang['DESCRIPTION_IS_MANDATORY'] = 'Description is mandatory!'; 

/*
** ------------------
** Marketplace
** ------------------
*/

$lang['LABEL_MARKET_PLACE'] = 'Marktplaats';

$lang['LABEL_PRICE'] = 'Prijs';
$lang['LABEL_IMAGE'] = 'Foto';
$lang['LABEL_ACTION'] = 'Actie';
$lang['LABEL_EURO']  = 'euro';

$lang['TOO_LESS_MONEY'] = 'Niet genoeg geld!';
$lang['ITEM_ORDER'] = 'Order is verwerkt!';

/*
** ------------------
** THE END
** ------------------
*/

?>