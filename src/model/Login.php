<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;
use App\utils\Session;

class Login
{
	use SqlQuery;
	use FilterInput;
	protected $username;
	protected $password;
	protected $nickname;
	protected $permission;
	
	public function setUsername(string $username) {
		$this->username = $this->cleanInput($username);
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setPassword(string $password) {
		$this->password = $this->cleanInput($password);
		$this->password = md5($this->password);
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setNickname(string $nickname) {
		$this->nickname = $nickname;
	}
	
	public function getNickname() {
		return $this->nickname;
	}
	
	public function setPermission(string $permission) {
		$this->permission = $permission;
	}
	
	public function getPermission() {
		return $this->permission;
	}
	
	public function auth() {
		$dados = array(
			'username' => $this->getUsername(),
			'password' => $this->getPassword()
		);
		$auth = SqlQuery::select('usuario', $dados, '*', '=', 'AND', 'login');
		if(!empty($auth)) {
			foreach($auth as $obj) {
				$this->setNickname($obj->nickname);
				$this->setPermission($obj->permissoes);
			}
			return true;
		} else {
			return false;
		}
 	}
 	
 	public function logout() {
 		$sessao = new Session();
 		return $sessao->clean();
 	}
}