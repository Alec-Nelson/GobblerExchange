<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a NotesController and route it
$sc = new NotesController();
$sc->route($action);

class NotesController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'notes':
				$this->notes();
				break;

			case 'editnotes':
				$this->editnotes();
				break;
			case 'editnotes_submit':
				$this->editnotes_submit();
				break;

			case 'newnotes':
				$this->newnotes();
				break;
			case 'newnotes_submit':
				$this->newnotes_submit();
				break;

			case 'deletenotes':
				$this->deletenotes();
				break;
		}
	}

	/* Shows the notes for a group
	 * Prereq (POST variables): groupId
	 * Page variables: $notes
	 */
    public function notes() {
		SiteController::loggedInCheck();

		//Get polls associated with the current group
		$groupId = $_POST['groupId'];
		$group = Group::loadById($groupId);
		$notes = $group->getNotes();

		include_once SYSTEM_PATH.'/view/notes.tpl';                               //TODO: make sure this is the correct tpl
	}

	/* Opens edit notes form
	 * Prereq (POST variables): notesId
	 * Page variables: title, link, tag
	 */
	public function editnotes(){
        SiteController::loggedInCheck();

        //retrieve the notes
		$notesId = $_POST['notesId'];
		$notes = Notes::loadById($notesId);

		//check if author of the notes is the logged in user
		$authorId = $notes->get('userId');
		$author = User::loadById($authorId);
		$authorUsername = $author->get('username');

		if($_SESSION['username'] != $authorUsername){
			$_SESSION['info'] = "You can only edit notes of which you are the author of.";
			header('Location: '.BASE_URL);											//TODO update
			exit();
		} else {
			//allow user to edit notes
			$title = $notes->get('title');
			$link = $notes->get('link');
			$tag = $notes->get('tag');
			include_once SYSTEM_PATH.'/view/edit.tpl';                           //TODO: check tpl is correct
		}
	}

	/* Publishes updated notes
	 * Prereq (POST variables): Cancel, title, link, tag, notesId
	 * Page variables: N/A
	 */
	public function editnotes_submit(){
        SiteController::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$notesId = $_POST['notesId'];
		$notes = Notes::loadById($notesId);

		$title = $_POST['title'];
		$link = $_POST['link'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());

		$notes->set('title', $title);
		$notes->set('link', $link);
		$notes->set('tag', $tag);
		$notes->set('timestamp', $timestamp);
		$notes->save();

		header('Location: '.BASE_URL);											//TODO update
	}

	/* Opens form for new notes
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newnotes(){
        SiteController::loggedInCheck();

		include_once SYSTEM_PATH.'/view/newnotes.tpl';                             //TODO make sure the tpl is correct
	}

	/* Publishes new notes
	 * Prereq (POST variables): Cancel, title, link, tag, groupId
	 * Page variables: N/A
	 */
	public function newnotes_submit(){
        SiteController::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$title = $_POST['title'];
		$link = $_POST['link'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());
		$author = $_SESSION['username'];
		$groupId = $_POST['groupId'];

		//get author's id
		$user_row = User::loadByUsername($author);
		$userid = $user_row->get('id');

		//add a rating
		$rating = new Rating();
		$rating->set('userId', $userid);
		$rating->set('rating', 0);
		$rating->save();

		$notes = new Notes();
		$notes->set('userId', $userid);
		$notes->set('timestamp', $timestamp);
		$notes->set('title', $title);
		$notes->set('link', $link);
		$notes->set('tag', $tag);
		$notes->set('ratingId', $rating->get('id'));
		$notes->set('groupId', $groupId);
		$notes->save();

        //add notesId to rating
		$rating->set('notesId', $notes->get('id'));
		$rating->save();
		header('Location: '.BASE_URL);
	}

	/* Deletes notes
	 * Prereq (POST variables): notesId
	 * Page variables: SESSION[info]
	 */
	public function deletenotes(){
    	SiteController::loggedInCheck();

		$notesId = $_POST['notesId'];
		$notes = Notes::loadById($notesId);
		$notesAuthorId = $notes->get('userId');
		$notesAuthor = User::loadById($notesAuthorId);

		//user is the author of the notes, allow delete
		if($notesAuthor->get('username') == $_SESSION['username']){
			$notes->delete();
		} else {
			$_SESSION['info'] = "You can only delete notes you have created.";
		}

		//refresh page
		header('Location: '.BASE_URL);											//TODO update
	}
}
