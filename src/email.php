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

function plaatdishes_email_notification() {

	$subject =  "PlaatDishes ".t("LABEL_OVERVIEW")." ".date('d-m-Y');
		
	$body  = "<html>";
	$body .= "<body>";
	$body .= "<h1>PlaatDishes ".t("LABEL_OVERVIEW")." ".date('d-m-Y')."</h1>";

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="15%" align="left">'.t('LABEL_NAME').'</th>';
	$body .= '<th width="15%" align="left">'.t('LABEL_COINS').'</th>';
	$body .= '<th width="15%" align="left">'.t('LABEL_AMOUNT').'</th>';
	$body .= '<th width="25%" align="left">'.t('LABEL_DATE').'</th>';
	$body .= '<th width="30%" align="left">'.t('LABEL_EXTRA').'</th>';
	$body .= '</tr>';
		
	$count=0;
		
	$sql = 'select a.uid, sum(a.total) as total, count(a.uid) as amount, b.name from dishes a, users b where a.uid=b.uid and b.active=1 and a.total>0 group by a.uid order by total';
	$result = plaatdishes_db_query($sql);	
	while ($data = plaatdishes_db_fetch_object($result)) {
	
		$body .= '<tr>';
	
		$body .= '<td>';
		$body .= $data->name;
		$body .= '</td>';		
	
		$body .= '<td>';
		$body .= $data->total;
		$body .= '</td>';	
		
		$body .= '<td>';
		$body .= $data->amount;
		$body .= '</td>';
	
		$body .= '<td>';
		$sql2 = 'select date from dishes where uid='.$data->uid.' order by date desc limit 0,1';
		$result2 = plaatdishes_db_query($sql2);	
		$data2 = plaatdishes_db_fetch_object($result2);
		$body .= plaatdishes_convert_date($data2->date);
		$body .= '</td>';	
	
		$body .= '<td>';
		if ($count==0) {
			$body .= t('LABEL_DISH_HELPER');
			$user = $data->uid;
			$count=1;
		} 
		$body .= '</td>';		
		$body .= '</tr>';
	}
	$body .= '</table>';

	plaatdishes_send_email($subject, $body);		
}

?>