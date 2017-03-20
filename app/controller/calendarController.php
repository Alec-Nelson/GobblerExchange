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
				$this->newEvent();
				break;

			case 'editEvent':
				$this->editEvent();
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

		//get calendar id from group
		// $groupId = $_POST['groupId'];
		$groupId = 1;

		$group = Group::loadById($groupId);
		$calendarId = $group->get('calendarId');

		//retrieve all events
        $calendar = Calendar::loadById($calendarId);
        $events = $calendar->getEvents();
		include_once SYSTEM_PATH.'/view/calendar.html';                            //TODO: make sure this is the right tpl
	}

	/* Opens the form to fill out a new event
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newEvent() {
		SiteController::loggedInCheck();

		include_once SYSTEM_PATH.'/view/newevent.tpl';								//TODO: check tpl name
	}

	/* Submits the new event form
	 * Prereq (POST variables): Cancel, location, description, date, time
	 * Page variables: N/A
	 */
	public function newEvent_submit() {
		SiteController::loggedInCheck();

		//user canceled new event
		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/calendar');											//TODO: update location?
			exit();
		}

		$location = $_POST['location'];
		$description = $_POST['description'];
		$time = $_POST['date'];
		$date = $_POST['time'];														//TODO worry about am/pm
		$timestamp = Event::convertToSQLDateTime($date, $time);
		$author = $_SESSION['username'];

		//get calendar id from group
		$groupId = $_POST['groupId'];
		$group = Group::loadById($groupId);
		$calendarId = $group->get('calendarId');

		//get author's id
		$user_row = User::loadByUsername($author);
		$userid = $user_row->get('id');

		$event = new Event();
		$event->set('timestamp', $timestamp);
		$event->set('userId', $userid);
		$event->set('location', $location);
		$event->set('description', $description);
		$event->set('calendarId', $calendarId);
		$event->save();

		header('Location: '.BASE_URL.'/calendar');
		exit();
	}

	/* Brings up form for editing an event
	 * Prereq (POST variables): edit (event id)
	 * Page variables: location, description, date, time
	 */
	public function editEvent(){
        SiteController::loggedInCheck();

        //retrieve the event
		$eventid = $_POST['edit'];
		$event_row = Event::loadById($eventid);

        //retrieve event author's username
		$authorid = $event_row->get('userId');
		$user = User::loadById($authorid);
		$username = $user->get('username');

		//check if author of the event is the logged in user
		if($_SESSION['username'] != $username){
			$_SESSION['info'] = "You can only edit events of which you are the author of.";
			header('Location: '.BASE_URL);
			exit();
		} else {
			//allow access to edit event
			$location = $event_row->get('location');
			$description = $event_row->get('description');
			$date = $event_row->getDate();												//TODO worry about am/pm
			$time = $event_row->getTime();
			include_once SYSTEM_PATH.'/view/editevent.tpl';                           //TODO: check tpl is correct
		}
	}

	/* Publishes edited event
	 * Prereq (POST variables): Cancel, eventId, location, description, date, time
	 * Page variables: N/A
	 */
	public function editEvent_submit(){
        SiteController::loggedInCheck();

		//user canceled editing of event
		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$eventid = $_POST['eventId'];
		$event = Event::loadById($eventid);

		$location = $_POST['location'];
		$description = $_POST['description'];
		$date = $_POST['date'];																//TODO worry about am/pm
		$time = $_POST['time'];
		$timestamp = Event::convertToSQLDateTime($date, $time);

		$event = Event::loadById($eventid);
		$event->set('location', $location);
		$event->set('description', $body);
		$event->set('timestamp', $timestamp);
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
