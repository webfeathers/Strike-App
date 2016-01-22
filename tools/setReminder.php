<?php
// Fetch vars
$event = array(
	'id' => $_GET['id'],
	'title' => $_GET['title'],
	'address' => $_GET['address'],
	'description' => $_GET['description'],
	'datestart' => $_GET['datestart'],
	'dateend' => $_GET['dateend'],
	'address' => $_GET['stage']
);

// iCal date format: yyyymmddThhiissZ
// PHP equiv format: Ymd\This

// The Function

function dateToCal($time) {
	return date('Ymd\This', $time) . 'Z';
}

// Build the ics file
$ical = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTEND:' . dateToCal($event['dateend']) . '
UID:' . md5($event['title']) . '
DTSTAMP:' . time() . '
LOCATION:' . addslashes($event['address']) . '
DESCRIPTION:' . addslashes($event['description']) . '
URL;VALUE=URI:http://mohawkaustin.com/events/' . $event['id'] . '
SUMMARY:' . addslashes($event['title']) . '
DTSTART:' . dateToCal($event['datestart']) . '
END:VEVENT
END:VCALENDAR';

//set correct content-type-header
if($event['id']){
	header('Content-type: text/calendar; charset=utf-8');
	header('Content-Disposition: attachment; filename=mohawk-event.ics');
	echo $ical;
} else {
	// If $id isn't set, then kick the user back to home. Do not pass go, and do not collect $200.
	header('Location: /');
}
?>