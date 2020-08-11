<?php

// Load Composer's autoloader
require 'redis/vendor/predis/predis/autoload.php';
require 'phpmailer/vendor/autoload.php';

// Loading PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//loading Redis
Predis\Autoloader::register();

class MyTools{

	function getRedis()
	{
		$redis = new Redis();
		$redis->connect('localhost', 6379);
		return $redis;
	}

	function getDbConnection($credentialArr){
		$servername = $credentialArr["hostname"];
		$username = $credentialArr["user"];
		$password = $credentialArr["password"];
		$dbname = $credentialArr["databaseName"];

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}

	function executeQuery($query){
		$result = $this->db()->query($query);
		$data = array();
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				array_push($data,$row);
			}
		}
		return $data;
	}

	function insertQuery($query){
		$result = $this->db()->query($query);
		if($result == TRUE ){
			echo "New record created successfully\n";
		}else{
			echo "Error: " . $query . "<br>" . $result->error;
		}
		return;
	}


	public function curlcall($url,$post = false){
		$curlInit = curl_init($url);
		curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,40);
		curl_setopt($curlInit,CURLOPT_HEADER,false);
		curl_setopt($curlInit, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 40);      // timeout on connect
		curl_setopt($curlInit, CURLOPT_TIMEOUT, -1);     // timeout on response
		curl_setopt($curlInit, CURLOPT_SSLVERSION,1);

		if($post){
			curl_setopt($curlInit, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($curlInit, CURLOPT_URL,$url);
			curl_setopt($curlInit, CURLOPT_POST, 1);
			curl_setopt($curlInit, CURLOPT_POSTFIELDS,$post);
		}

		$res['response'] = curl_exec($curlInit);
		$res['httpcode'] = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
		$res['err']     = curl_errno( $curlInit );
		$res['errmsg']  = curl_error( $curlInit );


		curl_close($curlInit);
		return $res;
	}
}
?>
