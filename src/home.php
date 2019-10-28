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
** PARAMETERS
** ---------------------
*/

$name = plaatdishes_db_config_value('system_name', CATEGORY_GENERAL);
$version = plaatdishes_db_config_value('database_version', CATEGORY_GENERAL);

/*
** ---------------------
** EVENTS
** ---------------------
*/

function plaatdishes_home_save_event() {
	
	$uid = plaatdishes_post("uid", 0);
	$task1 = plaatdishes_post("task1", 0);
	$task2 = plaatdishes_post("task2", 0);
	$task3 = plaatdishes_post("task3", 0);
	$task4 = plaatdishes_post("task4", 0);
	
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
	
	plaatdishes_db_dishes_insert($uid, $task1, $task2, $task3, $task4);
	
	plaatdishes_email_notification();
}

function plaatdishes_home_login_event() {

	global $pid;
	global $session;
	global $ip;
	
	$password = plaatdishes_post("password", "");
	$username = plaatdishes_post("username", "");

	$uid = plaatdishes_db_users_id($username, $password);

	if ($uid>0) {	
		$session = plaatdishes_db_get_session($ip, $uid, true);
		$pid = PAGE_HOME;
		
		$user = plaatdishes_db_users($uid);
		$user->last_login = date('Y-m-d H:i:s');
		plaatdishes_db_users_update($user);		
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

function plaatdishes_users($uid=0) {

	$page ='<select id="uid" name="uid" class="dropdown-select">';
	
	$sql = 'select uid, name from users where active=1 order by uid ';
    $result = plaatdishes_db_query($sql);	
	
	while ($data = plaatdishes_db_fetch_object($result)) {	
		$page.='<option value="'.$data->uid.'"';
		
		if ($data->uid == $uid) {
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

function plaatdishes_home_login_page() {

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

function plaatdishes_home_page() {

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
	$page .= '<th>'.t('LABEL_NAME').'</th>';
	$page .= '<th>'.t('LABEL_COINS').'</th>';
	$page .= '<th>'.t('LABEL_AMOUNT').'</th>';
	$page .= '<th>'.t('LABEL_DATE').'</th>';
	$page .= '<th>'.t('LABEL_EXTRA').'</th>';
	$page .= '</tr>';
		
	$count = 0;
	$uid = 0;
	$sql = 'select a.uid, sum(a.total) as total, count(a.uid) as amount, b.name from dishes a, users b where a.uid=b.uid and b.active=1 and a.total>0 group by a.uid order by total';
    $result = plaatdishes_db_query($sql);	
    while ($data = plaatdishes_db_fetch_object($result)) {
		$page .= '<tr>';
				
		$page .= '<td>';
		$page .= $data->name;
		$page .= '</td>';		
		
		$page .= '<td>';
		$page .= $data->total;
		$page .= '</td>';	
		
		$page .= '<td>';
		$page .= $data->amount;
		$page .= '</td>';	
		
		$page .= '<td>';
		$sql2 = 'select date from dishes where uid='.$data->uid.' order by date desc limit 0,1';
		$result2 = plaatdishes_db_query($sql2);	
		$data2 = plaatdishes_db_fetch_object($result2);
		$page .= plaatdishes_convert_date($data2->date);
		$page .= '</td>';	
		
		$page .= '<td>';
		if ($count==0) {
			$page .= t('LABEL_DISH_HELPER');
			$uid = $data->uid;
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
	
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_NAME').': ';
	$page .= plaatdishes_users($uid);
	$page .= '</td>';
	
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_DISH_SIZE').': ';
	$page .= plaatdishes_task(1, 0);
	$page .= '</td>';
	
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_PREPARE_QUALITY').': ';
	$page .= plaatdishes_task(2, 0);
	$page .= '</td>';
	
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_CLEANING_QUALITY').': ';
	$page .= plaatdishes_task(3, 0);
	$page .= '</td>';
	
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_CLEANUP_QUALITY').': ';
	$page .= plaatdishes_task(4, 0);
	$page .= '</td>';
	
	$page .= '<td style="padding-right: 10px;">';	
	$page .= plaatdishes_link('pid='.PAGE_HOME.'&eid='.EVENT_SAVE, t('LINK_SAVE'));
	$page .= '</td>';
	
	$page .= '</tr>';
	$page .= '</table>';
	
	$page .= '<br/>';
	
	$page .= '<p>';
	$page .= plaatdishes_link('pid='.PAGE_OVERVIEW, t('LINK_OVERVIEW'));	
	$page .= plaatdishes_link('pid='.PAGE_RELEASE_NOTES, t('LINK_RELEASE_NOTES'));	
	$page .= plaatdishes_link('pid='.PAGE_USERS, t('LINK_USERS'));	
	//$page .= plaatdishes_link('pid='.PAGE_HOME_LOGIN, t('LINK_LOGOUT'));
	$page .= '</p>';
	
	$page .= '</div>';
			
	$page .= plaatdishes_db_dishes_check();

	$page .= '<div class="upgrade" id="upgrade"></div>';
	$page .= '<script type="text/javascript" src="js/version1.js"></script>';
	
	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

function plaatdishes_home() {

	/* input */
	global $pid;
	global $eid;
	global $sid;
	
	/* Event handler */
	switch ($eid) {
	
		case EVENT_SAVE:
			plaatdishes_home_save_event();
			break;	

		case EVENT_LOGIN:
			plaatdishes_home_login_event();
			break;		
   }
		
	/* Page handler */
	switch ($pid) {
		
		case PAGE_HOME_LOGIN:
			return plaatdishes_home_login_page();
			break;
			
		case PAGE_HOME:
			return plaatdishes_home_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
