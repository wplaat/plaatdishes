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
$user_active = plaatdishes_post("user_active", 0);
$user_username = plaatdishes_post("user_username", "");
$user_password  = plaatdishes_post("user_password", "");
$user_admin = plaatdishes_post("user_admin", 0);

/*
** ---------------------
** ACTION
** ---------------------
*/

function plaatdishes_pay() {
	
	/* input */
	global $uid;
	
	global $user_name;
	global $user_email;
	global $user_username;
	global $user_active;
	global $user_admin;
	
	/* output */
	global $pid;
			
	$user = plaatdishes_db_users($uid);
	
	if (strlen($user_name)<3) {

		$page = t('NAME_TO_SHORT');
		
	} else if (validate_email($user_email)) {
		
		$page = t('EMAIL_INVALID');
		
	} else if (strlen($user_username)<3) {
		
		$page =  t('USERNAME_TO_SHORT');
		
	//} else if (isset($user->username) && ($user->username!=$user_username) && (plaatdishes_db_member_username($user_username)>0)) {
	
	//	$page .=  t('USERNAME_EXIST');
		
	} else {
	
		if ($uid==0) {
			
			$user->uid = plaatdishes_db_member_insert($user_username, $user_password);
		}
					
		$user->email = $user_email;			
		$user->name = $user_name;
		$user->active = $user_active;
		$user->username = $user_username;
		$user->admin = $user_admin;
			
		plaatdishes_db_users_update($user);			

		$pid = PAGE_USERS;
		$page = t('USER_SAVED');
	} 	
	
	return $page;
}


/*
** ---------------------
** UTILS
** ---------------------
*/

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
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_transaction_page() {

	
	$page = '<h1>'.t('LABEL_TRANSACTION').'</h1>';
	
	$page .= '<table>';
	$page .= '<tr>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_FROM').': ';
	//$page .= plaatdishes_users($uid);
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_TO').': ';
	//$page .= plaatdishes_task(1, 0);
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_AMOUNT').': ';
	//$page .= plaatdishes_task(2, 0);
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_DESCRIPTION').': ';
	//$page .= plaatdishes_task(3, 0);
	$page .= '</td>';
				
	$page .= '<td style="padding-right: 10px;">';	
	$page .= plaatdishes_link('pid='.PAGE_HOME.'&eid='.EVENT_PAY, t('LINK_PAY'));
	$page .= '</td>';
	
	$page .= '</tr>';
	$page .= '</table>';

	$page .= '<div class="nav">';
	$page .= plaatdishes_link('pid='.PAGE_HOME, t('LINK_CANCEL'));
	$page .=  '</div>';

	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

function plaatdishes_transaction() {

	/* input */
    global $pid;  
	global $eid;  

	$error="";
	
	switch ($eid) {
		
		case EVENT_PAY:
			plaatdishes_pay();
			break;
	}
	
	/* Page handler */
	switch ($pid) {

		case PAGE_TRANSACTION:
			return plaatdishes_transaction_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
