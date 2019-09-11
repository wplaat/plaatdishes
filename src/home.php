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

/**
 * @file
 * @brief contain home page
 */
 
/*
** ---------------------
** PARAMETERS
** ---------------------
*/

$name = plaatprotect_db_config_value('system_name', CATEGORY_GENERAL);
$version = plaatprotect_db_config_value('database_version', CATEGORY_GENERAL);

/*
** ---------------------
** EVENTS
** ---------------------
*/

function plaatprotect_home_save_event() {
	
	$user = plaatprotect_post("user", 0);
	$task1 = plaatprotect_post("task1", 0);
	$task2 = plaatprotect_post("task2", 0);
	$task3 = plaatprotect_post("task3", 0);
	$task4 = plaatprotect_post("task4", 0);
	
	if (($task1<0) && ($task1>2)) {
		return;
	}
	
	if (($task2<0) && ($task2>2)) {
		return;
	}
	
	if (($task3<0) && ($task3>2)) {
		return;
	}
	
	if (($task4<0) && ($task4>2)) {
		return;
	}
	
	if (($task1==0) && ($task2==0) && ($task3==0) && ($task4==0)) {
		return;
	}
	
	plaatprotect_db_dishes_insert($user, $task1, $task2, $task3, $task4);
}

function plaatprotect_home_login_event() {

	global $pid;
	global $session;
	global $ip;
	
	$password = plaatprotect_post("password", "");
	$username = plaatprotect_post("username", "");
			
	$home_password = plaatprotect_db_config_value('home_password',CATEGORY_GENERAL);
	$home_username = plaatprotect_db_config_value('home_username',CATEGORY_GENERAL);
	
	if (plaatprotect_password_verify($password, $home_password) && ($home_username==$username)) {
	
		$session = plaatprotect_db_get_session($ip, true);
		$pid = PAGE_HOME;
	} 
}

/*
** ---------------------
** UTILS
** ---------------------
*/

function plaatdishes_task($task, $item) {
		
	$values = array(0, 1, 2);

	$page ='<select id="task'.$task.'" name="task'.$task.'" class="dropdown-select">';
	
	foreach ($values as $value) {
		$page.='<option value="'.$value.'"';
		
		if ($item == $value) {
			$page .= ' selected="selected"';
		}
		$page .= '>'.$value.'</option>';
	}	
	$page.='</select>';
	
   return $page;
}


function plaatdishes_users($pid=0) {

	$page ='<select id="user" name="user" class="dropdown-select">';
	
	$sql = 'select pid, name from users order by pid';
    $result = plaatprotect_db_query($sql);	
	
	while ($data = plaatprotect_db_fetch_object($result)) {	
		$page.='<option value="'.$data->pid.'"';
		
		if ($data->pid == $pid) {
			$page .= ' selected="selected"';
		}
		$page .= '>'.$data->name.'</option>';
	}	
	$page.='</select>';
	
   return $page;
}

/*
** ---------------------------------------------------------------- 
** PAGE
** ---------------------------------------------------------------- 
*/

function plaatprotect_home_login_page() {

	// input	
	global $id;
	global $name;
	global $version;
			
	$page = '<h1>';
	$page .= t('TITLE').' ' ;
	$page .= '<span id="version">'.$version."</span>";
	if (strlen($name)>0) {
		$page .= ' ('.$name.') ';
	} 	
	$page .= '</h1>';

	$page .= '<fieldset>';
	
	$page .= '<br/>';
    $page .= '<label>'.t('LABEL_USERNAME').'</label>';
    $page .= '<input type="text" name="username" size="20" maxlength="20"/>';
    $page .= '<br/>';
	
    $page .= '<br/>';
    $page .= '<label>'.t('LABEL_PASSWORD').'</label>';
    $page .= '<input type="password" name="password" size="20" maxlength="20" autofocus/>';
    $page .= '<br/>';
   
    $page .= '<div class="nav">';   
    $page .= '<input type="hidden" name="token" value="pid='.PAGE_HOME_LOGIN.'&eid='.EVENT_LOGIN.'"/>';
    $page .= '<input type="submit" name="Submit" id="normal_link" value="'.t('LINK_LOGIN').'"/>';
    $page .= '</div>';
 	
	$page .= '</fieldset>';
	
	$page .= '<br/>';
	$page .= '<div class="upgrade" id="upgrade"></div>';
	$page .= '<script type="text/javascript" src="js/version1.js"></script>';
	
    return $page;
}

function plaatprotect_home_page() {

	// input	
	global $pid;
	global $name;
	global $version;
		
	$page = '<h1>';
	$page .= t('TITLE').' ';
	$page .= '<span id="version">'.$version."</span>";
	if (strlen($name)>0) {
		$page .= ' ('.$name.') ';
	} 	
	$page .= '</h1>';

	$page .= '<div class="home">';

	// ---------------------------
	
	$page .= '<div class="menu">';
		
	$page .= '<table>';
	$page .= '<tr>';
	$page .= '<th>'.t('LABEL_ID').'</th>';
	$page .= '<th>'.t('LABEL_NAME').'</th>';
	$page .= '<th>'.t('LABEL_SCORE').'</th>';
	$page .= '<th>'.t('LABEL_DATE').'</th>';
	$page .= '<th>'.t('LABEL_EXTRA').'</th>';
	$page .= '</tr>';
		
	$count = 0;
	$user = 0;
	$sql = 'select a.pid, sum(a.total) as total, b.name from dishes a, users b where a.pid=b.pid group by a.pid order by total';
    $result = plaatprotect_db_query($sql);	
    while ($data = plaatprotect_db_fetch_object($result)) {
		$page .= '<tr>';
		
		$page .= '<td>';
		$page .= $data->pid;
		$page .= '</td>';
		
		$page .= '<td>';
		$page .= $data->name;
		$page .= '</td>';		
		
		$page .= '<td>';
		$page .= $data->total;
		$page .= '</td>';	
		
		$page .= '<td>';
		$sql2 = 'select date from dishes where pid='.$data->pid.' order by did limit 0,1';
		$result2 = plaatprotect_db_query($sql2);	
		$data2 = plaatprotect_db_fetch_object($result2);
		$page .= plaatprotect_convert_date($data2->date);
		$page .= '</td>';	
		
		$page .= '<td>';
		if ($count==0) {
			$page .= t('LABEL_DISH_HELPER');
			$user = $data->pid;
			$count=1;
		} 
		$page .= '</td>';		
		$page .= '</tr>';
	}
	$page .= '</table>';
      	
	$page .= '</div>';
	
	
	$page .= '<br/>';
	
	$page .= '<table>';
	$page .= '<tr>';
	
	$page .= '<td>';
	$page .= t('LABEL_NAME').': ';
	$page .= plaatdishes_users($user);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= t('LABEL_DISH_SIZE').': ';
	$page .= plaatdishes_task(1, 0);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= t('LABEL_PREPARE_QUALITY').': ';
	$page .= plaatdishes_task(2, 0);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= t('LABEL_CLEANING_QUALITY').': ';
	$page .= plaatdishes_task(3, 0);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= t('LABEL_CLEANUP_QUALITY').': ';
	$page .= plaatdishes_task(4, 0);
	$page .= '</td>';
	
	$page .= '</tr>';
	$page .= '</table>';
	
	$page .= '<br/>';
	
	$page .= '<p>';
	$page .= plaatprotect_link('pid='.PAGE_HOME.'&eid='.EVENT_SAVE, t('LINK_SAVE'));
	$page .= plaatprotect_link('pid='.PAGE_HOME_LOGIN, t('LINK_LOGOUT'));
	$page .= '</p>';
	
	$page .= '</div>';
			
	$page .= plaatprotect_db_dishes_check();

	$page .= '<div class="upgrade" id="upgrade"></div>';
	$page .= '<script type="text/javascript" src="js/version1.js"></script>';
	
	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

function plaatprotect_home() {

	/* input */
	global $pid;
	global $eid;
	global $sid;
	
	/* Event handler */
	switch ($eid) {
	
		case EVENT_SAVE:
			plaatprotect_home_save_event();
			break;	

		case EVENT_LOGIN:
			plaatprotect_home_login_event();
			break;		
   }
		
	/* Page handler */
	switch ($pid) {
		
		case PAGE_HOME_LOGIN:
			return plaatprotect_home_login_page();
			break;
			
		case PAGE_HOME:
			return plaatprotect_home_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
