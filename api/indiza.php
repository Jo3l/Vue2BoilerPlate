<?php


class Indiza{

	private $linkTable = 'indiza_link'; 
	private $linkTagsTable = 'indiza_linktag'; 
	private $tagNameTable = 'indiza_tag'; 
	private $userTable = 'indiza_user';

	public function listPage($page, $linksPerPage, $userId = 'all', $tagId = 'all') {
		
		$db = new Database();
		$db->connect();
		
		//recogemos los link de la pagina para analizarlos
		$offset = $linksPerPage * $page;
		$db->sql("SELECT * FROM $this->linkTable".($tagId!='all' ? ", $this->linkTagsTable, $this->tagNameTable " : " ").($userId!='all' || $tagId!='all' ? "WHERE " : "").($userId!='all' ? "lnk_user = $userId " : "").($userId!='all' && $tagId!='all' ? "AND " : "").($tagId!='all' ? "$this->linkTable.lnk_id = $this->linkTagsTable.lnk_id AND $this->tagNameTable.tag_id = $this->linkTagsTable.tag_id AND $this->linkTagsTable.tag_id IN ($tagId) " : "")."ORDER BY lnk_datetime DESC LIMIT $offset, $linksPerPage");
		$result = $db->getResult();
		
		$r1 = count($result);
		for ($i=0;$i<$r1;$i++) { 
			$linkId[$i]=$result[$i]['LNK_ID'];
			$linkUser[$i] = $result[$i]['LNK_USER'];
		}
	
		//recogemos los tag de los link de la pagina
		$db->sql("SELECT * FROM $this->linkTagsTable WHERE 1=2".join( " OR LNK_ID=", $linkId ));
		$resultTags = $db->getResult();
		
		$r2 = count($resultTags);
		for ($i=0;$i<$r2;$i++) { 
			$tag[$resultTags[$i]['LNK_ID']][] = $resultTags[$i]['TAG_ID'];
			$tags[]=$resultTags[$i]['TAG_ID'];
		}
		array_unique($tags); //los unificamos
	
		//recogemos los nombres de tag que se usan en la pagina que hemos pedido
		$db->sql("SELECT * FROM $this->tagNameTable WHERE 1=2".join( " OR TAG_ID=", $tags ));
		$resultTagNames = $db->getResult();
		
		$r3 = count($resultTagNames);
		for ($i=0;$i<$r3;$i++) {
			$tagNames[$resultTagNames[$i]['TAG_ID']] = $resultTagNames[$i]['TAG_NAME'];
		}
		
		//recogemos los usuarios de las paginas que hemos pedido
		$db->sql("SELECT usr_id,usr_name,usr_avatar FROM $this->userTable WHERE 1=2".join(" OR usr_id=", $linkUser) );
		$resultUsers = $db->getResult();
	
		$r4 = count($resultUsers);
		for ($i=0;$i<$r4;$i++) { 
			
			$users[$resultUsers[$i]['usr_id']]['name'] = $resultUsers[$i]['usr_name'];
			$users[$resultUsers[$i]['usr_id']]['avatar'] = $resultUsers[$i]['usr_avatar'];
		}	
		
		$db->disconnect();
		
		//montando el array objeto para convertirlo a json
		$results = count($result);
		for ($i=0;$i<$results;$i++) {
			$final['links'][explode(" ", $result[$i]['LNK_DATETIME'])[0]][$result[$i]['LNK_ID']] = $result[$i];
			//final ->>>>>>>>>>>>>>>>>>>>key: dia >>>>>>>>>>>>>>>>>>>>0>>>>>>>>key: id link>>>>>>
		}
		$final['tag'] = $tag;
		$final['tagNames'] = $tagNames;
		$final['users'] = $users;
			
		return $final;
	}
	
}