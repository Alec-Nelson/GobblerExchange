<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$sc = new SiteController();
$sc->route($action);

class SiteController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'home':
				$this->home();
				break;

			case 'signup':
				$this->signup();
				break;
			case 'register':
				$this->register();
				break;
			case 'login':
				$this->login();
				break;
			case 'postlogin':
				$this->postlogin();
				break;
			case 'logout':
				$this->logout();
				break;
				case 'whiteboard':
					$this->whiteboard();
					break;
			case 'jsontable':
				$this->jsontable();
				break;
		}
	}

	//redirects to forum
    public function home() {														//TODO uncomment logincheck once login page complete
		User::loggedInCheck();

		if ( isset($_SESSION['groupId']) && $_SESSION['groupId'] != ""  && $_SESSION['groupId'] != "0")
		{
			header('Location: '.BASE_URL.'/viewgroup/'.$_SESSION['groupId']);
		}
		else{
			include_once SYSTEM_PATH.'/view/testHome.html';
		}
	}

	public function signup(){
		echo 'this is being called';
		// include_once SYSTEM_PATH.'/view/signup.tpl';
	}

	public function login(){
		include_once SYSTEM_PATH.'/view/login.html';
	}

	public function postlogin(){
	 	$un = $_POST['userlogin'];
		$pw = $_POST['passlogin'];
		$user = User::loadByUsername($un);
		if($user == null) {
			// username not found
			$_SESSION['error'] = "<b>Uh oh!</b> Username <u>".$un."</u> not found!";
			header('Location: '.BASE_URL.'/login');
		} // incorrect password
		elseif($user->get('password') != $pw)
		{
			$_SESSION['error'] = "<b>Uh oh!</b> Incorrect password for username <u>".$un."</u>";
			header('Location: '.BASE_URL.'/login');
		}
		else
		{
			$groups = $user->getGroups();
			if ($groups != null)
			{
				$_SESSION['groupId'] = $groups[0]->get("id");
			}

            $_topic_query_st = "";
            if ($groups != null)
            {
                foreach ($groups as $group) {
                    $gname = urlencode($group->get('group_name'));
                    $_topic_query_st = ($_topic_query_st . "&topics=" . $gname);
                }
            }

            $chat_server = "http://" . /*"localhost:8050"; */ "104.236.205.162";
            $_SESSION['chat_server'] = $chat_server;
            $token = file_get_contents($chat_server . "/create_session?name=$un$_topic_query_st");
            $_SESSION['chat_token'] = $token;

			// else{
			// 	$_SESSION['groupId'] = 0;
			// }
			$_SESSION['userId'] = $user->get("id");
			header('Location: '.BASE_URL);
		}
	}



	public function register() {
		// get post data
		$username  = $_POST['username'];
		$passwd = $_POST['password'];
		$passwdConf = $_POST['password_confirmation'];
		$email  = $_POST['email'];
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$name = $firstName." ".$lastName;

		// are all the required fields filled?
		if ($name == '' || $username == '' || $passwd == '' || $email == '') {
			// missing form data; send us back
			$_SESSION['error'] = -'Please complete all registration fields.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}

		//does email have @vt.edu
		if (strpos($email, '@vt.edu') === false) {
			$_SESSION['error'] = 'Your email does not contain an vt.edu domain.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}

		//are fields too long?
		if(strlen($name) > 100){
			$_SESSION['error'] = 'Sorry, that name is too long.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}
		if(strlen($username) > 20){
			$_SESSION['error'] = 'Sorry, that username is too long.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}
		if(strlen($passwd) > 30){
			$_SESSION['error'] = 'Sorry, that password is too long.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}
		if(preg_match('/[^A-Za-z0-9]/', $username)){
			$_SESSION['error'] = 'Sorry, that username contains invalid characters';
			header('Location: '.BASE_URL.'/login');
			exit();
		}
		//do the passwords match?
		if(strcmp($passwd, $passwdConf) != 0)
		{
			$_SESSION['error'] = 'Sorry, your two passwords do not match';
			header('Location: '.BASE_URL.'/login');
			exit();
		}

		// is username in use?
		$user = User::loadByUsername($username);
		if(!is_null($user)) {
			// username already in use; send us back
			$_SESSION['error'] = 'Sorry, that username is already in use. Please pick a unique one.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}

		// is email in use?
		$user = User::loadByEmail($email);
		if(!is_null($user)) {
			// email is in use
			$_SESSION['error'] = 'Sorry, that email is already in use. Please pick a different one.';
			header('Location: '.BASE_URL.'/login');
			exit();
		}

		// okay, let's register
		$user = new User();
		$user->set('username', $username);
		$user->set('password', $passwd);
		$user->set('email', $email);
		$user->set('name', $name);
		$user->save();

		// log in this freshly created user and redirect to home page
		$_SESSION['username'] = $username;
		// $_SESSION['userId'] = $user->get('id');
		$_SESSION['success'] = "You successfully registered as ".$username.".";
		header('Location: '.BASE_URL.'/login');
		exit();
	}

	public function logout(){
		// erase the session
		unset($_SESSION['userId']);
		session_destroy();

		// redirect to home page
		// header('Location: '.BASE_URL);
		header('Location: '.BASE_URL.'/login');
		// include_once SYSTEM_PATH.'/view/login.html';
	}

	public function whiteboard() {
	User::loggedInCheck();

		include_once SYSTEM_PATH.'/view/whiteboard.html';
}

}
