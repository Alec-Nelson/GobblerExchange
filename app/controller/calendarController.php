<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a calendarController and route it
$sc = new CalendarController();
$sc->route($action);

class CalendarController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'calendar':
				$this->calendar();
				break;

			case 'newEvent':
				$this->newEvent();
				break;
			case 'newEvent_submit':
				$this->newEvent_submit();
				break;

			case 'editEvent':
				$eventId = $_GET['eventId'];
				$this->editEvent($eventId);
				break;
			case 'editEvent_submit':
				$this->editEvent_submit();
				break;

			case 'deleteEvent':
				$this->deleteEvent();
				break;
		}
	}

	/* Opens the calendar view page for a particular group
	 * Prereq (POST variables): groupId
	 * Page variables: $events - list of events in the calendar
	 */
    public function calendar() {
		// SiteController::loggedInCheck();

		$groupId = $_SESSION['groupId'];

		//do nothing if the user didn't select a group first
		if ($groupId == 0){
			header('Location: '.BASE_URL);
		}

		//get calendar id from group
		$group = Group::loadById($groupId);
		$calendarId = $group->get('calendarId');
		$calendar = Calendar::loadById($calendarId);

		$year = date("Y", time());

		//retrieve events
		$events = $calendar->getAllEventsAfterToday();

		include_once SYSTEM_PATH.'/view/calendar.html';
	}

	/* Opens the form to fill out a new event
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newEvent() {
		//SiteController::loggedInCheck();

		include_once SYSTEM_PATH.'/view/createCalendarEvent.html';
	}

	/* Submits the new event form
	 * Prereq (POST variables): Cancel, location, description, date, time, title, am, pm
	 * Page variables: N/A
	 */
	public function newEvent_submit() {
		//SiteController::loggedInCheck();

		//user canceled new event
		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/calendar');											//TODO: update location?
			exit();
		}

		$location = $_POST['location'];
		$description = $_POST['description'];
		$authorId = $_SESSION['userId'];
		$title = $_POST['title'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$ampm =$_POST['ampm'];

		if($ampm == "pm") $timestamp = Event::convertToSQLDateTime($date, $time, true);
		else $timestamp = Event::convertToSQLDateTime($date, $time, false);

		//get calendar id from group
		$groupId = $_SESSION['groupId'];
		$group = Group::loadById($groupId);
		$calendarId = $group->get('calendarId');

		$event = new Event();
		$event->set('timestamp', $timestamp);
		$event->set('location', $location);
		$event->set('description', $description);
		$event->set('calendarId', $calendarId);
		$event->set('title', $title);
		$event->set('userId', $authorId);
		$event->save();

		header('Location: '.BASE_URL.'/calendar');
		exit();
	}

	/* Brings up form for editing an event
	 * Prereq (POST variables): edit (event id)
	 * Page variables: location, description, date, time, title
	 */
	public function editEvent($eventId){
        //SiteController::loggedInCheck();

        //retrieve the event
		$event= Event::loadById($eventId);

        //retrieve event author's username
		$authorid = $event->get('userId');

		//check if author of the event is the logged in user
		// if($_SESSION['userId'] != $authorid){
		// 	$_SESSION['info'] = "You can only edit events of which you are the author of.";
		// 	header('Location: '.BASE_URL);
		// 	exit();
		// } else {
			//allow access to edit event
			$id = $event->get('id');
			$title = $event->get('title');
			$location = $event->get('location');
			$description = $event->get('description');
			$date = $event->getDate();												//TODO worry about am/pm
			$time = $event->getTime();
			$isPM = $event->isPM();
			$ampm = "am";
			if($isPM) $ampm = "pm";
			$title = $event->get('title');
			include_once SYSTEM_PATH.'/view/editCalendarEvent.html';
		//}
	}

	/* Publishes edited event
	 * Prereq (POST variables): Cancel, eventId, location, description, date, time, title
	 * Page variables: N/A
	 */
	public function editEvent_submit(){
        //SiteController::loggedInCheck();

		//user canceled new event
		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/calendar');
			exit();
		}

		$eventId = $_POST['eventId'];
		$event = Event::loadById($eventId);

		if (isset($_POST['Delete'])) {
			// if($event->get('userId') == $_SESSION['userId']){
				$event->delete();
			// } else {
			//	$_SESSION['info'] = "You can only delete events you have created.";
			// }
			header('Location: '.BASE_URL.'/calendar');
			exit();
		}

		$location = $_POST['location'];
		$description = $_POST['description'];
		$title = $_POST['title'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$ampm =$_POST['ampm'];
		
		if($ampm == "pm") $timestamp = Event::convertToSQLDateTime($date, $time, true);
		else $timestamp = Event::convertToSQLDateTime($date, $time, false);

		$event->set('location', $location);
		$event->set('description', $description);
		$event->set('timestamp', $timestamp);
		$event->set('title', $title);
		$event->save();

		header('Location: '.BASE_URL.'/calendar');
	}

	/* Deletes an event, if the author is the one deleting it
	 * Prereq (POST variables): delete (event id)
	 * Page variables: SESSION[info]
	 */
	public function deleteEvent(){
    	SiteController::loggedInCheck();

		$eventid = $_POST['delete'];
		$event_row = Event::loadById($eventid);
		$event_author_id = $event_row->get('userId');
		$event_author = User::loadById($event_author_id);

		//user is the author of the event, allow delete
		if($event_author->get('username') == $_SESSION['username']){
			$event_row->delete();
		} else {
			$_SESSION['info'] = "You can only delete events you have created.";
		}

		//refresh page
		header('Location: '.BASE_URL.'/calendar');
	}
}
