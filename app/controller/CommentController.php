<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a CommentController and route it
$sc = new CommentController();
$sc->route($action);

class CommentController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'viewcomments':
				$postId = $_GET['postId'];
				$this->viewComments($postId);
				break;

            case 'editcomment':
                $this->editComment();
                break;
            case 'editcomment_submit':
                $this->editComment_submit();
                break;
            case 'deletecomment':
                $this->deleteComment();
                break;

			case 'newnotescomment':
				$this->newNotesComment();
				break;
			case 'newnotescomment_submit':
				$this->newNotesComment_submit();
				break;

            case 'newpostcomment':
				$postId = $_GET['postId'];
                $this->newPostComment($postId);
                break;
            case 'newpostcomment_submit':
                $this->newPostComment_submit();
                break;
		}
	}

	/* Opens edit comment form
	 * Prereq (POST variables): N/A
	 * Page variables: post {title, date, authorUsername, description, tag}
	 */
	public function viewComments($postId){
		//get post info
		$post = ForumPost::loadById($postId);
		$title = $post->get('title');
		$timestamp = $post->get('timestamp');
		$authorId = $post->get('userId');
		$description = $post->get('description');
		$tag = $post->get('tag');

		//convert SQL timestamp to readable date format
		$date = Event::convertToReadableDate($timestamp);

		//get username of author
		$author = User::loadById($authorId);
		$authorUsername = $author->get('username');

		// get comments
		$comments = $post->getComments();
		include_once SYSTEM_PATH.'/view/forumcomment.html';
	}

    /* Opens edit comment form
	 * Prereq (POST variables): commentId
	 * Page variables: comment
	 */
	public function editComment(){
        SiteController::loggedInCheck();

        //retrieve the comment
		$commentId = $_POST['commentId'];
		$comment_entry = Comment::loadById($commentId);

		//check if author of the comment is the logged in user
		$authorId = $comment_entry->get('userId');
		$author = User::loadById($authorId);
		$authorUsername = $author->get('username');

		if($_SESSION['username'] != $authorUsername){
			$_SESSION['info'] = "You can only edit comments of which you are the author of.";
			header('Location: '.BASE_URL);											//TODO update
			exit();
		} else {
			//allow user to edit comment
			$comment = $comment_entry->get('comment');
			include_once SYSTEM_PATH.'/view/editcomment.tpl';                           //TODO: check tpl is correct
		}
	}

	/* Publishes updated comment
	 * Prereq (POST variables): Cancel, comment, commentid
	 * Page variables: N/A
	 */
	public function editComment_submit(){
        SiteController::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$commentId = $_POST['commentId'];
		$comment_entry = Comment::loadById($commentId);

		$comment = $_POST['comment'];
		$timestamp = date("Y-m-d", time());

		$comment_entry->set('comment', $comment);
		$comment_entry->set('timestamp', $timestamp);
		$notes->save();

		header('Location: '.BASE_URL);											//TODO update
	}

    /* Deletes comment
     * Prereq (POST variables): commentId
     * Page variables: SESSION[info]
     */
    public function deleteComment(){
        SiteController::loggedInCheck();

        $commentId = $_POST['commentId'];
        $comment = Comment::loadById($commentId);
        $commentAuthorId = $comment->get('userId');
        $commentAuthor = User::loadById($commentAuthorId);

        //user is the author of the notes, allow delete
        if($commentAuthor->get('username') == $_SESSION['username']){
            $comment->delete();
        } else {
            $_SESSION['info'] = "You can only delete comments you have created.";
        }

        //refresh page
        header('Location: '.BASE_URL);											//TODO update
    }

    // ---------- NOTES FUNCTIONS --------------------------------------------

	/* Opens form for new comment for notes
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newNotesComment(){
        SiteController::loggedInCheck();

		include_once SYSTEM_PATH.'/view/newnotescomment.tpl';                             //TODO make sure the tpl is correct
	}

	/* Publishes new comment for notes
	 * Prereq (POST variables): Cancel, comment, notesId
	 * Page variables: N/A
	 */
	public function newNotesComment_submit(){
        SiteController::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$timestamp = date("Y-m-d", time());
		$comment = $_POST['comment'];
		$notesId = $_POST['notesId'];

		//get author's id
		$author = $_SESSION['username'];
		$user = User::loadByUsername($author);
		$userid = $user->get('id');

		$comment_entry = new Comment();
		$comment_entry->set('timestamp', $timestamp);
		$comment_entry->set('comment', $comment);
		$comment_entry->set('notesId', $notesId);
		$comment_entry->set('userId', $userid);
		$comment_entry->save();

		header('Location: '.BASE_URL);                                            //update
	}

    // -------------- POST FUNCTIONS -------------------------------------------

    /* Opens form for new comment for a forum post
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newPostComment($postId){
        //SiteController::loggedInCheck();

		//get post info
		$post = ForumPost::loadById($postId);
		$title = $post->get('title');
		$timestamp = $post->get('timestamp');
		$authorId = $post->get('userId');
		$description = $post->get('description');
		$tag = $post->get('tag');

		//convert SQL timestamp to readable date format
		$date = Event::convertToReadableDate($timestamp);

		//get username of author
		$author = User::loadById($authorId);
		$authorUsername = $author->get('username');

		include_once SYSTEM_PATH.'/view/createComment.html';                             //TODO make sure the tpl is correct
	}

	/* Publishes new comment for a forum post
	 * Prereq (POST variables): Cancel, comment, postId
	 * Page variables: N/A
	 */
	public function newPostComment_submit(){
        //SiteController::loggedInCheck();

	//	include_once SYSTEM_PATH.'/view/forumcomment.html';

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$timestamp = date("Y-m-d", time());
		$text = $_POST['comment'];
		$postId = $_POST['postId'];

		//get author's id
		$authorId = $_SESSION['userId'];

		$comment = new Comment();
		$comment->set('timestamp', $timestamp);
		$comment->set('comment', $text);
		$comment->set('postId', $postId);
		$comment->set('userId', $authorId);
		$comment->save();

		header('Location: '.BASE_URL.'/viewcomments/'.$postId);
	}
}
