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

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_overview_page() {

// input
	global $pid;
	global $date;
		
	list($year, $month, $day) = explode("-", $date);	
	$day = ltrim($day ,'0');
	$month = ltrim($month ,'0');
	$current_date = mktime(0, 0, 0, $month, $day, $year);  
	
	$step = 60*60*24;
	$data = "";
	
	for ($i=31; $i>=0; $i--) {
		
		$sql1 = 'select pid from users where active=1 order by pid';
		$result1 = plaatdishes_db_query($sql1);
		
		$first=true;
		while ($row1 = plaatdishes_db_fetch_object($result1)) {
			
			$timestamp = date("Y-m-d", $current_date-($step*$i));	
			$sql2 = 'select date, sum(total) as total from dishes where date<="'.$timestamp.'" and pid='.$row1->pid;		
			$result2 = plaatdishes_db_query($sql2);
					
			while ($row2 = plaatdishes_db_fetch_object($result2)) {
							
				if ($first==true) {
					if (strlen($data)>0) {
						$data .= ',';
					}		
					$data .= "['".plaatdishes_convert_date($timestamp)."'";					
					$first=false;
				} 
				
				$value = "null";
				if (isset($row2->total)) {
					$value = $row2->total;
				} 
				$data .= ",".$value;
			}	
		}
		if ($first==false) {
			$data .= ']';	
		}
	}
	
	if (strlen($data)==0) {
		$data .= '["00-00-0000"';
		
		$sql1 = 'select pid from users where active=1 order by pid';
		$result1 = plaatdishes_db_query($sql1);
		while ($node = plaatdishes_db_fetch_object($result1)) {
			$data .= ',null';
		}
		$data .= ']';
	}	
	
	$json2 = "[".$data."]";

	$page = '
		   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
			google.load("visualization", "1", {packages:["line"]});
			google.setOnLoadCallback(drawChart);

			function drawChart() {

				var data = new google.visualization.DataTable();
				data.addColumn("string", "Date");';
				
				$sql3 = 'select pid, name from users where active=1 order by pid';
				$result3 = plaatdishes_db_query($sql3);	
				while ($node = plaatdishes_db_fetch_object($result3)) {				
					$page .= 'data.addColumn("number","'.$node->name.'"); ';
				};
	
				$page .= "\r\n".'data.addRows('.$json2.');

				var options = {
					legend: { position: "top", textStyle: {fontSize: 10} },
					vAxis: {format: "decimal", title: ""},
					hAxis: {title: ""},
					backgroundColor: "transparent",
					chartArea: {
						backgroundColor: "transparent"
					}
				};

				var chart = new google.charts.Line(document.getElementById("chart_div"));
				chart.draw(data, google.charts.Line.convertOptions(options));
		}
		</script>';
	
	$page .= '<h1>'.t('LABEL_COINS').' '.t('LINK_OVERVIEW').'</h1>';

	$page .= '<div id="chart_div" style="width:950px; height:350px"></div>';
	
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

function plaatdishes_overview() {

  /* input */
  global $pid;  
	
	/* Page handler */
	switch ($pid) {

		case PAGE_OVERVIEW:
			return plaatdishes_overview_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
