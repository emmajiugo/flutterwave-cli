<?php
//class for login section
require_once('class.general.php');//including the general functions
class auth extends general
{
	
	function __construct($con)
	{
		parent::__construct($con);
	}

	//function for login
	public function login($username, $password)
	{
		$sql = "SELECT * FROM userdetails WHERE username = '$username' AND password = '$password'";
		$stmt = $this->db->query($sql);
		$count = $stmt->rowCount();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		//set values
		$name = $row['fullname'];
		$user = $row['username'];
		$userid = $row['id'];

		if ($count > 0) {
			$msg = 1;

			//set session
			$_SESSION['name'] = $name;
			$_SESSION['user'] = $user;
			$_SESSION['userid'] = $userid;

			//return this
			$values = array('msg' => $msg, 'name' => $_SESSION['name'], 'user' => $_SESSION['user'], 'userid' => $_SESSION['userid']);

		} else {
			$msg = 2;

			//return this
			$values = array('msg' => $msg);
		}

		return $values;
	}
}
?>