<?php
  $date      = $_GET['date'];
  $startTime = $_GET['startTime'];
  $endTime   = $_GET['endTime'];
  $subject   = $_GET['subject'];
  $desc      = $_GET['desc'];

  $ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:Strike Calendar
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "strikebrewingco.com
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:".$date."T".$startTime."00Z
DTEND:".$date."T".$endTime."00Z
SUMMARY:".$subject."
DESCRIPTION:".$desc."
END:VEVENT
END:VCALENDAR";

  //set correct content-type-header
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: inline; filename=StrikeFollowUp.ics');
  echo $ical;
  exit;
?>