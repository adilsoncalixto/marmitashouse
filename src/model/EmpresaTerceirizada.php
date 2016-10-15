<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;

/**
 * Gerencia os dados pertencentes a sua responsabilidade e
 * manda-os para o banco de dados
 * @author Jorge Lucas
 */
class EmpresaTerceirizada
{
	/**
	 * @trait FilterInput Recurso para remoção de caracteres nocivos
	 * @trait SqlQuery Gerenciador de instruções sql
	 * @var int $codigo Código pertencente a Empresa
	 * @var string $nome Nome da empresa
	 * @var string $cnpj Número de regitro da empresa
	 * @var string $endereco Endereço da empresa
	 * @var string $bairro Bairro da empresa
	 * @var string $cidade Cidade da empresa
	 * @var int $telefone Nº para contato com a empresa
	 * @var string $email E-mail para contato
	 */
	use FilterInput;
	use SqlQuery;
	private $codigo;
	private $nome;
	private $cnpj;
	private $endereco;
	private $bairro;
	private $cidade;
	private $telefone;
	private $email;
	
	/**
	 * Armazena o código da empresa
	 * @param int $codigo
	 * @return void
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));
	}
	
	/**
	 * Retorna o valor armazenado em $código
	 * @return int
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Armazena o nome da empresa em $nome
	 * @param string $nome Nome da empresa
	 * @return void
	 */
	public function setNome(string $nome) {
		$this->nome = $this->cleanInput($nome);	
	}
	
	/**
	 * Retorna o valor armazenado em $nome
	 * @return string $nome
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	 * Formata e armazena o cnpj da empresa em $cnpj
	 * @param string $cnpj Nº de registro da empresa
	 * @return void
	 */
	public function setCnpj(string $cnpj) {
		if(preg_match("/\//", $cnpj)) {
			$this->cnpj = $cnpj;
		} else {
			$cnpj = intval($cnpj);
			$cnpj = $this->cleanInput($cnpj);
			$tmp = $cnpj;
			$cnpj  = substr($tmp, 0, 2) . '.';
			$cnpj .= substr($tmp, 2, 3) . '.';
			$cnpj .= substr($tmp, 5, 3) . '/';
			$cnpj .= substr($tmp, 8, 4) . '-';
			$cnpj .= substr($tmp, 12,2);
			$this->cnpj = $cnpj;		
		}
	}
	
	/**
	 * Retorna o valor armazenado em $cnpj
	 * @return string $cnpj
	 */
	public function getCnpj() {
		return $this->cnpj;
	}
	
	/**
	 * Armazena a endereço da empresa em $endereco
	 * @param string $endereco Endereço da empresa
	 * @return void
	 */
	public function setEndereco(string $endereco) {
		$this->endereco = $this->cleanInput($endereco);
	}
	
	/**
	 * Retorna o valor armazenado em $endereco 
	 * @return string $endereco
	 */
	public function getEndereco() {
		return $this->endereco;
	}
	
	/**
	 * Armazena o valor em $bairro
	 * @param strinf $bairro Bairro da empresa
	 * @return void
	 */
	public function setBairro(string $bairro) {
		$this->bairro = $this->cleanInput($bairro);
	}
	
	/**
	 * Retorna o valor armazenado em $bairro
	 * @return string $bairro
	 */
	public function getBairro() {
		return $this->bairro;
	}
	
	/**
	 * Armazena o valor em $cidade
	 * @param string $cidade Cidade da empresa
	 * @return void
	 */
	public function setCidade(string $cidade) {
		$this->cidade = $this->cleanInput($cidade);
	}
	
	/**
	 * Retorna o valor armazenado em $cidade
	 * @return string $cidade
	 */
	public function getCidade() {
		return $this->cidade;
	}
	
	/**
	 * Armazena o valor em $telefone
	 * @param string $telefone
	 * @return void
	 */
	public function setTelefone(string $telefone) {
		$this->telefone = intval($this->cleanInput($telefone));
	}

	/**
	 * Retorna o valor armazenado em $telefone
	 * @return integer $telefone
	 */
	public function getTelefone() {
		return $this->telefone;
	}
	
	/**
	 * Armazena o valor em $email
	 * @param string $email Email da empresa
	 */
	public function setEmail(string $email) {
		$this->email = $this->cleanInput($email);
	}
	
	/**
	 * Retorna o valor armazenado em $email
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Lê os dados nos campos da classe e envia-os ao banco de dados
	 * @return boolean
	 */
	public function save() {
		$dados = array(
				'nome' => $this->nome,
				'cnpj' => $this->cnpj,
				'endereco' => $this->endereco,
				'bairro' => $this->bairro,
				'cidade' => $this->cidade,
				'telefone' => $this->telefone,
				'email' => $this->email
		);
		$insert = SqlQuery::insert('empresa', $dados, '', '', 'cad_empresa');
		if($insert) {
			return true;
		}
		return false;
	}
	
	/**
	 * Recebe os dados para busca e efetua uma busca no banco de dados
	 * @param array $dados
	 * @return array $result
	 */
	public function search(array $dados) {
		
		$select = SqlQuery::select('empresa', $dados, '*', 'LIKE', 'OR', 'bus_empresa');
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Lê os dados nos campos da classe e atualiza os mesmo no banco
	 * de dados
	 * @return boolean
	 */
	public function update() {
		
		$dados = array(
				'codigo' => $this->codigo,
				'nome' => $this->nome,
				'cnpj' => $this->cnpj,
				'endereco' => $this->endereco,
				'bairro' => $this->bairro,
				'cidade' => $this->cidade,
				'telefone' => $this->telefone,
				'email' => $this->email
		);
		
		if(SqlQuery::update('empresa', $dados, 'upd_empresa')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Lê o valor $codigo e deleta a linha correspondente no banco de dados
	 * @return boolean
	 */
	public function delete() {
		
		$delete = SqlQuery::drop('empresa', array('codigo', $this->codigo), 'del_empresa');
		if($delete) {
			return true;
		}
		return false;
	}
}