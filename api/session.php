<?php


class Session{
	
	private $user_id = '';
	
	public static function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
	
	public function validateHash($login,$pass) {

		$db = new Database();
		$db->connect();
		$db->select('indiza_user','*',NULL,'USR_NAME="'.$login.'"','');
		$res = $db->getResult();
		$db->disconnect();
		
        if(!empty($res)) {
            if(crypt($pass,$res[0]['USR_PASSWORD'])===$res[0]['USR_PASSWORD']) {
                $this->user_id = $res[0]['USR_ID'];
                $_SESSION['user_id'] = $this->user_id;
                return true;
            }
        }
        return false;
        
    }
    

    
    private function hashPassword($password)
    {
        return crypt($password, $this->blowfishSalt());
    }
	
	    private function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }
        $rand = array();
        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }
        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . sprintf('%02d', $cost) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }
    
    public function addUsersHash($login,$pass) {
       $hash = $this->hashPassword($pass);
       $result = $this->pdo->prepare("INSERT INTO users (login,pass) VALUES (?,?)");
       $result->execute(array($login,$hash));
    }
    

	
}




