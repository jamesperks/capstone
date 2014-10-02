<?php
require('inc/header.php');
require('inc/auth.php');
/* draws a calendar */
function draw_calendar($month,$year, $db){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar table-bordered s100">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><th class="calendar-day-head">'.implode('</th><th class="calendar-day-head">',$headings).'</th></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day" valign="top">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div><div class="clearfix"></div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
            $stmt=$db->prepare("SELECT * FROM class WHERE studentID=? AND day=? ORDER BY start");
            $stmt->execute(array($_SESSION['userid'], $days_in_this_week-1));
            $data = $stmt->fetchAll();
            foreach($data as $row) {
                $time = date('g:iA', $row['start']);
                $calendar .= '<p class="calendar-class"><a href="class.php?id='.$row['id'].'">'.$time.' '.$row['name'].'</a></p>';
            }
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}
?>
    <div class="container">

        <div class="starter-template">
            <?php
            $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $month = array(date('n'), date('F'));
            $year = date('Y');
            echo '<h2>'.$month[1].' '.$year.'</h2>';
            echo draw_calendar($month[0],$year,$db);
            ?>
        </div>
    </div>
<?php require('inc/footer.php'); ?>