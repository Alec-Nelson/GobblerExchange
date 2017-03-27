<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a ForumController and route it
$sc = new ForumController();
$sc->route($action);

class ForumController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'forum':
				$this->forum();
				break;

			case 'editpost':
				$postId = $_GET['postId'];
				$this->editpost($postId);
				break;
			case 'editpost_submit':
				$this->editpost_submit();
				break;

			case 'newpost':
				$this->newpost();
				break;
			case 'newpost_submit':
				$this->newpost_submit();
				break;

			case 'deletepost':
				$this->deletepost();
				break;
		}
	}

	/* Shows the forum posts
	 * Prereq (POST variables): groupId
	 * Page variables: posts, pinned_posts, polls
	 */
    public function forum() {
		User::loggedInCheck();

		$groupId = $_SESSION['groupId'];


		//do nothing if the user didn't select a group first
		if ($groupId == 0){
			header('Location: '.BASE_URL);
		}

		//Get forumid associated with the current group
		$group_entry = Group::loadById($groupId);
		$group_name = $group_entry->get('group_name');
		$forumId = $group_entry->get('forumId');
        $forum = Forum::loadById($forumId);

		//retrieve all posts from the forum
		$posts = $forum->getPosts();
        $pinned_posts = $forum->getPinnedPosts();
		$polls = Poll::getAllOpenPolls($groupId);
		include_once SYSTEM_PATH.'/view/forum.html';                               //TODO: make sure this is the correct tpl
	}

	/* Opens edit post form
	 * Prereq (POST variables): edit (post id)
	 * Page variables: title, body, tag
	 */
	public function editpost($postId){
       User::loggedInCheck();

        //retrieve the post
		//$postid = $_POST['edit'];
		$post_row = ForumPost::loadById($postId);

        //retrieve post author's username
		$authorid = $post_row->get('userId');

		//check if author of the post is the logged in user
		// if($_SESSION['userId'] != $authorId){
		// 	$_SESSION['info'] = "You can only edit posts of which you are the author of.";
		// 	header('Location: '.BASE_URL);
		// 	exit();
		// } else {
			//hidden variables:
			$postId = $post_row->get('id');

			//allow access to edit post
			$title = $post_row->get('title');
			$body = $post_row->get('description');
			$tag = $post_row->get('tag');
			include_once SYSTEM_PATH.'/view/editForumPost.html';                           //TODO: check tpl is correct
		// }
	}

	/* Publishes an edited post
	 * Prereq (POST variables): Cancel, title, description, tag, postid
	 * Page variables: N/A
	 */
	public function editpost_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL.'/forum');
			exit();
		}

		$postid = $_POST['postId'];
		$post = ForumPost::loadById($postid);

		if (isset($_POST['Delete'])) {
			// if($post->get('userId') == $_SESSION['userId']){
				$post->delete();
			// } else {
			//	$_SESSION['info'] = "You can only delete posts you have created.";
			// }
			header('Location: '.BASE_URL.'/forum');
			exit();
		}

		$title = $_POST['title'];
		$body = $_POST['description'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());

		$post->set('title', $title);
		$post->set('description', $body);
		$post->set('tag', $tag);
		$post->set('timestamp', $timestamp);
		$post->save();

		header('Location: '.BASE_URL.'/forum');
	}

	/* Opens form for a new post
	 * Prereq (POST variables): N/A
	 * Page variables: N/A
	 */
	public function newpost(){
        User::loggedInCheck();

		include_once SYSTEM_PATH.'/view/createForumPost.html';                             //TODO make sure the tpl is correct
	}

	/* Publishes new post
	 * Prereq (POST variables): Cancel, title, description, tag, groupId
	 * Page variables: N/A
	 */
	public function newpost_submit(){
        User::loggedInCheck();

		if (isset($_POST['Cancel'])) {
			header('Location: '.BASE_URL);
			exit();
		}

		$title = $_POST['title'];
		$description = $_POST['description'];
		$tag = $_POST['tag'];
		$timestamp = date("Y-m-d", time());

		//get author's id
		$user_row = User::loadById($_SESSION['userId']);
		$userid = $user_row->get('id');

		//add a rating
		$rating = new Rating();
		$rating->set('userId', $userid);
		$rating->set('rating', 0);
		$rating->save();

		$post = new ForumPost();
		$post->set('userId', $userid);
		$post->set('timestamp', $timestamp);
		$post->set('title', $title);
		$post->set('description', $description);
		$post->set('tag', $tag);
		$post->set('ratingId', $rating->get('id'));

		//get forum id from group
		$groupId = $_SESSION['groupId'];
		$group = Group::loadById($groupId);
		$forumId = $group->get('forumId');
		$post->set('forumId', $forumId);

		$post->save();

        //add postId to rating
		$rating->set('postId', $post->get('id'));
		$rating->save();
		header('Location: '.BASE_URL.'/forum');
	}

	/* Deletes a post
	 * Prereq (POST variables): delete (post id)
	 * Page variables: SESSION[info]
	 */
	public function deletepost(){
    	User::loggedInCheck();

		$postid = $_POST['delete'];
		$post_row = ForumPost::loadById($postid);
		$post_author_id = $post_row->get('userId');
		$post_author = User::loadById($post_author_id);

		//user is the author of the post, allow delete
		if($post_author->get('username') == $_SESSION['username']){
			$post_row->delete();
		} else {
			$_SESSION['info'] = "You can only delete posts you have created.";
		}

		//refresh page
		header('Location: '.BASE_URL);											//TODO update
	}
}