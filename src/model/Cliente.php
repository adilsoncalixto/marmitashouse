<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;

/**
 * Classe responsável por gerenciar os dados do cliente repassados
 * pela classe ControlCliente e rquisitar operações com o banco de dados
 * @author Jorge Lucas
 */
class Cliente
{
	/**
	 * @trait SqlQuery "Classe" que monstará as instruções sql
	 * @trait FilterIput "Classe" que realiza a remoção de caracteres nocivos
	 * @var $codigo Código vinculado ao cliente
	 * @var $nome Nome vinculado ao cliente
	 * @var $dtNascimento Data de nascimento vinculado ao cliente
	 * @var $telefone Telefone vinculado ao cliente
	 * @var $endereco Endereço vinculado ao cliente
	 * @var $bairro Bairro vinculado ao cliente
	 * @var $ptReferencia Ponto de referência vinculado ao cliente
	 */
	use SqlQuery;
	use FilterInput;
	private $codigo;
	private $nome;
	private $dtNascimento;
	private $telefone;
	private $endreco;
	private $bairro;
	private $ptReferencia;
	
	/**
	 * Recebe uma variável do tipo inteiro, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param int $codigo
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));	
	}
	
	/**
	 * Retorna o valor armazenado na variável $codigo
	 * @return int $codigo
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $nome
	 */
	public function setNome(string $nome) {
		$this->nome = $this->cleanInput($nome);
	}
	
	/**
	 * Retorna o valor armazenado na variável $nome
	 * @return string $nome
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $dtNascimento
	 */
	public function setDtNascimento(string $dtNascimento) {
		if(preg_match('/\//', $dtNascimento)) {
			$this->dtNascimento = $dtNascimento;
		} else {
			$this->dtNascimento = date("d/m/Y", strtotime($dtNascimento));
		}
	}
	
	/**
	 * Retorna o valor armazenado na variável $dtNascimento
	 * @return string $dtNascimento
	 */
	public function getDtNascimento() {
		return $this->dtNascimento;
	}
	
	/**
	 * Recebe uma variável do tipo int, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $telefone
	 */
	public function setTelefone(int $telefone) {
		$this->telefone = $this->cleanInput($telefone);
	}
	
	/**
	 * Retorna o valor armazenado na variável $telefone
	 * @return string $telefone
	 */
	public function getTelefone() {
		return $this->telefone;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $endereco
	 */
	public function setEndereco(string $endereco) {
		$this->endreco = $this->cleanInput($endereco);
	}
	
	/**
	 * Retorna o valor armazenado na variável $endereco
	 * @return string $endereco
	 */
	public function getEndereco() {
		return $this->endreco;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $bairro
	 */
	public function setBairro(string $bairro) {
		$this->bairro = htmlspecialchars($bairro);
	}
	
	/**
	 * Retorna o valor armazenado na variável $bairro
	 * @return string $bairro
	 */
	public function getBairro() {
		return $this->bairro;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $ptReferencia
	 */
	public function setPtReferencia(string $ptReferencia) {
		$this->ptReferencia = htmlspecialchars($ptReferencia);
	}
	
	/**
	 * Retorna o valor armazenado na variável $ptReferencia
	 * @return string $ptReferencia
	 */
	public function getPtReferencia() {
		return $this->ptReferencia;
	}
	
	/**
	 * Coleta os dados armazenados nos campos da classe e tenta realizar
	 * a inserção no banco de dados
	 * @return boolean
	 */
	public function send() {
		$dados = array(
			'nome' => $this->nome,
			'dtNascimento' => $this->dtNascimento,
			'telefone' => $this->telefone,
			'endereco' => $this->endreco,
			'bairro' => $this->bairro,
			'ptReferencia' => $this->ptReferencia
		);
		$insert = SqlQuery::insert('cliente', $dados, '', '', 'cad_cliente');
		if($insert) {
			return true;
		} 
		return false;
	}
	
	/**
	 * Recebe um array com os dados a serem buscados e, de acordo com os mesmos,
	 * deterima quais serão utilizados na busca no banco de dados usando o trait SqlQuery
	 * @param array $dados Dados a serem usados na busca
	 * @return array|boolean Caso complete a busca, retorna os dados, senão,
	 * retorn falso
	 */
	public function search(array $dados) {

		$select = SqlQuery::select('cliente', $dados, '*', 'LIKE', 'OR', 'bus_cliente');
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
	public function update() {
		$dados = array(
				'codigo' => $this->codigo,
				'nome' => $this->nome,
				'dtNascimento' => $this->dtNascimento,
				'telefone' => $this->telefone,
				'endereco' => $this->endreco,
				'bairro' => $this->bairro,
				'ptReferencia' => $this->ptReferencia
		);
		
		if(SqlQuery::update('cliente', $dados, 'upd_cliente')) {
			return true;
		}
		return false;		
	}
	
	/**
	 * Lê o campo $codigo e solicita ao trait SqlQuery para deletar a linha
	 * no banco de dados
	 * @return boolean
	 */
	public function delete() {
		$delete = SqlQuery::drop('cliente', array('codigo', $this->codigo), 'del_cliente');
		if($delete) {
			return true;
		}
		return false;
	}
}