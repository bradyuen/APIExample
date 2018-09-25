<?php
/*==============================
	Example to Build Rest API

	  Server Side
================================*/
include("apiFunction.php");
include("Model/PurchaseOrderService.php");
include("Model/TotalsCalculator.php");
//User API login, hard code for test only
$apiUser["BudgetPetProduct"] = array(
	"Password" => "Y61gsa623sa78u1234567890",
	"ApiKey" => "ui91hea73y21u27h87129891232791889sjwikheo",
);


//Set Default API call failure Message
$returnJSON = array(
	"result" => "false",
	"message" => "API authentication failed"
);



if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

	$_SERVER['PHP_AUTH_USER'] = filter_var($_SERVER['PHP_AUTH_USER'], FILTER_SANITIZE_STRING);
	$_SERVER['PHP_AUTH_PW'] = filter_var($_SERVER['PHP_AUTH_PW'], FILTER_SANITIZE_STRING);
	

	//Match Username
	if(isset($apiUser[$_SERVER['PHP_AUTH_USER']])){
		//Check API Key
		if($apiUser[$_SERVER['PHP_AUTH_USER']]["Password"] == $_SERVER['PHP_AUTH_PW']){

			if(isset($_POST["request"]) && trim($_POST["request"])!=""){
				//Filter to stop databse injection
				$returnJSON["debug"] = $_POST;
				$_POST["request"] = filter_var($_POST["request"], FILTER_SANITIZE_STRING);
				if($requestResult = apiDecrypt($_POST["request"], $apiUser[$_SERVER['PHP_AUTH_USER']]["Password"])){

					$requestArray = json_decode($requestResult, true);

					//Match Api Key
					if($requestArray["api_key"] == $apiUser["BudgetPetProduct"]["ApiKey"]){
						//Decrypt Request

						if(isset($requestArray["ids"]) && !empty($requestArray["ids"])){
							$totalObj = new BearClaw\Warehousing\TotalsCalculator();
							if($result = $totalObj->generateReport($requestArray["ids"])){

							}
						}

						$returnJSON = array(
							"result" => "true",
							"message" => "Match",
							"debug" => $requestArray
						);						
					}

				}

			}


		}
	}
}

echo json_encode($returnJSON);


exit;


$users = array('admin' => 'mypass', 'guest' => 'guest');

echo $_SERVER['PHP_AUTH_USER'];
echo $_SERVER['PHP_AUTH_PW'];
if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


if($authArray = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])){
	echo '<pre>';
	print_r($authArray);	
}

echo '<pre>';
print_r($_POST);

echo '<pre>';
print_r($_GET);

//Funtion to Get request
if(isset($_GET["request"]) && trim($_GET["request"]) != ""){

}

