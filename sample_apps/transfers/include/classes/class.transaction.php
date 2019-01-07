<?php
require_once('Flutterwave/api/Transfer.php');
use Flutterwave\Transfer;
//class for login section
require_once('class.general.php');//including the general functions
class transaction extends general
{
	
	function __construct($con)
	{
		parent::__construct($con);
	}

	//update wallet amount
	public function updateWalletAmount($userid, $amount)
	{
		//query
		$sql = "UPDATE userdetails SET walletamount = (walletamount + '$amount') WHERE id = '$userid'";
		$stmt = $this->db->query($sql);
		$count = $stmt->rowCount();

		if ($count > 0) {
			$status = 1;
		} else {
			$status = 0;
		}

		return $status;
	}

	//get wallet amount
	public function getWalletAmount($userid)
	{
		$array = array(
			"currency"=>"NGN",
			"seckey"=> $_ENV['SECRET_KEY']
		);

		$balance = new Transfer();
		$result = $balance->getBalance($array);
		echo $result;
		$result = json_decode($result, true);
		$_SESSION["AvailableBalance"] = $result["data"]["AvailableBalance"];
		$_SESSION["LedgerBalance"] = $result["data"]["LedgerBalance"];
        $amount =$_SESSION["AvailableBalance"];
		//query
		$sql = "UPDATE userdetails SET walletamount = (walletamount + '$amount') WHERE id = '$userid'";
		$stmt = $this->db->query($sql);
		$count = $stmt->rowCount();

		if ($count > 0) {
			$status = 1;
		} else {
			$status = 0;
		}

		 return $_SESSION["AvailableBalance"];
	}

	//create a group for transfer
	public function createGroup($groupname, $groupdesc, $userid)
	{
		// $sql = "INSERT INTO userdetails (fullname, username, password) VALUES ('$name', '$username', '$password')";
		$sql = "INSERT INTO groups (groupname, groupdesc, userid) VALUES ('$groupname', '$groupdesc', '$userid')";
		$stmt = $this->db->query($sql);
    	$count = $stmt->rowCount();
	}

	//get groups for a specific user
	public function getGroups($userid)
	{
		$sql = "SELECT * FROM groups WHERE userid = '$userid'";
		$stmt = $this->db->query($sql);

		return $stmt;
	}

	//add group members
	public function addMember($groupid, $bank, $acctno, $acctname, $salary)
	{
		// $sql = "INSERT INTO userdetails (fullname, username, password) VALUES ('$name', '$username', '$password')";
		$sql = "INSERT INTO groupmembers (groupid, staffname, staffbank, staffacctno, amount) VALUES ('$groupid', '$acctname', '$bank', '$acctno', '$salary')";
		$stmt = $this->db->query($sql);
    	$count = $stmt->rowCount();
	}

	//get group members
	public function getMembers($groupid)
	{
		$sql = "SELECT * FROM groupmembers WHERE groupid = '$groupid'";
		$stmt = $this->db->query($sql);

		return $stmt;
	}

	//get total payout of a group
	public function getTotalPayout($groupid)
	{
		$sql = "SELECT SUM(amount) as amount FROM groupmembers WHERE groupid = '$groupid'";
		$stmt = $this->db->query($sql);
		$count = $stmt->rowCount();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['amount'];
	}
}
?>