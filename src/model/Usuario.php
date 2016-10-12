<?php

namespace App\model;

use App\database\SqlQuery;
use App\utils\FilterInput;

use Exception;

/**
 * Classe responsável por tratar os dados recebidos pela classe
 * ControlUsuario e interagir com o banco de dados
 * @author Jorge Lucas
 */
class Usuario extends Login
{
	/**
	 * trait SqlQuery Gerencia CRUD no sgbd
	 * trait FilterInput Remove caracteres nocivos
	 * @var int $codigo Código do usuário
	 */
	use SqlQuery;
	use FilterInput;
	private $codigo;
	
	
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Coleta os dados, verifica se já existe um mesmo nickname e então,
	 * tenta enviá-los ao banco de dados, senão, laça uma exceção
	 */
	public function send() {
		
		if($this->search(array('username' => $this->username))) {
			throw new Exception("Usuário {$this->username} já cadastrado no sistema!");
		}
		
		$dados = array(
				'username' => $this->username,
				'password' => $this->password,
				'nickname' => $this->nickname,
				'permissoes' => $this->permission
		);
		$insert = SqlQuery::insert('usuario', $dados, '', '', 'cad_usuario');
		if($insert) {
			return true;
		}
		return false;
	}
	
	/**
	 * Recebe os dados que serão utilizados na busca e então,
	 * retorna o resultado
	 * @param array $dados Dados para buscar
	 */
	public function search(array $dados = null) {
		if($dados !== null) {
			$select = SqlQuery::select('usuario', $dados, '*', '=', '', 'bus_usuario');
		} else {
			$select = SqlQuery::select('usuario', array(), '*', '', '', 'bus_usuario');
		}
		
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Lê os dados armazendos nos campos da classe e tenta atualizar os dados, utilizando
	 * o trait SqlQuery, no banco de dados.
	 * @return boolean
	 */
	public function upd() {
		$dados = array(
				'codigo' => $this->getCodigo(),
				'username' => $this->getUsername(),
				'password' => $this->getPassword(),
				'nickname' => $this->getNickname(),
				'permissoes' => $this->getPermission()
		);
	
		if(SqlQuery::update('usuario', $dados, 'upd_usuario')) {
			return true;
		}
		return false;
	}

	/**
	 * Coleta o nickname do usuário e tenta deletá-lo do
	 * banco de dados
	 */
	public function delete() {
		$delete = SqlQuery::drop('usuario', $this->codigo, 'del_usuario');
		if($delete) {
			return true;
		}
		return false;
	}
}