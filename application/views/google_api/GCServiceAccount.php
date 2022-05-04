<?php
 require_once 'application/views/google_api/vendor/autoload.php';
   session_start();	 	
/************************************************	
 The following 3 values an befound in the setting	
 for the application you created on Google 	 	
 Developers console.	 	 Developers console.
 The Key file should be placed in a location	 
 that is not accessable from the web. outside of 
 web root.	 
 	 	 
 In order to access your GA account you must	
 Add the Email address as a user at the 	
 ACCOUNT Level in the GA admin. 	 	
 ************************************************/
$key_file_location = '../JDocServer/servicekey/jdocweb-google-api.json';	 	


$client = new Google_Client();
$client->setAuthConfig($key_file_location);
$client->setScopes(['https://www.googleapis.com/auth/calendar']);



// separate additional scopes with a comma	 
$service = new Google_Service_Calendar($client);    

?>


<?php
//$calendar = $service->calendars->get('npoq1hnob9meush32kha5pk3p0');

//echo $calendar->getSummary();


/*$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);

if (count($results->getItems()) == 0) {
  print "No upcoming events found.\n";
} else {
  print "Upcoming events:\n";
  foreach ($results->getItems() as $event) {
    $start = $event->start->dateTime;
    if (empty($start)) {
      $start = $event->start->date;
    }
    printf("%s (%s)\n", $event->getSummary(), $start);
  }
}
*/





if($start_time==''||$start_time==null)
{
    $event = new Google_Service_Calendar_Event(array(
        'summary' => $titolo,
        //'location' => '800 Howard St., San Francisco, CA 94103',
        'description' => $descrizione,
        'start' => array(
          'date' => $start_date,
          'timeZone' => 'Europe/Rome',
        ),
        'end' => array(
          'date' => $end_date,
          'timeZone' => 'Europe/Rome',
        ),
    )); 
}
else
{
    $start_datetime=$start_date."T".$start_time;
    $end_datetime=$end_date."T23:59:59";
    $endTimeUnspecified=true;
    if($end_time!=''&&$end_time!=null)
    {
        $end_datetime=$end_date."T".$end_time;
        $endTimeUnspecified=false;
    }
    else
    {
        $timestamp = strtotime($start_time) + 60*60;
        $end_time = date('H:i:s', $timestamp);
        $end_datetime=$end_date."T".$end_time;
        $endTimeUnspecified=true;
    }
    $event = new Google_Service_Calendar_Event(array(
            'summary' => $titolo,
            //'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => $descrizione,
            'start' => array(
              'dateTime' => $start_datetime,
              'timeZone' => 'Europe/Rome',
            ),
            'end' => array(
              'dateTime' => $end_datetime,
              'timeZone' => 'Europe/Rome',
            ),
        )); 
}

/*$event = new Google_Service_Calendar_Event(array(
  'summary' => $titolo,
  //'location' => '800 Howard St., San Francisco, CA 94103',
  'description' => $descrizione,
  'start' => array(
    'dateTime' => $start_datetime,
    'timeZone' => 'Europe/Rome',
  ),
  'end' => array(
    'dateTime' => $end_datetime,
    'timeZone' => 'Europe/Rome',
  ),
  'endTimeUnspecified' => $endTimeUnspecified,
  /*'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=1'
  ),
  'attendees' => array(
    array('email' => 'lpage@example.com'),
    array('email' => 'sbrin@example.com'),
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));*/
$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);
?>
