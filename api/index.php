<?php

$linksPerPage = 20;

include('mysqli_crud.php');
include('indiza.php');
include('session.php');

session_start();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
     
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	
	if (in_array('page', $request)) {
	    $page = is_numeric($request[array_search('page', $request)+1]) ? $request[array_search('page', $request)+1] : 0;
	} else $page = 0;
	
	if (in_array('user', $request)) {
	    $userId = is_numeric($request[array_search('user', $request)+1]) ? $request[array_search('user', $request)+1] : 'all';
	} else $userId = 'all';
	
	if (in_array('tag', $request)) {
	    $tagId = preg_match('/^[0-9]+(,[0-9]+)*$/', $request[array_search('tag', $request)+1]) ? $request[array_search('tag', $request)+1] : 'all';
	} else $tagId = 'all';
	
	$request = new Indiza();
	$final = $request->listPage($page, $linksPerPage, $userId, $tagId);     
     
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	$input = json_decode(file_get_contents('php://input'),true);

	if(isset($input['logout'])) {
		$final = ['logged'=>'true'];
	    Session::logout();
	} 

	else if(isset($input['user']) && isset($input['password'])) {
	
		$session = new Session();
	    if($session->validateHash($input['user'],$input['password'])) {

	        $final = ['logged'=>'true'];
	    }
	    else {
	        $final = ['logged'=>'false'];
	        Session::logout();
	    }
	}


	if(isset($_SESSION['user_id'])) {
	    
	    
	    
	}

	
}


header('Content-Type: application/json, charset=utf-8');
echo json_encode( $final );

 
?>