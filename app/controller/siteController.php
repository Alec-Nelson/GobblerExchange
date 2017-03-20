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
			case 'jsontable':
				$this->jsontable();
				break;
		}
	}

	//redirects to forum
    public function home() {														//TODO uncomment logincheck once login page complete
		// self::loggedInCheck();
		include_once SYSTEM_PATH.'/view/testHome.html';
	}

	public function signup(){
		include_once SYSTEM_PATH.'/view/signup.tpl';
	}

	public function login(){
		include_once SYSTEM_PATH.'/view/login.tpl';
	}

	public function postlogin(){
	 	$un = $_POST['username'];
		$pw = $_POST['password'];
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
			$_SESSION['username'] = $un;
			header('Location: '.BASE_URL);
		}
	}

	public function loggedInCheck(){
		//checks if user  is logged in
		// if not redirects to sign up page
		if( !isset($_SESSION['username']) || $_SESSION['username'] == '')
		{
			header('Location: '.BASE_URL.'/login');
		}
		else
		{
			$user = User::loadByUsername($_SESSION['username']);
			$userName = $user->get('username');
		}
	}
	public function register() {
		// get post data
		$username  = $_POST['username'];
		$passwd = $_POST['passwd'];
		$email  = $_POST['email'];
		$name = $_POST['name'];

		// are all the required fields filled?
		if ($name == '' || $username == '' || $passwd == '' || $email == '') {
			// missing form data; send us back
			$_SESSION['error'] = 'Please complete all registration fields.';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}

		//are fields too long?
		if(strlen($name) > 100){
			$_SESSION['error'] = 'Sorry, that name is too long.';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}
		if(strlen($username) > 20){
			$_SESSION['error'] = 'Sorry, that username is too long.';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}
		if(strlen($passwd) > 30){
			$_SESSION['error'] = 'Sorry, that password is too long.';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}
		if(preg_match('/[^A-Za-z0-9]/', $username)){
			$_SESSION['error'] = 'Sorry, that username contains invalid characters';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}

		// is username in use?
		$user = User::loadByUsername($username);
		if(!is_null($user)) {
			// username already in use; send us back
			$_SESSION['error'] = 'Sorry, that username is already in use. Please pick a unique one.';
			header('Location: '.BASE_URL.'/signup');
			exit();
		}

		// is email in use?
		$user = User::loadByEmail($email);
		if(!is_null($user)) {
			// email is in use
			$_SESSION['error'] = 'Sorry, that email is already in use. Please pick a different one.';
			header('Location: '.BASE_URL.'/signup');
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
		$_SESSION['success'] = "You successfully registered as ".$username.".";
		header('Location: '.BASE_URL);
		exit();
	}

	public function logout(){
		// erase the session
		unset($_SESSION['username']);
		session_destroy();

		// redirect to home page
		header('Location: '.BASE_URL);
	}

	public function jsontable(){
		// header('Content-Type: application/json');
		$cmd = "generate-classes-json.rb";
		echo exec("ruby ".$cmd);
		echo system($cmd);
		echo $cmd;
		// echo "test";
		// redirect to home page
		// header('Location: '.BASE_URL);
	}
}
