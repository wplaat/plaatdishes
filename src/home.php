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
	
	$user = plaatdishes_db_users_session($session);
	
	if ($user->admin==1) {
	
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
		
		$amount = $task1 + $task2 + $task3 + $task4;
		
		plaatdishes_db_transaction_insert($uid, $amount, "Dishwash event");
		
		plaatdishes_email_notification();
	}
}

function plaatdishes_home_login_event() {

	global $pid;
	global $session;
	global $ip;
	
	$password = plaatdishes_post("password", "");
	$username = plaatdishes_post("username", "");

	$uid = plaatdishes_db_users_id($username, $password);

	if ($uid>0) {	
		$session = plaatdishes_db_get_session($ip, true);		
				
		$user = plaatdishes_db_users($uid);
		$user->last_login = date('Y-m-d H:i:s');
		$user->session_id = $session;
		plaatdishes_db_users_update($user);		
		
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
	global $session;
		
	$user = plaatdishes_db_users_session($session);
		
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
	$sql = 'SELECT a.uid, a.name, (SELECT count(b.uid) from dishes b where b.uid=a.uid) as amount, (SELECT sum(c.amount) from transaction c where c.uid=a.uid) as total FROM users a where a.active=1 order by total';
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
		if (isset($data2->date)) {
			$page .= plaatdishes_convert_date($data2->date);
		}
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
	
	if ($user->admin==1) {
	
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
	}
	
	$page .= '<br/>';
	
	$page .= '<p>';
	$page .= plaatdishes_link('pid='.PAGE_OVERVIEW, t('LINK_OVERVIEW'));	
	$page .= plaatdishes_link('pid='.PAGE_TRANSACTION, t('LINK_TRANSACTION'));		
	$page .= plaatdishes_link('pid='.PAGE_RELEASE_NOTES, t('LINK_RELEASE_NOTES'));	
		
	if ($user->admin==1) {
		$page .= plaatdishes_link('pid='.PAGE_USERS, t('LINK_USERS'));	
	}
	$page .= plaatdishes_link('pid='.PAGE_HOME_LOGIN, t('LINK_LOGOUT'));
	$page .= '</p>';
	
	$page .= '</div>';
		
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
