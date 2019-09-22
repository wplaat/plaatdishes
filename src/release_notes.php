<?php

/* 
**  ============
**  plaatdishes
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
** NOTES
** ---------------------
*/

$note[2] = '<div class="subparagraph">Version 0.2 (22-09-2019)</div>
<div class="large_text">
<ul>
<li>Added email notification</li>
<li>Added release notes page</li>
<li>Added overview chart page</li>
</ul>
</div>';

$note[1] = '<div class="subparagraph">Version 0.1 (20-09-2019)</div>
<div class="large_text">
<ul>
<li>Initial version</li>
</ul>
</div>';

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatdishes_release_notes_page(){

  global $pid;
  global $id;
  global $note;
  
  $page  = '<h1>Release Notes</h1>';
   
  $page .= $note[$id];
  
  $page .= '<div class="nav">';
  $page .= plaatdishes_link('pid='.$pid.'&eid='.EVENT_PREV.'&id='.$id, t('LINK_PREV'));
  $page .= plaatdishes_link('pid='.PAGE_HOME, t('LINK_HOME'), 'home');
  $page .= plaatdishes_link('pid='.$pid.'&eid='.EVENT_NEXT.'&id='.$id, t('LINK_NEXT'));
  $page .= '</div>';

  return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

/**
 * Help handler
 */
function plaatdishes_release_notes() {

	/* input */
	global $max;
	global $pid;
	global $eid;
	global $id;
	global $note;

	if($id==0) {
		$id = sizeof($note);
	}
	
	/* Event handler */
	switch ($eid) {
      
		case EVENT_NEXT:
			if ($id<sizeof($note)) {
				$id++;
			}
			break;

		case EVENT_PREV:
			if ($id>1) {
				$id--;
			}
			break;
   }

	/* Page handler */
	switch ($pid) {

		case PAGE_RELEASE_NOTES:
			return plaatdishes_release_notes_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
