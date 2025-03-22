<?php
	include_once "db_connect.php";
	include_once "twilio-php/Services/Twilio.php";
	$pilots = json_decode($_POST["pilots"], true);

	foreach($pilots as $pilot){
		$id = $pilot["id"];
		$account = $_SESSION["account"];
		$query = "SELECT fname, lname, phone FROM pilot_info WHERE id=$id";
		$result = $mysqli->query($query);
		if($result != false){
			$row = $result->fetch_assoc();
			if($row["phone"] != null && $row["phone"] != ""){
				$number = str_replace('+', "", str_replace("-", "", str_replace(" ", "", str_replace("(", "", str_replace(")", "", $row["phone"])))));

				$name = $row["fname"]." ".$row["lname"];
				$date = $pilot["date"];
				$craft = $pilot["craft"];
				$position = $pilot["position"];
				$details = ($pilot["details"] != "" ? " Details: ".$pilot["details"] : '');
				$message = "Hello $name, you are scheduled to fly $craft on $date as $position.$details";

				$data = array("From"=>"+14387937518", "To"=>"+".$number, "Body"=>$message, "StatusCallback"=>"https://www.helicopters-offshore.com/REST/messaging/status");
				$AccountSid = "AC92d096297651c750e1f813e9feb8a74c";
				$AuthToken = "9280c4c55f592e6cba55faca54d6a8f2";
				 
				$client = new Services_Twilio($AccountSid, $AuthToken);
				
				try{
					$message = $client->account->messages->create($data);
					$messageSid = $message->sid;
					// $sentDate = date("Y-m-d", strtotime($message->date_created));
					// $sentDate = $message->date_created;
					$sentDate = date("Y-m-d\TG:i:sP", strtotime($message->date_created));
					$dbDate = $pilot["dbDate"];
					$insert = "INSERT INTO sms_messages VALUES ('$messageSid', 'sent', '$name', '$dbDate', '$craft', '$sentDate', $account)";
					if($mysqli->query($insert)){

					}else{
						print($mysqli->error);
					}
				}catch(Services_Twilio_RestException $e){
					print($e->getMessage());
					$sentDate = $pilot["dbDate"]."T".date("G:i:sP", time());
					$randID = generateString();
					$insert = "INSERT INTO sms_messages VALUES ('$randID', 'failed', '$name', '$sentDate', '$craft', '$sentDate', $account)";
					$mysqli->query($insert);
				}						
			}
		}
	}

function generateString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 32; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>