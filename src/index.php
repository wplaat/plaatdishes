<?php

/*
**  ============
**  PlaatProtect
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

/**
 * @file
 * @brief contain general page and event handler
 */

$time_start = microtime(true);

include "general.php";
include "database.php";
include "english.php";

if (!file_exists( "config.php" )) {

	echo general_header();

	echo '<h1>ERROR</h1>';
	echo '<br/>';
    echo t('CONGIG_BAD');
	echo '<br/>';
	
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	
	echo general_footer($time);
	
   exit;
}

include "config.php";

/*
** --------------------
** DATABASE
** --------------------
*/

if ( @plaatprotect_db_connect($dbhost, $dbuser, $dbpass, $dbname) == false) {

	echo general_header();

	echo '<h1>ERROR</h1>';
	echo '<br/>';
	echo t('DATABASE_CONNECTION_FAILED');
	echo '<br/>';

	$time_end = microtime(true);
	$time = $time_end - $time_start;
	
	echo general_footer($time);

	exit;
}

@plaatprotect_db_check_version($version);

/*
** ----------------------
** PARAMETERS
** ----------------------
*/

$ip = $_SERVER['REMOTE_ADDR'];

$eid = EVENT_NONE;
$sid = EVENT_NONE;
$pid = PAGE_HOME;

$date = date('Y-m-d');
$limit = 0;
$cat=0;

$session = plaatprotect_post('session', '');
$token = plaatprotect_post("token", "");

if (strlen($token)>0) {
	
  /* Decode token to php parameters */
  $token =  plaatprotect_token_decode($token);	  
  $tokens = @preg_split("/&/", $token);
	
  foreach ($tokens as $item) {
     $items = preg_split ("/=/", $item);				
     ${$items[0]} = $items[1];	
     //echo '>'.$items[0].'='.$items[1].'<br/>';
  }
}

/*
** --------------------------------------
** SECURITY
** --------------------------------------
*/

$home_password = plaatprotect_db_config_value('home_password',CATEGORY_GENERAL);

// Create for each visitor an account (without session_id)
$session_id = plaatprotect_db_get_session($ip);

if (strlen($home_password)>0) {
	if ((strlen($session_id)==0) || ($session!=$session_id)) {
		// User not login, Redirect to login page
		$pid = PAGE_HOME_LOGIN;
	}
}

/*
** -------------------
** ACTIONS
** -------------------
*/

function plaatprotect_scheme_action() {

	global $ip;
		
	$sql  = 'select theme from session where ip="'.$ip.'"';
	$result = plaatprotect_db_query($sql);
	$row = plaatprotect_db_fetch_object($result);

	if ($row->theme=="light") {
		$theme = "dark";
	} else {
		$theme = "light" ;
	}
	
	$sql = 'update session set theme="'.$theme.'" where ip="'.$ip.'"';
	plaatprotect_db_query($sql);
}

function plaatprotect_language_action() {
	
	global $ip;
	
	$sql  = 'select language from session where ip="'.$ip.'"';
	$result = plaatprotect_db_query($sql);
	$row = plaatprotect_db_fetch_object($result);

	if ($row->language=="en") {
		$language = "nl";
	} else {
		$language = "en";
	}
	
	$sql = 'update session set language="'.$language.'" where ip="'.$ip.'"';
	plaatprotect_db_query($sql);
}

/*
** ---------------------
** SPECIAL EVENT MACHINE
** ---------------------
*/

switch ($sid) {

	case EVENT_SCHEME: 
			plaatprotect_scheme_action();
			break;
			
	case EVENT_LANGUAGE:
			plaatprotect_language_action();
			break;
}


/*
** -------------------
** LANGUAGE
** -------------------
*/

$sql  = 'select language from session where ip="'.$ip.'"';
$result = plaatprotect_db_query($sql);
$row = plaatprotect_db_fetch_object($result);

if ($row->language=="nl") {

	include("dutch.php");
}	

/*
** -------------------
** STATE MACHINE
** -------------------
*/

$page = "";

switch ($pid) {

	// ---------------------------------
		
	case PAGE_HOME: 
	case PAGE_HOME_LOGIN: 
		include "home.php";
		$page = plaatprotect_home();
		break;
}


// Normal page
echo general_header();

echo "<!-- content-start -->";
echo $page;
echo "<!-- content-end -->";
	
// Calculate to page render time 
$time_end = microtime(true);
$time = $time_end - $time_start;

echo general_footer($time);

plaatprotect_db_close();

/*
** -------------------
** THE END
** -------------------
*/

?>
