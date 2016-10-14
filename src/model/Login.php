<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;
use App\utils\Session;

/**
 * Gerencia os dados do usuário para realização
 * de login no sistema
 * @author Jorge Lucas
 */
class Login
{
	/**
	 * @trait SqlQuery Recurso que gerencia as instruções sql
	 * @trait FilterInput Realiza a remoção de caracteres nocivos
	 * @var string $username Nome do usuário
	 * @var string $password Senha do usuário
	 * @var string $nickname Apelido do usuário
	 * @var string $permission Nível de permissão no sistema
	 */
	use SqlQuery;
	use FilterInput;
	protected $username;
	protected $password;
	protected $nickname;
	protected $permission;
	
	/**
	 * Verifica e armazena o nome de usuário
	 * @param string $username
	 * @return void
	 */
	public function setUsername(string $username) {
		$this->username = $this->cleanInput($username);
	}
	
	/**
	 * Retorna o nome do usuário armazenado
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * Criptografa e armazena a senha do usuário
	 * @param string $password
	 * @return void
	 */
	public function setPassword(string $password) {
		$this->password = $this->cleanInput($password);
		$this->password = md5($this->password);
	}
	
	/**
	 * Retorna a senha armazenada
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Armazena o apelido do usuário
	 * @param string $nickname
	 * @return void
	 */
	public function setNickname(string $nickname) {
		$this->nickname = $nickname;
	}
	
	/**
	 * Retorna o apelido armazenado
	 * @return string
	 */
	public function getNickname() {
		return $this->nickname;
	}
	
	/**
	 * Salva o nível de permissão do usuário
	 * @param string $permission
	 * @return void
	 */
	public function setPermission(string $permission) {
		$this->permission = $permission;
	}
	
	/**
	 * Retorna a permissão armazenada
	 * @return string
	 */
	public function getPermission() {
		return $this->permission;
	}
	
	/**
	 * Utiliza os dados de usuário e senha para tentar autenticar e logar
	 * @return boolean
	 */
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
 	
 	/**
 	 * Realiza a destruição da sessão no sistema
 	 * @return unknown
 	 */
 	public function logout() {
 		$sessao = new Session();
 		return $sessao->clean();
 	}
}