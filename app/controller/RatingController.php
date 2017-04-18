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
			    $notesId = $_GET['notesId'];
                $this->upvoteNotes($notesId);
                break;
            case 'downvotenotes':
				$notesId = $_GET['notesId'];
                $this->downvoteNotes($notesId);
                break;

            case 'upvotepost':
				$postId = $_GET['postId'];
                $this->upvoteForumPost($postId);
                break;
            case 'downvotepost':
				$postId = $_GET['postId'];
                $this->downvoteForumPost($postId);
                break;
		}
	}

    /* Upvotes notes
     * Prereq (POST variables):
     * Page variables: N/A
     */
    public function upvoteNotes($notesId){
        User::loggedInCheck();

        //get notes
        $notes = Notes::loadById($notesId);

        //get the user who upvoted the notes
		$userId = $_SESSION['userId'];

        //upvote the notes
        $notes->upvote($userId);

        header('Location: '.BASE_URL.'/notes');											//TODO update
        exit();
    }

    /* Downvote notes
     * Prereq (POST variables):
     * Page variables: N/A
     */
    public function downvoteNotes($notesId){
        User::loggedInCheck();

        //get notes
        $notes = Notes::loadById($notesId);

        //get the user who downvoted the Notes
		$userId = $_SESSION['userId'];

        //downvote the notes
        $notes->downvote($userId);

        header('Location: '.BASE_URL.'/notes');												//TODO update
        exit();
    }

    // --------- POST FUNCTIONS ----------------------------------------
    /* Upvotes a post
     * Prereq (POST variables):
     * Page variables: N/A
     */
    public function upvoteForumPost($postId){
        User::loggedInCheck();

        //get post
        $post = ForumPost::loadById($postId);

        //get the user who upvoted the post
        $userId = $_SESSION['userId'];

        //upvote the post
        $post->upvote($userId);

        header('Location: '.BASE_URL.'/forum');
        exit();
    }

    /* Downvote a post
     * Prereq (POST variables):
     * Page variables: N/A
     */
    public function downvoteForumPost($postId){
        User::loggedInCheck();

        //get post
        $post = ForumPost::loadById($postId);

        //get the user who downvoted the post
		$userId = $_SESSION['userId'];

        //downvote the post
        $post->downvote($userId);

        header('Location: '.BASE_URL.'/forum');
        exit();
    }
}
