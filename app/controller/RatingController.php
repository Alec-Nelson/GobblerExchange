<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a RatingController and route it
$sc = new RatingController();
$sc->route($action);

class RatingController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {

            case 'upvotenotes':
                $this->upvoteNotes();
                break;
            case: 'downvotenotes':
                $this->downvoteNotes();
                break;

            case 'upvotepost':
                $this->upvoteForumPost();
                break;
            case: 'downvotepost':
                $this->downvoteForumPost();
                break;
		}
	}

    /* Upvotes notes
     * Prereq (POST variables): notesId
     * Page variables: N/A
     */
    public function upvoteNotes(){
        SiteController::loggedInCheck();

        //get notes
        $notesId = $_POST['notesId'];
        $notes = Notes::loadById($notesId);

        //get the user who upvoted the notes
        $user = User::loadByUsername($_SESSION['username']);
        $userId = $user->get('id');

        //upvote the notes
        $notes->upvote($userId);

        header('Location: '.BASE_URL);											//TODO update
        exit();
    }

    /* Downvote notes
     * Prereq (POST variables): notesId
     * Page variables: N/A
     */
    public function downvoteNotes(){
        SiteController::loggedInCheck();

        //get notes
        $notesId = $_POST['notesId'];
        $notes = Notes::loadById($notesId);

        //get the user who downvoted the Notes
        $user = User::loadByUsername($_SESSION['username']);
        $userId = $user->get('id');

        //downvote the notes
        $notes->downvote($userId);

        header('Location: '.BASE_URL);												//TODO update
        exit();
    }

    // --------- POST FUNCTIONS ----------------------------------------
    /* Upvotes a post
     * Prereq (POST variables): upvote (post id)
     * Page variables: N/A
     */
    public function upvoteForumPost(){
        SiteController::loggedInCheck();

        //get post
        $postId = $_POST['upvote'];
        $post = ForumPost::loadById($postId);

        //get the user who upvoted the post
        $user = User::loadByUsername($_SESSION['username']);
        $userId = $user->get('id');

        //upvote the post
        $post->upvote($userId);

        header('Location: '.BASE_URL);
        exit();
    }

    /* Downvote a post
     * Prereq (POST variables): downvote (post id)
     * Page variables: N/A
     */
    public function downvoteForumPost(){
        SiteController::loggedInCheck();

        //get post
        $postId = $_POST['downvote'];
        $post = ForumPost::loadById($postId);

        //get the user who downvoted the post
        $user = User::loadByUsername($_SESSION['username']);
        $userId = $user->get('id');

        //downvote the post
        $post->downvote($userId);

        header('Location: '.BASE_URL);
        exit();
    }
}
