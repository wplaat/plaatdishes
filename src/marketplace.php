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

function plaatdishes_send_email($subject, $body) {

	$header  = "From: Plaatdishes\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
	$sql = "select email from users";
	$result = plaatdishes_db_query($sql);
	while ($row = plaatdishes_db_fetch_object($result)) {	
	
		if (isset($row->email)) {	
			mail($row->email, $subject, $body, $header);
		}
	}
}

function plaatdishes_email_buy_notification($mid, $now) {

	global $session;

	$subject =  "PlaatDishes order ".$now;
		
	$body  = "<html>";
	$body .= "<body>";
	$body .= "<h1>PlaatDishes Order ".$now."</h1>";

	$sql = 'select mid, description, image, price from market_place where mid='.$mid;
	$result = plaatdishes_db_query($sql);	
	$product = plaatdishes_db_fetch_object($result);
		
	$user = plaatdishes_db_users_session($session);	
	
	$body .= 'Customer: '.$user->name;
	$body .= '<br/>';
	$body .= 'Product: '.$product->description;
	$body .= '<br/>';
	$body .= 'Price: '.$product->price.' '.t('LABEL_EURO');
	$body .= '<br/>';
	$body .= 'Order Date: '.$now;
	$body .= '<br/>';
	
	plaatdishes_send_email($subject, $body);		
}


function plaatdishes_buy() {
		
	global $mid;
	global $session;
	
	$page = t('TOO_LESS_MONEY');

	$sql = 'select mid, description, image, price from market_place where mid='.$mid;
	$result = plaatdishes_db_query($sql);	
	$product = plaatdishes_db_fetch_object($result);
		
	$user = plaatdishes_db_users_session($session);	
		
	$sql2 = 'SELECT sum(price) as price from sales where uid='.$user->uid;
	$result2 = plaatdishes_db_query($sql2);	
	$data2 = plaatdishes_db_fetch_object($result2);
		
	$sql3 = 'SELECT a.uid, a.name, (SELECT count(b.uid) from dishes b where b.uid=a.uid) as amount, (SELECT sum(c.amount) from transaction c where c.uid=a.uid) as total FROM users a where a.active=1 and a.uid='.$user->uid;
    $result3 = plaatdishes_db_query($sql3);	
    $data3 = plaatdishes_db_fetch_object($result3);
	
	$money = ($data3->total*MONEY_CONVER_RATE);
	if (isset($data2->price)) {
		$money -= $data2->price;
	}
	
	if ($money>$product->price) {
			
		$now = date('Y-m-d H:i:s');
			
		$query  = 'insert into sales(mid, uid, price, timestamp) values ('.$mid.','.$user->uid.','.$product->price.',"'.$now.'")';
		plaatdishes_db_query($query);
		
		plaatdishes_email_buy_notification($mid, $now);
		
		$page = t('ITEM_ORDER');
	}
		
	return $page;
}

/*
** ---------------------
** UTILS
** ---------------------
*/

function plaatdishes_users($uid=0) {

	global $session;

	$user = plaatdishes_db_users_session($session);	

	$page ='<select id="to" name="to" class="dropdown-select">';
	
	$sql = 'select uid, name from users where active=1 and uid!='.$user->uid. ' order by uid';
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

function plaatdishes_amount($amount=0) {

	$page ='<select id="amount" name="amount" class="dropdown-select">';
	
	for ($i=0; $i<=8; $i++) {
		$page.='<option value="'.$i.'" ';
		if ($amount==$i) {
			$page .= 'selected="selected"';
		}
		$page.= '>'.$i.'</option>';
	}	
	$page.='</select>';	
    
	return $page;
}

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_buy_page() {

	global $mid;

	$page = '<h1>'.t('LABEL_MARKET_PLACE').'</h1>';

	$sql = 'select mid, description, price, image from market_place where mid='.$mid;
	$result = plaatdishes_db_query($sql);	
	$data = plaatdishes_db_fetch_object($result);
		
	$page .= '<img src="images\\'.$data->image.'" width="80" height="80">';
	$page .= '<br/>';
	$page .= $data->description;
	$page .= '<br/>';
	$page .= $data->price.' '.t('LABEL_EURO');
	$page .= '<br/>';
	$page .= '<br/>';

	$page .= '<p>';
	$page .= plaatdishes_link('pid='.PAGE_MARKET_PLACE.'&eid='.EVENT_BUY.'&mid='.$data->mid, t('LINK_BUY'));
	$page .= plaatdishes_link('pid='.PAGE_MARKET_PLACE, t('LINK_CANCEL'));	
	$page .= '</p>';
	
	return $page;
}

function plaatdishes_market_place_page($popup) {
	
	global $session;
	global $description;
	global $amount;
	global $to;
	
	$user = plaatdishes_db_users_session($session);	
	
	$page = '<h1>'.t('LABEL_MARKET_PLACE').'</h1>';
	
	if (strlen($popup)>0) {
		$page .= '<div class="upgrade">';
		$page .= $popup;
		$page .= '</div>';	
	}
	
	$page .= '<table>';
	$page .= '<tr>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= '<b>'.t('LABEL_IMAGE').'</b>: ';	
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= '<b>'.t('LABEL_DESCRIPTION').'</b>: ';
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= '<b>'.t('LABEL_PRICE').'</b>: ';
	$page .= '</td>';
		
	$page .= '<td style="padding-right: 10px;">';
	$page .= '<b>'.t('LABEL_ACTION').'</b>: ';
	$page .= '</td>';

	$page .= '</tr>';
	
	$page .= '<tr>';
	$page .= '</tr>';
	
	$sql = 'select mid, description, price, image from market_place order by price';
	$result = plaatdishes_db_query($sql);	
	while ($data = plaatdishes_db_fetch_object($result)) {
		
		$page .= '<tr>';
		
		$page .= '<td>';
		$page .= '<img src="images\\'.$data->image.'" width="80" height="80">';
		$page .= '</td>';
		
		$page .= '<td>';
		$page .= $data->description;
		$page .= '</td>';
		
		$page .= '<td>';
		$page .= $data->price.' '.t('LABEL_EURO');
		$page .= '</td>';
			
		$page .= '<td>';
		$page .= plaatdishes_link('pid='.PAGE_BUY.'&mid='.$data->mid, t('LINK_BUY'));
		$page .= '</td>';
		
		$page .= '</tr>';
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

function plaatdishes_market_place() {

	/* input */
    global $pid;  
	global $eid;  

	$popup="";
	
	switch ($eid) {
		
		case EVENT_BUY:
			$popup = plaatdishes_buy();
			break;
	}
	
	/* Page handler */
	switch ($pid) {

		case PAGE_BUY:
			return plaatdishes_buy_page();
			break;
			  
		case PAGE_MARKET_PLACE:
			return plaatdishes_market_place_page($popup);
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
