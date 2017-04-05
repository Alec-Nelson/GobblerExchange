<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a PollController and route it
$sc = new PollController();
$sc->route($action);

class PollController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'polls':
				$this->polls();
				break;

			case 'editpoll':
				$pollId = $_GET['pollId'];
				$this->editpoll($pollId);
				break;
			case 'editpoll_submit':
				$this->editpoll_submit();
				break;

			case 'newpoll':
				$this->newpoll();
				break;
			case 'newpoll_submit':
				$this->newpoll_submit();
				break;

			case 'vote':
				$this->vote();
				break;

			case 'clear_response':
				$optId = $_GET['optId'];
				$this->removeResponse($optId);
				break;
		}
	}

	/* Shows the polls
	 * Prereq (POST variables): groupId
	 * Page variables: $polls
	 */
	public function polls() {
		User::loggedInCheck();

		$groupId = $_SESSION['groupId'];

		//do nothing if the user didn't select a group first
		if ($groupId == 0){
			header('Location: '.BASE_URL);
		}

		//Get polls associated with the current group
		$group = Group::loadById($groupId);
		$polls = $group->getAllPolls();

		include_once SYSTEM_PATH.'/view/polls.html';
	}

	/* Opens edit poll form
	 * Prereq (POST variables): edit (poll id)
	 * Page variables: title, options
	 */
	public function editpoll($pollId){
        User::loggedInCheck();

        //retrieve the poll
		$poll = Poll::loadById($pollId);

        //retrieve poll author's username
		$authorid = $poll->get('userId');

		//allow access to edit poll
		$title = $poll->get('title');
        $options = $poll->getPollOptions();
		$poll_status = $poll->get('isOpen');
		include_once SYSTEM_PATH.'/view/editPoll.html';
	}

	/* Publishes an edited poll
	 * Prereq (POST variables): Cancel, title, options, pollid
	 * Page variables: N/A
	 */
	public function editpoll_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/polls');
			exit();
		}

		//load poll
		$pollid = $_POST['pollId'];
		$poll = Poll::loadById($pollid);

		if (isset($_POST['Delete'])) {
			$poll->delete();
			header('Location: '.BASE_URL.'/polls');
			exit();
		}

		$title = $_POST['title'];
		$options = trim($_POST['options']);
		$optionsArray = split (",", $options);
		$optionsArray=array_map('trim',$optionsArray);
		$timestamp = date("Y-m-d", time());
		$poll_status = $_POST['poll_status'];

		if($poll_status == "open"){
			$poll->set('isOpen', 1);
		} else {
			$poll->set('isOpen', 0);
		}

		$poll->set('title', $title);
		$poll->set('timestamp', $timestamp);
		$poll->save();

        //remove old options
        $old_options = $poll->getPollOptions();
        foreach($old_options as $opt){
			$opt->delete();
        }

        //update options
        foreach ($optionsArray as $option){
            $poll_option = new PollOption();
            $poll_option->set('pollId', $pollid);
            $poll_option->set('poll_option', $option);
            $poll_option->save();
        }

		header('Location: '.BASE_URL.'/polls');
	}

	/* Opens form for a new poll forum poll
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newpoll(){
        User::loggedInCheck();

		include_once SYSTEM_PATH.'/view/createPoll.html';
	}

	/* Publishes new poll to the forum
	 * Prereq (POST variables): Cancel, groupId, title, options (array)
	 * Page variables: N/A
	 */
	public function newpoll_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		//get data
		$title = $_POST['title'];
		$options = trim($_POST['options']);
		$timestamp = date("Y-m-d", time());

		//create poll
		$poll = new Poll();
		$poll->set('title', $title);
		$poll->set('userId', $_SESSION['userId']);
		$poll->set('groupId', $_SESSION['groupId']);
		$poll->set('timestamp', $timestamp);
		$poll->set('isOpen', '1');
		$poll->save();

		//format options into array:
		$optionsArray = split (",", $options);

		foreach($optionsArray as $opt){
			$poll_option = new PollOption();
			$poll_option->set('pollId', $poll->get('id'));
			$poll_option->set('poll_option', trim($opt));
			$poll_option->save();
		}

		header('Location: '.BASE_URL.'/polls');
	}


	/* Saves a user's selection on a poll
	 * Prereq (POST variables): pollid
	 * Page variables: SESSION[info]
	 */
	public function vote(){
    	User::loggedInCheck();

		//load the id of the poll and option the user selected
		$pollId = $_POST['pollId'];
		$selectedOpt = $_POST['polloption'];
		$pollOption = PollOption::loadByPollOption($selectedOpt);
		$optId = $pollOption->get('id');

		//check if user previously voted. if so, update, otherwise, create new entry
		$pollopt = null;
	    if (($pollopt = UserPollOption::getOldSelection($pollId, $_SESSION['userId'])) == null){
			$pollopt = new UserPollOption();
			$pollopt->set('pollOptionId', $optId);
			$pollopt->set('userId', $_SESSION['userId']);
			$pollopt->save();
		} else {
			$pollopt->set('pollOptionId', $optId);
			$pollopt->save();
		}

		header('Location: '.BASE_URL.'/polls');
	}

	public function removeResponse($optId){
		$pollOpt = UserPollOption::loadByPollOptionAndUser($optId, $_SESSION['userId']);
		$pollOpt->delete();
		header('Location: '.BASE_URL.'/polls');
	}
}
