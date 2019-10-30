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

$to = plaatdishes_post("to", 0);
$amount = plaatdishes_post("amount", 0);
$description = plaatdishes_post("description", "");

/*
** ---------------------
** ACTION
** ---------------------
*/

function plaatdishes_pay() {
	
	/* input */
	global $uid;	
	global $to;
	global $amount;
	global $description;
	global $session;
		
	$user = plaatdishes_db_users_session($session);	
	$user_amount = plaatdishes_db_transaction_total($user->uid);			
	$user_to = plaatdishes_db_users($to);
	
	$page = "";
	
	if ($amount<=0) {	
		$page = t('AMOUNT_TO_SMALL');
		
	} else if ($amount>8) {
		$page = t('AMOUNT_TO_BIG');
	
	} else if ($user->uid==0) {	
		$page = t('USER_DOES_NOT_EXIST');
				
	} else if (($user->admin==0) && ($user_amount<$amount)) {			
		$page = t('TOO_LESS_COINS');		
			
	} else {
		
		plaatdishes_db_transaction_insert($user_to->uid, $amount, $description);
		
		if ($user->admin==0) {
			plaatdishes_db_transaction_insert($user->uid, ($amount*-1), $description);
		}
		$page = t('PAYMENT_DONE');
	} 			
	return $page;
}

/*
** ---------------------
** UTILS
** ---------------------
*/

function plaatdishes_users($uid=0) {

	$page ='<select id="to" name="to" class="dropdown-select">';
	
	$sql = 'select uid, name from users where active=1 and uid!='.$uid. ' order by uid';
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

function plaatdishes_amount() {

	$page ='<select id="amount" name="amount" class="dropdown-select">';
	$page.='<option value="0" selected="selected">0</option>';
	$page.='<option value="1">1</option>';
	$page.='<option value="2">2</option>';
	$page.='<option value="3">3</option>';
	$page.='<option value="4">4</option>';
	$page.='<option value="5">5</option>';
	$page.='<option value="6">6</option>';
	$page.='<option value="7">7</option>';
	$page.='<option value="8">8</option>';		
	$page.='</select>';	
    
	return $page;
}

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_transaction_page() {
	
	global $session;
	
	$user = plaatdishes_db_users_session($session);	
	
	$page = '<h1>'.t('LABEL_TRANSACTION').'</h1>';
	
	$page .= '<table>';
	$page .= '<tr>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_FROM').': ';	
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_TO').': ';
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_AMOUNT').': ';
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= t('LABEL_DESCRIPTION').': ';
	$page .= '</td>';
				
	$page .= '<td style="padding-right: 10px;">';		
	$page .= '</td>';
	
	$page .= '</tr>';
	$page .= '<tr>';
	
	$page .= '<td>';
	$page .= plaatdishes_ui_input('from', 20, 20, $user->name, true);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= plaatdishes_users($user->uid);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= plaatdishes_amount();
	$page .= '</td>';
		
	$page .= '<td>';
	$page .= plaatdishes_ui_input('description', 20, 20, "", false);
	$page .= '</td>';
	
	$page .= '<td>';
	$page .= plaatdishes_link('pid='.PAGE_TRANSACTION.'&eid='.EVENT_PAY, t('LINK_PAY'));
	$page .= '</td>';
	
	$page .= '</tr>';
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

function plaatdishes_transaction() {

	/* input */
    global $pid;  
	global $eid;  

	$error="";
	
	switch ($eid) {
		
		case EVENT_PAY:
			$error = plaatdishes_pay();
			break;
	}
	
	/* Page handler */
	switch ($pid) {

		case PAGE_TRANSACTION:
			return plaatdishes_transaction_page().$error;
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
