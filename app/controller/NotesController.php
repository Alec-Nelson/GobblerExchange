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
				$notesId = $_GET['notesId'];
				$this->editnotes($notesId);
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
		}
	}

	/* Shows the notes for a group
	 * Prereq (POST variables): groupId
	 * Page variables: $notes
	 */
    public function notes() {
		User::loggedInCheck();

		$groupId = $_SESSION['groupId'];

		//do nothing if the user didn't select a group first
		if ($groupId == 0){
			header('Location: '.BASE_URL);
		}

		//Get polls associated with the current group
		$group = Group::loadById($groupId);
		$notes = $group->getNotesByRating();

		include_once SYSTEM_PATH.'/view/notes.html';                               //TODO: make sure this is the correct tpl
	}

	/* Opens edit notes form
	 * Prereq (POST variables):
	 * Page variables: title, link, tag
	 */
	public function editnotes($notesId){
        User::loggedInCheck();

        //retrieve the notes
		$notes = Notes::loadById($notesId);

		//check if author of the notes is the logged in user
		$authorId = $notes->get('userId');

		//allow user to edit notes
		$title = $notes->get('title');
		$noteslink = $notes->get('link');

		//get just the file name:
		$path = explode("\\", $noteslink);
		$filename = $path[count($path) - 1];

		$tag = $notes->get('tag');
		include_once SYSTEM_PATH.'/view/editNote.html';

	}

	/* Publishes updated notes
	 * Prereq (POST variables): Cancel, title, link, tag, notesId
	 * Page variables: N/A
	 */
	public function editnotes_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/notes');
			exit();
		}

		$notesId = $_POST['notesId'];
		$notes = Notes::loadById($notesId);

		if (isset($_POST['Delete'])) {
			$notes->delete();
			header('Location: '.BASE_URL.'/notes');
			exit();
		}

		$title = $_POST['title'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());

		//update fields (except for 'link')
		$notes->set('timestamp', $timestamp);
		$notes->set('title', $title);
		$notes->set('tag', $tag);
		$notes->save();

		//update 'link' if user specified a file
		if(isset($_FILES['attached'])){
		      $errors = array();
		      $file_name = $_FILES['attached']['name'];
		      $file_size = $_FILES['attached']['size'];
		      $file_tmp  = $_FILES['attached']['tmp_name'];
		      $file_type = $_FILES['attached']['type'];
			  $file_error= $_FILES['attached']['error'];
			  $file_ext=strtolower(end(explode('.',$_FILES['attached']['name'])));
			  $allowed_extensions = array("pdf");
			  //there was a problem uploading the file
			  if ($file_error !== UPLOAD_ERR_OK) {
					 $_SESSION['error'] = "<b>Uh oh!</b> There was an error uploading your file.";
 					 header('Location: '.BASE_URL.'/notes');
			  }
			  //make sure user uploaded the correct file extension
		      else if(in_array($file_ext, $allowed_extensions) === false){
				 $_SESSION['error'] = "<b>Uh oh!</b> <i>.".$file_ext."</i> files are not allowed, must be a .pdf.";
				 header('Location: '.BASE_URL.'/notes');
		      }
			  //attempt to store the file in the file system
		      else if (empty($errors) == true){
				 $temp_path = realpath(dirname(dirname(getcwd())));
				 //Path on local system
				 $path = $_SESSION['notes_directory'].$file_name;
				 //check if file name already exists
				 if(file_exists($path)){
					 $_SESSION['error'] = "File with that name already exists. Please rename your file and resubmit.";
					 header('Location: '.BASE_URL.'/notes');
					 exit();
				 }
		         move_uploaded_file($file_tmp,$path);
		         $_SESSION['success'] = "<b>Success!</b> Your file has been uploaded.";

				 //attempt to delete old file
				 unlink($notes->get('link'));

				  //Update link!
				  $notes->set('link', $file_name);
				  $notes->save();
		      }
              //There's an error with the file system.
			  else{
				 $_SESSION['error'] = "<b>FILE SYSTEM ERROR</b> Could not save the file.";
				 header('Location: '.BASE_URL.'/notes');
		      }
		  }
		header('Location: '.BASE_URL.'/notes');
	}

	/* Opens form for new notes
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newnotes(){
        User::loggedInCheck();

		include_once SYSTEM_PATH.'/view/createNotes.html';                             //TODO make sure the tpl is correct
	}

	/* Publishes new notes
	 * Prereq (POST variables): Cancel, title, link, tag, groupId
	 * Page variables: N/A
	 */
	public function newnotes_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/notes');
			exit();
		}

		$title = $_POST['title'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());
		$authorId = $_SESSION['userId'];
		$groupId = $_SESSION['groupId'];

		if(isset($_FILES['attached'])){
		      $errors = array();
		      $file_name = $_FILES['attached']['name'];
		      $file_size = $_FILES['attached']['size'];
		      $file_tmp  = $_FILES['attached']['tmp_name'];
		      $file_type = $_FILES['attached']['type'];
			  $file_error= $_FILES['attached']['error'];
			  $file_ext=strtolower(end(explode('.',$_FILES['attached']['name'])));
			  $allowed_extensions = array("pdf");
			  //there was a problem uploading the file
			  if ($file_error !== UPLOAD_ERR_OK) {
					 $_SESSION['error'] = "<b>Uh oh!</b> There was an error uploading your file";
 					 header('Location: '.BASE_URL.'/notes');
			  }
			  //make sure user uploaded the correct file extension
		      else if(in_array($file_ext, $allowed_extensions) === false){
				 $_SESSION['error'] = "<b>Uh oh!</b> <i>.".$file_ext."</i> files are not allowed, must be a .pdf.";
				 header('Location: '.BASE_URL.'/notes');
		      }
			  //attempt to store the file in the file system
		      else if (empty($errors) == true){
				 $temp_path = realpath(dirname(dirname(getcwd())));
				 //Path on local system (CHANGE IF HOST CHANGES)
				  $path = $_SESSION['notes_directory'].$file_name;
				 //check if file name already exists
				 if(file_exists($path)){
					 $_SESSION['error'] = "File with that name already exists. Please rename your file and resubmit.";
					 header('Location: '.BASE_URL.'/notes');
					 exit();
				 }
		         move_uploaded_file($file_tmp,$path);
		         $_SESSION['success'] = "<b>Success!</b> Your file has been uploaded.";

				  //ADD TO DATABASE
				  //add a rating
				  $rating = new Rating();
				  $rating->set('rating', 0);
				  $rating->save();

				  $notes = new Notes();
				  $notes->set('userId', $authorId);
				  $notes->set('timestamp', $timestamp);
				  $notes->set('title', $title);
				  $notes->set('link', $file_name);
				  $notes->set('tag', $tag);
				  $notes->set('ratingId', $rating->get('id'));
				  $notes->set('groupId', $groupId);
				  $notes->save();
				  header('Location: '.BASE_URL.'/notes');
		      }
              //There's an error with the file system.
			  else{
				 $_SESSION['error'] = "<b>FILE SYSTEM ERROR</b> Could not save the file.";
				 header('Location: '.BASE_URL.'/notes');
		      }
		   }
		  header('Location: '.BASE_URL.'/notes'); //no notes chosen to be uploaded
	}

}
