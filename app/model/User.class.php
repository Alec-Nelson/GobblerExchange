<?php
class User extends DbObject {
    // name of database table
    const DB_TABLE = 'user';

    // database fields
    protected $id;
    protected $name;
    protected $username;
    protected $email;
    protected $password;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'name' => null,
            'username' => null,
            'email' => null,
            'password' => null
            );
        $args += $defaultArgs;
        $this->id = $args['id'];
        $this->name = $args['name'];
        $this->username = $args['username'];
        $this->email = $args['email'];
        $this->password = $args['password'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        $db_properties = array(
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password
        );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // delete user
    public function delete()
    {
         $db = Db::instance();
            $query = sprintf(" DELETE FROM %s  WHERE username = '%s' AND password = '%s' ",
            self::DB_TABLE,
            $this->username,
            $this->pw
            );
            $ex = mysql_query($query);
            if(!$ex)
            die ('Query failed:' . mysql_error());
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load user by username
    public function loadByUsername($username=null) {
        if($username === null)
            return null;
        $query = sprintf(" SELECT id FROM %s WHERE username = '%s' ",
            self::DB_TABLE,
            $username
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }

    //load by email
    public function loadByEmail($email=null) {
        if($email === null)
            return null;
        $query = sprintf(" SELECT id FROM %s WHERE email = '%s' ",
            self::DB_TABLE,
            $email
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }

    //get all groups the user is involved in
    public function getGroups(){
        return UserGroup::getAllGroupsOfUser($this->id);
    }

    //checks if the username has already been taken
    public function checkUsernameAvailability($username){
        if($username === null)
            return false;
        $query = sprintf(" SELECT id FROM %s WHERE username = '%s' ",
            self::DB_TABLE,
            $username
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return true;
        else {
            return false;
        }
    }

    //checks if an email is already assoicated with the account
    public function checkEmailAvailability($email){
        if($email === null)
            return false;
        $query = sprintf(" SELECT id FROM %s WHERE email = '%s' ",
            self::DB_TABLE,
            $email
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return true;
        else {
            return false;
        }
    }

    //Authenticates the user; returns true if the username and password are valid; false otherwise
    public function authenticateUser($username, $password){
        if($username === null || $password === null)
            return false;
        $query = sprintf(" SELECT id FROM %s WHERE username = '%s' AND password= '%s' ",
            self::DB_TABLE,
            $username,
            $password
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return false;
        else {
            return true;
        }
    }

    //SEARCH FUNCTIONS --------------------------------------------------------
    //search by username
    //returns all users if search term is empty/null
    public static function searchUsername($username) {
        if($username == null || $username == ""){
            return self::getAllUsers();
        }

        $query = sprintf(" SELECT id FROM %s WHERE username='%s'",
            self::DB_TABLE,
            $username
            );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

    //search by email
    //returns all users if search term is empty/null
    public static function searchEmail($email) {
        if($email == null || $email == ""){
            return self::getAllUsers();
        }

        $query = sprintf(" SELECT id FROM %s WHERE email='%s'",
            self::DB_TABLE,
            $email
            );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

    //get all users
    public static function getAllUsers() {
        $query = sprintf(" SELECT id FROM %s ",
            self::DB_TABLE
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

    public static function loggedInCheck(){
 	// 	// checks if user  is logged in
 	// 	// if not redirects to sign up page
 		if( !isset($_SESSION['userId']) || $_SESSION['userId'] == '')
 		{
 			header('Location: '.BASE_URL.'/login');
 		}
 		else
 		{
 		// 	$user = User::loadById($_SESSION['userId']);
 		// 	$userName = $user->get('username');
 		// 	header('Location: '.BASE_URL.'/'.$userName);

 		}
 	}
}
