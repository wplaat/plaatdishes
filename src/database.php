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
** ---------------------
** SETTINGS
** ---------------------
*/

define('DEBUG', 0);
$db = "";

/*
** ---------------------
** GENERAL
** ---------------------
*/

/**
 * connect to database
 * @param $dbhost database hostname
 * @param $dbuser database username
 * @param $dbpass database password
 * @param $dbname database name
 * @return connect result (true = successfull connected | false = connection failed)
 */
function plaatdishes_db_connect($dbhost, $dbuser, $dbpass, $dbname) {

	global $db;

    $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);	
	if (mysqli_connect_errno()) {
		plaatdishes_db_error();
		return false;		
	}
	return true;
}

/**
 * Disconnect from database  
 * @return disconnect result
 */
function plaatdishes_db_close() {

	global $db;

	mysqli_close($db);

	return true;
}

/**
 * Show SQL error 
 * @return HTML formatted SQL error
 */
function plaatdishes_db_error() {

	if (DEBUG == 1) {
		echo mysqli_connect_error(). "<br/>\n\r";
	}
}

/**
 * Count queries 
 * @return queries count
 */
$query_count=0;
function plaatdishes_db_count() {

	global $query_count;
	return $query_count;
}

/**
 * Execute database multi query
 */
function plaatdishes_db_multi_query($queries) {

	$tokens = @preg_split("/;/", $queries);
	foreach ($tokens as $token) {
	
		$token=trim($token);
		if (strlen($token)>3) {
			plaatdishes_db_query($token);		
		}
	}
}

/**
 * Execute database query
 * @param $query SQL query with will be executed.
 * @return Database result
 */
function plaatdishes_db_query($query) {
			
	global $query_count;
	global $db;
	
	$query_count++;

	if (DEBUG == 1) {
		echo $query."<br/>\r\n";
	}

	$result = @mysqli_query($db, $query);

	if (!$result) {
		plaatdishes_db_error();		
	}
	
	return $result;
}

/**
 * escap database string
 * @param $data  input.
 * @return $data escaped
 */
function plaatdishes_db_escape($data) {

	global $db;
	
	return mysqli_real_escape_string($db, $data);
}

/**
 * Fetch query result 
 * @return mysql data set if any
 */
function plaatdishes_db_fetch_object($result) {
	
	$row="";
	
	if (isset($result)) {	
		$row = $result->fetch_object();
	}
	return $row;
}

/**
 * Return number of rows
 * @return number of row in dataset
 */
function plaatdishes_db_num_rows($result) {
	
	return mysqli_num_rows($result);
}

/*
** ---------------------
** DB UPDATE
** ---------------------
*/

function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Execute SQL script
 * @param $version Version of sql patch file
 */
function plaatdishes_db_execute_sql_file($version) {

    $filename = 'database/patch-'.$version.'.sql';
    $commands = file_get_contents($filename);
	 
    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--') ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";\n", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)) {
				if (DEBUG == 1) {
					echo $command."<br/>\n\r";
				}
            $success += (@plaatdishes_db_query($command)==false ? 0 : 1);
            $total += 1;
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}

/**
 * Check db version and upgrade if needed!
 */
function plaatdishes_db_check_version() {
	
	// Execute SQL base sql script if needed!
	$sql = "select 1 FROM config limit 1" ;
	$result = plaatdishes_db_query($sql);
	if (!$result)  {
		plaatdishes_db_execute_sql_file("0.1");
	}
		
	// Execute SQL path script v0.2 if needed
	$value = plaatdishes_db_config_value('database_version', CATEGORY_GENERAL);
	if ($value=="0.1")  { 
		plaatdishes_db_execute_sql_file("0.2");
	}
   
	// Execute SQL path script v0.2 if needed
	$value = plaatdishes_db_config_value('database_version', CATEGORY_GENERAL);
	if ($value=="0.2")  { 
		plaatdishes_db_execute_sql_file("0.3");
	}
}

/*
** ---------------------
** SESSION
** ---------------------
*/

function plaatdishes_db_get_session($ip, $new=false) {

   $sql = 'select sid, timestamp, session_id, requests from session where ip="'.$ip.'"';
   $result = plaatdishes_db_query($sql);
   $data = plaatdishes_db_fetch_object($result);

   $session_id = "";
   if ( isset($data->sid) ) {   
	
		$session_id = $data->session_id;
		$requests = $data->requests;
	
		if (($new==true) || ((time()-strtotime($data->timestamp))>(60*15))) {		
			$session_id = md5(date('Y-m-d H:i:s'));
		}

		$now = date('Y-m-d H:i:s');
		$sql = 'update session set timestamp="'.$now.'", session_id="'.$session_id.'", requests='.++$requests.' where sid="'.$data->sid.'"';
	    plaatdishes_db_query($sql);
	  
   } else {

		$now = date('Y-m-d H:i:s');
		$sql = 'insert into session (timestamp, ip, requests, language, theme, session_id) value ("'.$now.'", "'.$ip.'", 1, "en", "light", "'.$session_id.'")';
		plaatdishes_db_query($sql);
	}

   return $session_id;
}

/*
** ---------------------
** DISHES
** ---------------------
*/

function plaatdishes_db_dishes_check() {

	$page = "";
	$sql = 'select did, date, pid, task1, task2, task3, task4, total, hash from dishes';
    $result = plaatdishes_db_query($sql);
    
	while($data = plaatdishes_db_fetch_object($result)) {
		$key = $data->date."-".$data->pid."-".$data->task1."-".$data->task2."-".$data->task3."-".$data->task4."-".$data->total;
		$hash = md5($key);
			
		if ($hash!=$data->hash) {
			$page .= "Record ".$data->did." is invalid!<br/>";
		}
	}
    return $page;
}
   
function plaatdishes_db_dishes_insert($pid, $task1, $task2, $task3, $task4) {
 
    $date = date('Y-m-d');
	
	$total = $task1 + $task2 + $task3 + $task4;
	
	$key = $date."-".$pid."-".$task1."-".$task2."-".$task3."-".$task4."-".$total;
	$hash = md5($key);
	
    $query  = 'insert into dishes (date, pid, task1, task2, task3, task4, total, hash)';
	$query .= 'values ("'.$date.'",'.$pid.','.$task1.','.$task2.','.$task3.','.$task4.','.$total.',"'.$hash.'")';
			
	return plaatdishes_db_query($query);
}

/*
** ---------------------
** USERS
** ---------------------
*/

function plaatdishes_db_users($pid) {

	$sql = 'select pid, name, email, active where pid='.$pid;
	$result = plaatdishes_db_query($sql);
	return  plaatdishes_db_fetch_object($result);
}

/*
** ---------------------
** CONFIG
** ---------------------
*/

function plaatdishes_db_config_value($key, $category=CATEGORY_GENERAL) {

	$value="";
	
	$sql = 'select value from config where token="'.$key.'" and category='.$category;
	$result = plaatdishes_db_query($sql);
	$data = plaatdishes_db_fetch_object($result);

	if (isset($data->value)) {
		$value = $data ->value;
	}

   return $value;
}

function plaatdishes_db_config($key, $category=0) {

   $sql = 'select id, category, token, value from config where token="'.$key.'" and category='.$category;
   $result = plaatdishes_db_query($sql);
  
   return plaatdishes_db_fetch_object($result);
}

function plaatdishes_db_config_update($config) {

  $now = date('Y-m-d H:i:s');
  $query = 'update config set value="'.$config->value.'", date="'.$now.'" where id='.$config->id;		
  
  return plaatdishes_db_query($query);
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
