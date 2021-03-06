<?php

include_once '../global.php';
include_once 'SiteController.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a GroupController and route it
$gc = new GroupController();
$gc->route($action);

class GroupController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'viewgroup':
				$groupId = $_GET['groupId'];
				$this->viewGroup($groupId);
				break;
			case 'joingroup':
				$groupId = $_GET['groupId'];
				$this->joinGroup($groupId);
				break;
			case 'leavegroup':
				$groupId = $_GET['groupId'];
				$this->leaveGroup($groupId);
				break;

			case 'newGroup':
				$this->newGroup();
				break;
			case 'newGroup_submit':
				$this->newGroup_submit();
				break;

			case 'search':
				$this->search();
				break;
		}
	}

	public function viewGroup($groupId){
		$_SESSION['groupId'] = $groupId;
		include_once SYSTEM_PATH.'/view/viewgroup.html';
	}

	public function joinGroup($groupId){
		User::loggedInCheck();

		$usr_grp = new UserGroup();
		$usr_grp->set('groupId', $groupId);
		$usr_grp->set('userId', $_SESSION['userId']);
		$usr_grp->save();

		$group = Group::loadById($groupId);
		$group_name = $group->get('group_name');

		$_SESSION['info'] = "You have been added to the group: ".$group_name;

        SiteController::negotiateRealtimeToken();

		$_SESSION['groupId'] = $groupId;

		header('Location: '.BASE_URL);
	}

	public function leaveGroup($groupId){
		User::loggedInCheck();

		$usr_grp = UserGroup::loadByUserGroup($_SESSION['userId'], $groupId);
		$usr_grp->delete();

		$group = Group::loadById($groupId);
		$group_name = $group->get('group_name');
		$_SESSION['info'] = "You have been removed from the group: ".$group_name;

		//$_SESSION['groupId'] = 0;


		$user = User::loadById($_SESSION['userId']);
		$groups = $user->getGroups();
		if ($groups != null)
			$_SESSION['groupId'] = $groups[0]->get("id");
		else
			$_SESSION['groupId'] = 0;
		header('Location: '.BASE_URL);
	}

	/* Opens form for creating a new group
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
    public function newGroup() {
		User::loggedInCheck();

		include_once SYSTEM_PATH.'/view/createGroup.html';								//TODO: check tpl name
	}

	/* Creates/publishes a new group
	 * Prereq (POST variables): Cancel, group_name, type (CRN or non-CRN), number (crn)
	 * Page variables: SESSION[error]
	 */
	public function newGroup_submit() {
		// User::loggedInCheck();

		//user canceled new group
		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);											//TODO: update location?
			exit();
		}

		//Check if group name is available (doesn't already exist)


		// if class, number = crn; null otherwise
		if($_POST['checkBox'] == "1"){
			//Pull info from JSON:
			$number = $_POST['crn'];
			if ($number == "" )
			{
				$_SESSION['error'] = 'Please complete all fields.';
				self::newGroup();
				exit();
			}
	        $str = file_get_contents(BASE_URL."/public/json/timetable.json");
	        $json_a = json_decode($str);
	        $valid = false;
	        foreach($json_a->courses as $course) {
	            if($course->crn == $number) {
	            	$valid = true;
	            	$groupName = $course->code.": ".$course->name;
	            }
	        }
		}
		else{
			$groupName = $_POST['groupName'];
			if ($groupName == "" )
			{
				$_SESSION['error'] = 'Please complete all fields.';
				self::newGroup();
				exit();
			}
		}

		if (!$valid && $_POST['checkBox'] == "1") {
			//Invalid CRN
			$_SESSION['error'] = 'Sorry, the CRN '.$number.' is not valid.';	  //TODO make sure SESSION[error] is available in tpl
			header('Location: '.BASE_URL.'/newgroup');											//TODO update location?
			exit();
		}
		if(!Group::checkGroupNameAvailability($groupName)){
			//unavailable group name
			if($_POST['checkBox'] == "1"){
				$_SESSION['error'] = 'Sorry, a group already exists for CRN '.$number.'.';	  //TODO make sure SESSION[error] is available in tpl
			} else {
				$_SESSION['error'] = 'Sorry, that group name,'.$groupName.', is already taken.';	  //TODO make sure SESSION[error] is available in tpl
			}

			header('Location: '.BASE_URL.'/newgroup');											//TODO update location?
			exit();
		}

		//get author's id
		$userId = $_SESSION['userId'];

		//create modules for the group
		$calendar = new Calendar();
		$calendar->save();
		$forum = new Forum();
		$forum->save();
		$chat = new Chat();
		$chat->save();
		$whiteboard = new Whiteboard();
		$whiteboard->save();



		$group = new Group();
		$group->set('number', $number);
		$group->set('group_name', $groupName);
		$group->set('userId', $userId);
		$group->set('calendarId', $calendar->get('id'));
		$group->set('forumId', $forum->get('id'));
		$group->set('chatId', $chat->get('id'));
		$group->set('whiteboardId', $whiteboard->get('id'));
		$group->save();

		//add creator to the group
		$usrgrp = new UserGroup();
		$usrgrp->set('userId', $userId);
		$usrgrp->set('groupId', $group->get('id'));
		$usrgrp->save();

        SiteController::negotiateRealtimeToken();

		header('Location: '.BASE_URL.'/viewgroup/'.$group->get('id'));
		exit();
	}

	/* Creates/publishes a new group
	 * Prereq (POST variables): type, search
	 * Page variables: result
	 */
	public function search(){
		$type = $_POST['type']; //crn, group, username or email
		$search_term = $_POST['search']; //entered into search bar

		switch ($type) {
			case "crn":
				$results = Group::searchCRN($search_term);
				include_once SYSTEM_PATH.'/view/searchgroup.html';
			break;
			case "username":
				$results = User::searchUsername($search_term);
				include_once SYSTEM_PATH.'/view/searchuser.html';
			break;
			case "email":
				$results = User::searchEmail($search_term);
				include_once SYSTEM_PATH.'/view/searchuser.html';
			default: //group
				$type="group";
				$results = Group::searchGroupName($search_term);
				include_once SYSTEM_PATH.'/view/searchgroup.html';
		}
	}
}
