<?php

include_once '../global.php';

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
		$groupName = $_POST['groupName'];
		if(!Group::checkGroupNameAvailability($groupName)){
			//unavailable group name
			$_SESSION['error'] = 'Sorry, that group name is already taken.';	  //TODO make sure SESSION[error] is available in tpl
			header('Location: '.BASE_URL.'/newgroup');											//TODO update location?
			exit();
		}


		 // if class, number = crn; null otherwise
		 $number = NULL;
		if($_POST['checkBox'] == "1"){
			$number = $_POST['number'];
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

		header('Location: '.BASE_URL.'/');
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
			case "group":
				$results = Group::searchGroupName($search_term);
				include_once SYSTEM_PATH.'/view/searchgroup.html';
			break;
			case "username":
				$result = User::searchUsername($search_term);
				include_once SYSTEM_PATH.'/view/searchuser.html';
			break;
			case "email":
				$result = User::searchEmail($search_term);
				include_once SYSTEM_PATH.'/view/searchuser.html';
			default: //crn
				$type="crn";
				$results = Group::searchCRN($search_term);
				include_once SYSTEM_PATH.'/view/searchgroup.html';
		}
		include_once SYSTEM_PATH.'/view/test.html';
	}
}
