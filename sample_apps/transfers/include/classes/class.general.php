<?php
//class for general functions
class general {

	protected $db;
	
	//db function
	function __construct($con)
	{
		$this->db = $con;
	}

    //function checking if the user is logged in
    public function is_loggedin() {

        if(isset($_SESSION['user'])) {
            return true;
        }
    }

	//function for Africa/Lagos date
   	public function africaDate() {

   		date_default_timezone_set('Africa/Lagos');
   		$dbDate = date('d/m/Y H:i:s');
   		return $dbDate;
   	}

   	//function to delete row
	public function delete($id, $tblname)
	{
		//selecting from db
    	$sql = "DELETE FROM `$tblname` WHERE id = '$id'";
    	$stmt = $this->db->query($sql);
    	$count = $stmt->rowCount();

    	if ($count > 0) {
    		//set msg
    		$msg = true;
    	}

    	return $msg;
	}
}

?>
