<?php

	session_start();

	/// mostrar errors
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	error_reporting(E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
	
	// md5=37e714943a89182fce6e1b038fc264d8
	
	$request= explode('/',trim($_SERVER['REQUEST_URI'],'/'));
	
	//echo '<pre>',print_r($request);
	
	$linksPerPage= 20;
	$nom= $request[2];
	$json= array();
	///print_r(json_decode($_POST['json'],true));
	if (isset($_POST['json'])) $json= json_decode($_POST['json'],true);
	$id=@$_REQUEST['id'];
	
	include('mysqli_crud.php');
	$db=new Database();
	$db->connect();

	echo '-',print_r($_SESSION),'-';
	if (!isset($_SESSION['id'])) if ($nom!='usuari') die('200 ACCESS ERROR');

	$wheres= array('1=1');
	$limit='';

	$pos= array_search('p', $request);
	if ( $pos && is_numeric($request[$pos+1]) )
	    $limit = ' limit '.$request[$pos+1].','.$linksPerPage;

	$pos= array_search('id', $request);
	if ( $pos && is_numeric($request[$pos+1]) )
	    array_push( $wheres, "id=".$request[$pos+1] );

	$pos= array_search('c', $request);
	if ( $pos )
	    array_push( $wheres, "instr('".$request[$pos+1]."',categoria)>0" );

	$pos= array_search('t', $request);
	if ( $pos )
	    array_push( $wheres, "instr('".$request[$pos+1]."',tags)>=0" );

	$pos= array_search('u', $request);
	if ( $pos ) {
		if ( is_numeric($request[$pos+1]) ) // usuari com a id
	    	array_push( $wheres, "autor=".$request[$pos+1] );
	    else // usuari com a nom (seguretat?)
	    	array_push( $wheres, "autor=(select id from fedpival.usuari where nom like '%".$request[$pos+1]."%'" );
	}

	$pos= array_search('d', $request);
	if ( $pos && is_numeric($request[$pos+1]) )
	    array_push( $wheres, "modificacio='".$request[$pos+1]."'" );
	    //opcio de posar rangos des de-fins a (i posar només una)
	
	switch($nom){
		case "usuari":
			if ($request[3]=='logout'){
				unset($_SESSION['id']);
				session_destroy();
				die('LOGGEDOUT ');
			}
			if (!$json['pwd']) die('200 ACCESS ERROR');
			@$db->sql("SELECT * FROM fedpival.usuario where pwd='".$json['pwd']."';");
			$result= @$db->getResult();
			if (count($result)>0){
				$_SESSION['id']= $result[0]['id'];
			}
			echo json_encode($result);
			exit ;
		break;
		case "noticia":
	    	array_push( $wheres, "instr('noticia',categoria)>0" );
	    	$nom= 'pagina';
		break;
		case "pagina":
	    	array_push( $wheres, "instr('pagina',categoria)>0" );
		break;
		case "article":
		break;
		case "partida":
		break;
		case "acte":
		break;
		default:
			die('200 UNKNOWN ERROR');
		}
		
		$sql= "SELECT * FROM fedpival.".$nom." where ".implode(' and ',$wheres).$limit;
		$db->sql( $sql );
		$result= $db->getResult();
		header('Content-Type: application/json, charset=utf-8');
		echo json_encode($result);

?>