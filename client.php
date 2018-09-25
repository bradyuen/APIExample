<?php


/*====================================
	Client Side API Function

====================================*/

include("apiFunction.php");
$username = "BudgetPetProduct";
$password = "Y61gsa623sa78u1234567890";
$APIKey =  "ui91hea73y21u27h87129891232791889sjwikheo";

$request["api_key"] = $APIKey;
$request["displayTest"] = "Hi, I'm Brad Wong.";
$request["ids"] = array("123", "124", "133");


$url = $_SERVER['HTTP_HOST']."/APIExample/index.php";
if($result = BPPAPICall($url, $request, $username, $password)){
	echo '<pre>';
	print_r($result);
}






?>