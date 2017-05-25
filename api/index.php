<?php

/// mostrar errors
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
// md5=37e714943a89182fce6e1b038fc264d8

     
$request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
echo '<pre>',print_r($request);



$linksPerPage = 20;
$nom= $request[2];
$json= array();

///print_r(json_decode($_POST['json'],true));

if (isset($_POST['json'])) $json= json_decode($_POST['json'],true);

$id= @$_REQUEST['id'];

include('mysqli_crud.php');
$db = new Database();
$db->connect();


switch($nom){
	case "usuari":
		//if ($request[3]=='logout') { die(unset($_SESSION['id'])); }
		if (!$json['pwd']) die('200 ACCESS ERROR');
		@$db->sql("SELECT count(*) as c FROM fedpival.usuario where pwd='".$json['pwd']."';");
		$result= @$db->getResult();
		if ($result['c']==1) {
			session_start();
			$_SESSION['id']= 1;
		}
		exit;
		break;
	case "pagina":
		session_start();
		echo ',',$_SESSION['id'],',';
		break;
	case "article": break;
	case "partida": break;
	case "acte": break;
	default: die('200 UNKNOWN ERROR');
}



$db->sql("SELECT * FROM fedpival.".$nom);
$result = $db->getResult();
		

header('Content-Type: application/json, charset=utf-8');
echo json_encode( $result );

 
?>