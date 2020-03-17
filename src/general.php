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
** -----------
** DIRECTORY
** -----------
*/

// Installation path
define('BASE_DIR', '/var/www/html/plaatdishes');
 
/*
** -----------
** PAGES
** -----------
*/

define('PAGE_HOME_LOGIN',           10);
define('PAGE_HOME',                 11);
define('PAGE_RELEASE_NOTES',        12);
define('PAGE_OVERVIEW',             13);
define('PAGE_USERS',                14);
define('PAGE_USER',                 15);
define('PAGE_TRANSACTION',          16);
define('PAGE_MARKET_PLACE',         17);
define('PAGE_BUY', 			        18);

/*
** -----------
** EVENTS
** -----------
*/

define('EVENT_NONE',                100);
define('EVENT_LOGIN',               101);
define('EVENT_SCHEME',              102);
define('EVENT_LANGUAGE',            103);
define('EVENT_SAVE',                104);
define('EVENT_NEXT',                105);
define('EVENT_PREV',                106);
define('EVENT_EDIT',                107);
define('EVENT_SAVE_PASSWORD',       108);
define('EVENT_TRANSFER',            109);
define('EVENT_BUY',                 110);

/*
** -----------
** CATEGORY
** -----------
*/

define('CATEGORY_GENERAL',           0);
define('MONEY_CONVER_RATE',          0.2);

/*
** ---------------------------------------------------------------- 
** PASSWORD
** ---------------------------------------------------------------- 
*/

function plaatdishes_password_hash($raw) {

	$options = [
		'cost' => 12,
	];
	return password_hash($raw, PASSWORD_BCRYPT, $options);
}

function plaatdishes_password_verify( $password, $hash) {

	return password_verify ( $password, $hash );
}

/**
 ********************************
 * LOG
 ********************************
 */
 
function plaatdishes_log($text) {

  $t = microtime(true);
  $micro = sprintf("%06d",($t - floor($t)) * 1000000);
  $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );

  print $d->format("Y-m-d H:i:s.u");
  echo " ".$text."\r\n";
}

/*
** -----------
** LOCK
** -----------
*/

function plaatdishes_islocked() { 
    if( file_exists( LOCK_FILE ) ) { 

        $lockingPID = trim( file_get_contents( LOCK_FILE ) ); 
        $pids = explode( "\n", trim( `ps -e | awk '{print $1}'` ) ); 
        if( in_array( $lockingPID, $pids ) )  return true; 
        unlink( LOCK_FILE ); 
    } 
    
    file_put_contents( LOCK_FILE, getmypid() . "\n" ); 
    return false; 
} 


/*
** -----------
** PAGE
** -----------
*/

/**
 * Language function 
 * @return Combine string in selected language
 */
function t() {

	global $lang;
	
   $numArgs = func_num_args();

   $temp = $lang[func_get_arg(0)];

   $pos = 0;
   $i = 1;

   while (($pos = strpos($temp, "%s", $pos)) !== false) {
      if ($i >= $numArgs) {
         throw new InvalidArgumentException("Not enough arguments passed.");
		}

      $temp = substr($temp, 0, $pos) . func_get_arg($i) . substr($temp, $pos + 2);
      $pos += strlen(func_get_arg($i));
      $i++;
   }      
	
	$temp = mb_convert_encoding($temp, "UTF-8", "HTML-ENTITIES" ); 
   return $temp; 
}

/**
 * Add title icon 
 */
function add_icons() {
	
	// Charset
	$page = '<meta charset="UTF-8">';
	
	// Normal icons
	$page .= '<link rel="shortcut icon" type="image/png" sizes="16x16" href="images/16.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="24x24" href="images/24.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="32x32" href="images/32.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="48x48" href="images/48.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="64x64" href="images/64.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="128x128" href="images/128.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="256x256" href="images/256.png">';
	$page .= '<link rel="shortcut icon" type="image/png" sizes="512x512" href="images/512.png">';
	
	// Apple icons
	$page .= '<link rel="apple-touch-icon" type="image/png" href="images/apple-60.png">';
	$page .= '<link rel="apple-touch-icon" type="image/png" sizes="76x76" href="images/apple-76.png">';
	$page .= '<link rel="apple-touch-icon" type="image/png" sizes="120x120" href="images/apple-120.png">';
	$page .= '<link rel="apple-touch-icon" type="image/png" sizes="152x152" href="images/apple-152.png">';
	
	// Web app cable (runs the website as app)
	$page .= '<meta name="apple-mobile-web-app-capable" content="yes">';
	$page .= '<meta name="mobile-web-app-capable" content="yes">';
	
	// Title
	$page .= '<title>'.t('TITLE').'</title>';
	   
	return $page;
}

function loadCSS($url) {
	return '<link href="'.$url.'" rel="stylesheet" type="text/css" />';
}


function loadJS($url) {
	return '<script language="JavaScript" src="'.$url.'" type="text/javascript"></script>';	
}

/**
 * General header
 */
function general_header() {

	// input
	global $ip;
	global $pid;
	global $eid;
	global $sid;
	global $date;
	global $session;

	$sql  = 'select theme,language from session where ip="'.$ip.'"';
	$result = plaatdishes_db_query($sql);
	$row = plaatdishes_db_fetch_object($result);
		
	$theme="light";
	$lang="en";
	if (isset($row->language)) {
		$lang = $row->language;
		$theme = $row->theme;
	}
    
	$page  = '<!DOCTYPE html>';	
	$page .= '<html>';
	$page .= '<head>'; 

  $page .= add_icons();

  $page .= loadJS('js/link2.js');
  
   $page .= loadCSS('css/general.css');

    // Load the icons from Font Awesome not with loadCSS because this file never will change and it cant load the font
    $page .= '<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"/>';

    // Load the dark css theme if db theme var = dark
    if ($theme == "dark") {
	$page .= loadCSS('css/theme-dark.css');
    }
  
  $page .= '</head>';
  
  $page .= '<body>';
  $page .= '<form id="plaatdishes" method="POST">';  
  
  $page .= '<input type="hidden" name="session" value="'.$session.'" />';
  
	$page .= '<div class="language">';
	if ($lang=="en") {
		$page .= plaatdishes_normal_link('pid='.$pid.'&eid='.$eid.'&date='.$date.'&sid='.EVENT_LANGUAGE, t('DUTCH'));
	} else { 
		$page .= plaatdishes_normal_link('pid='.$pid.'&eid='.$eid.'&date='.$date.'&sid='.EVENT_LANGUAGE , t('ENGLISH'));
	}
	$page .= '</div>';
	
	$page .= '<div class="theme">';
	if ($theme == "light") {
		$page .= plaatdishes_normal_link('pid='.$pid.'&eid='.$eid.'&date='.$date.'&sid='.EVENT_SCHEME, t('THEME_TO_DARK'));
	} else {
		$page .= plaatdishes_normal_link('pid='.$pid.'&eid='.$eid.'&date='.$date.'&sid='.EVENT_SCHEME, t('THEME_TO_LIGHT'));		
	}
	$page .= '</div>';
   
	return $page;
}

/**
 * General footer
 */
function general_footer($time=0) {
	global $pid;
	
	$page = '';
	
	$page .= '<div class="copyright">'.t('LINK_COPYRIGHT') .' - '.t('TITLE').'<br/>';
	$page .= '['.round($time*1000).' ms - '.plaatdishes_db_count().' queries]';
	$page .= '</div>';

	$page .= '</form>';
	$page .= '</body>';
	$page .= '</html>';
  
	return $page;
}

/**
 * Set cookie
 */
function set_cookie_and_refresh ($name, $value) {
	setcookie($name, $value, time() + (86400 * 30), "/");
	header("Location: " . $_SERVER['PHP_SELF']);
}

/**
 * Set icon to link
 */
function i ($name) { 
	$icon = '<i class="fa fa-' . $name;
	if ($name == 'chevron-right') {
		$icon .= ' right';
	}
	$icon .= ' fa-fw"></i>';
	return $icon;
}

// ----------------------------
// NAVIGATION
// ----------------------------

function plaatdishes_get($label, $default) {
	
	$value = $default;
	
	if (isset($_GET[$label])) {
		$value = $_GET[$label];
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
	}
	
	return $value;
}

/**
 * Process post parameters 
 */
function plaatdishes_post($label, $default) {
	
	$value = $default;
	
	if (isset($_POST[$label])) {
		$value = $_POST[$label];
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
	}
	
	return $value;
}

/** 
 * Encode link data
 */
function plaatdishes_token_decode($token) {
	
	$token=gzinflate(base64_decode($token));
	
	return htmlspecialchars_decode($token);
}

/** 
 * Encode link data
 */
function plaatdishes_token_encode($token) {
   
   $token = htmlspecialchars($token);	
   
   return base64_encode(gzdeflate($token));
}

/**
 * Create button like link 
 */
function plaatdishes_link($parameters, $label, $title="") {
   	
	$link  = '<a href="javascript:link(\''.plaatdishes_token_encode($parameters).'\');" class="link" ';			
	
	if (strlen($title)!=0) {
		$link .= ' title="'.strtolower($title).'"';
	}
	
	$link .= '>'.$label.'</a>';	
	return $link;
}

/**
 * Create hidden link with popup
 */ 
function plaatdishes_link_confirm($parameters, $label, $question="") {
   			
	$link  = '<a href="javascript:show_confirm(\''.$question.'\',\''.plaatdishes_token_encode($parameters).'\');" class="link" ';
	$link .= '>'.$label.'</a>';	
		
	return $link;
}

/**
 * Create hyperlink like link 
 */
function plaatdishes_normal_link($parameters, $label, $id="", $title="") {
   
	global $link_counter;
	
	$link_counter++;
	
	$link  = '<a href="javascript:link(\''.plaatdishes_token_encode($parameters).'\');" class="normal_link" ';			
	if (strlen($id)!=0) {
		$link .= ' id="'.strtolower($id).'"';
	}
	if (strlen($title)!=0) {
		$link .= ' title="'.strtolower($title).'"';
	}
	$link .= '>'.$label.'</a>';	
	return $link;
}

function plaatdishes_create_path($path) {
    if (is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
    $return = plaatdishes_create_path($prev_path);
    umask(0);
    return ($return && is_writable($prev_path)) ? mkdir($path, 0777) : false;
}

function plaatdishes_convert_date($date) {
	return date("d-m-Y", strtotime($date));
}

function plaatdishes_ui_input($name, $size, $maxlength, $value, $readonly=false) {
	
	$page  = '<input ';
	$page .= 'type="text" ';
	$page .= 'id="'.$name.'" ';
	$page .= 'name="'.$name.'" ';
	$page .= 'value="'.$value.'" ';
	$page .= 'size='.$size.' ';
	$page .= 'maxlength='.$maxlength.' ';
	
	if ($readonly==true) {
		$page .= 'disabled="true" ';
	}
	
	$page .= '/>';

	return $page;
}

function plaatdishes_ui_checkbox($name, $value, $readonly=false) {

	$tmp = '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="1" ';
	
	if ($value==1) {
		$tmp .= ' checked="checked"';
	} 
	
	if ($readonly==1) {
		$tmp .= ' disabled="true"';
	} 
	
	$tmp .= '/>';
	
	return $tmp;	
}

/**
 * Function valid email address
 * @return true or false
 */
function validate_email($address) {

   return !preg_match("/[A-Za-z0-9_-]+([\.]{1}[A-Za-z0-9_-]+)*@[A-Za-z0-9-]+([\.]{1}[A-Za-z0-9-]+)+/",$address);
}


// ----------------------------
// THE END
// ----------------------------



