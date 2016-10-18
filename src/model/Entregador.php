<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;

/**
 * Realiza o tratamento dos dados que serão enviados/retornados
 * ao banco de dados
 * @author Jorge Lucas
 */
class Entregador
{
	/**
	 * @trait FilterInput Trata de caracteres nocivos
	 * @trait SqlQuery Monta as instruções sql para interagir com o bd
	 * @var int $codigo Código relacionado ao Entregador
	 * @var string $nome Nome do entregador
	 * @var int $cpf CPF do entregador
	 * @var int $rg RG do entregador
	 * @var int $telefone Telefone do entregador
	 * @var Empresa $empresa Instância da classe EmpresaTerceirizada
	 */
	use FilterInput;
	use SqlQuery;
	private $codigo;
	private $nome;
	private $cpf;
	private $rg;
	private $telefone;
	private $empresa;
	
	/**
	 * Armazena o valor em $codigo
	 * @param int $codigo Código relacionado ao Entregador
	 * @return void
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));
	}
	
	/**
	 * Retorna o valor contido em $codigo
	 * @return int
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Armazena o valor em $nome
	 * @param string $nome Nome do entregador
	 * @return void
	 */
	public function setNome(string $nome) {
		$this->nome = $this->cleanInput($nome);
	}
	
	/**
	 * Retorna o valor contido em $nome
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	 * Armazena o valor em $cpf
	 * @param string $cpf
	 * @return void
	 */
	public function setCpf(string $cpf) {
		$this->cpf = $this->cleanInput($cpf);
	}
	
	/**
	 * Retorna o valor contido em $cpf
	 * @return string
	 */
	public function getCpf() {
		return $this->cpf;
	}
	
	/**
	 * Armazena o valor em $crg
	 * @param string $rg
	 * @return void
	 */
	public function setRg(string $rg) {
		$this->rg = $this->cleanInput($rg);
	}
	
	/**
	 * Retorna o valor contido em $rg
	 * @return string
	 */
	public function getRg() {
		return $this->rg;
	}
	
	/**
	 * Armazena o valor em $telefone
	 * @param string $telefone
	 * @return void
	 */
	public function setTelefone(string $telefone) {
		$this->telefone = $this->cleanInput($telefone);
	}
	
	/**
	 * Retorna o valor contido em $telefone
	 * @return string
	 */
	public function getTelefone() {
		return $this->telefone;
	}
	
	/**
	 * Armazena o valor em $empresa
	 * @param string $empresa
	 * @return void
	 */
	public function setEmpresa(string $empresa) {
		$search = SqlQuery::select('empresa', ['nome' => $empresa], '*', '=', '', 'bus_empresa');
		$this->empresa = intval($search[0]->codigo);
	}
	
	/**
	 * Retorna o valor contido em $empresa
	 * @return string
	 */
	public function getEmpresa() {
		return $this->empresa;
	}
	
	/**
	 * Coleta os dados armazenados nos campos da classe e tenta realizar
	 * a inserção no banco de dados
	 * @return boolean
	 */
	public function save() {
		$dados = array(
				'nome' => $this->nome,
				'cpf' => $this->cpf,
				'rg' => $this->rg,
				'telefone' => $this->telefone,
				'empresa' => $this->empresa
		);
		$insert = SqlQuery::insert('entregador', $dados, '', '', 'cad_entregador');
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
	public function search(array $dados, string $operator = 'LIKE') {
	
		$select = SqlQuery::select('entregador', $dados, '*', $operator, 'OR', 'bus_entregador');
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
				'cpf' => $this->cpf,
				'rg' => $this->rg,
				'telefone' => $this->telefone,
				'empresa' => $this->empresa
		);
	
		if(SqlQuery::update('entregador', $dados, 'upd_entregador')) {
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
		$delete = SqlQuery::drop('entregador', array('codigo', $this->codigo), 'del_entregador');
		if($delete) {
			return true;
		}
		return false;
	}
}