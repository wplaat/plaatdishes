<?php

/* 
**  ===========
**  plaatdishes
**  ===========
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

$user_name = plaatdishes_post("user_name", "");
$user_email = plaatdishes_post("user_email", "");
$user_active = plaatdishes_post("user_active", 1);
$user_username = plaatdishes_post("user_username", "");
$user_password  = plaatdishes_post("user_password", "");
$user_admin = plaatdishes_post("user_active", 0);

/*
** ---------------------
** ACTION
** ---------------------
*/

function plaatdishes_user_save() {
	
	/* input */
	global $uid;
	
	global $user_name;
	global $user_email;
	global $user_username;
	global $user_password;
	global $user_role;
	
	/* output */
	global $pid;
	
	$page = '';
		
	$user = plaatdishes_db_users($uid);
	
	if (strlen($user_name)<3) {

		$page = t('NAME_TO_SHORT');
		
	} else if (validate_email($user_email)) {
		
		$page = t('EMAIL_INVALID');
		
	} else if (strlen($user_username)<5) {
		
		$page =  t('USERNAME_TO_SHORT');
		
	//} else if (isset($user->username) && ($user->username!=$user_username) && (plaatdishes_db_member_username($user_username)>0)) {
	
	//	$page .=  t('USERNAME_EXIST');
		
	} else if ((strlen($user_password)>0) && (strlen($user_password)<5)) {

		$page = t('PASSWORD_TO_SHORT');
			
	} else {
	
		if ($uid>0) {
			
			plaatdishes_db_users_update2($user_username, $user_password, $uid);
									
			$user->email = $user_email;			
			$user->name = $user_name;
			$user->active = $user_active;
			$user->username = $user_username;
			
			plaatdishes_db_user_update($data);			
		
		} else  {
			
			/* Insert new member */
			$member_id = plaatdishes_db_member_insert($user_username, $user_password);
			
			/* Insert new user */
			plaatdishes_db_user_insert($member_id, $user_name, $user_email, $user_role);		
		}
		
		$page = t('USER_SAVED');
		
		$pid = PAGE_USERS;
	} 	
	
	return $page;
}

function plaatdishes_user_cancel_do() {

	/* input */
	global $user;
	
	/* output */
	global $pid;
	
	/* Goto to previous form */		
	if ($user->role_id==ROLE_ADMINISTRATOR) {
	
		$pid = PAGE_USERLIST;
		
	} else {
	
		$pid = PAGE_GENERAL;
	}	
}

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_user_page() {

	global $uid;
	global $error;
	
	$page = '<h1>'.t('LABEL_USER').'</h1>';

    $user = plaatdishes_db_users($uid);
	
	$page .= $error;
	
	$page .= '<table>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_ID').'</td>';
	$page .= '<td>'.$user->uid.'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_NAME').'</td>';
	$page .= '<td>'.plaatdishes_ui_input('user_name', 20, 20, $user->name).'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_EMAIL').'</td>';
	$page .= '<td>'.plaatdishes_ui_input('user_email', 20, 20, $user->email).'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_ACTIVE').'</td>';
	$page .= '<td>'.plaatdishes_ui_checkbox('user_active', $user->active).'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_USERNAME').'</td>';
	$page .= '<td>'.plaatdishes_ui_input('user_username', 20, 20, $user->username).'</td>';
	$page .= '</tr>';
		
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_PASSWORD').'</td>';
	$page .= '<td>'.plaatdishes_ui_input('user_password', 30, 30, "").'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_LAST_LOGIN').'</td>';
	$page .= '<td>'.$user->last_login.'</td>';
	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '<td>'.t('LABEL_ADMIN').'</td>';
	$page .= '<td>'.plaatdishes_ui_checkbox('user_admin', $user->admin).'</td>';
	$page .= '</tr>';
	
	$page .= '</table>';
	
	$page .= '<div class="nav">';	
	$page .= plaatdishes_link('pid='.PAGE_USERS, t('LINK_CANCEL'));
	$page .= plaatdishes_link('pid='.PAGE_USER.'&uid='.$uid.'&eid='.EVENT_SAVE, t('LINK_SAVE'));
	$page .=  '</div>';	
	
	return $page;
}

function plaatdishes_users_page() {

	$page = '<h1>'.t('LABEL_USERS').'</h1>';

	$page .= '<table>';
	$page .= '<tr>';
	$page .= '<th>'.t('LABEL_ID').'</th>';
	$page .= '<th>'.t('LABEL_USERNAME').'</th>';
	$page .= '<th>'.t('LABEL_LAST_LOGIN').'</th>';
	$page .= '<th>'.t('LABEL_ADMIN').'</th>';
	$page .= '</tr>';

		
	$sql = 'select uid, name, email, username, last_login, admin from users order by uid';
    $result = plaatdishes_db_query($sql);	
    while ($data = plaatdishes_db_fetch_object($result)) {
		$page .= '<tr>';
				
		$page .= '<td>';
		$page .= plaatdishes_normal_link('pid='.PAGE_USER.'&uid='.$data->uid, $data->uid);
		$page .= '</td>';		

		$page .= '<td>';
		$page .= $data->username;
		$page .= '</td>';		
				
		$page .= '<td>';
		$page .= $data->last_login;
		$page .= '</td>';		
		
		$page .= '<td>';
		$page .= $data->admin;
		$page .= '</td>';		
			
		$page .= '<tr>';
	}
	$page .= '</table>';

	$page .= '<div class="nav">';
	$page .= plaatdishes_link('pid='.PAGE_HOME, t('LINK_HOME'));
	$page .=  '</div>';

	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

function plaatdishes_users() {

	/* input */
    global $pid;  
	global $eid;  

	switch ($eid) {
		
		case EVENT_SAVE:
			$error = plaatdishes_user_save();
			break;
	}
	
	/* Page handler */
	switch ($pid) {

		case PAGE_USER:
			return plaatdishes_user_page();
			break;
			
		case PAGE_USERS:
			return plaatdishes_users_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
