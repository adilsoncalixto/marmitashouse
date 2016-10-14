<?php

namespace App\model;

use App\database\SqlQuery;
use App\utils\FilterInput;
use Exception;

/**
 * Classe responsável por gerenciar a movimentação do caixa (R$)
 * @author Jorge Lucas
 */
class Caixa
{
	/**
	 * @trait SqlQuery "Classe" que monstará as instruções sql
	 * @trait FilterIput "Classe" que realiza a remoção de caracteres nocivos
	 * @var string $data Data de abertura do caixa
	 * @var double $quantia Quantia a ser manipulada pelas requisições
	 * @var double $username usuário responsável por abrir o caixa
	 */
	use SqlQuery;
	use FilterInput;
	private $data;
	private $quantia;
	private $username;
	
	/**
	 * Atribui a data atual à variável $data
	 */
	public function setData() {
		$this->data = date("d/m/Y");
	}
	
	/**
	 * Retorna a data armazenada em $data
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Atribui um valor (quantia) à variável $quantia
	 * @param string $quantia
	 */
	public function setQuantia(string $quantia) {
		$this->quantia = doubleval($this->cleanInput($quantia));
	}
	
	/**
	 * Retorna o valor armazenado em $quantia
	 * @return double $quantia
	 */
	public function getQuantia() {
		return $this->quantia;
	}
	
	/**
	 * Configura a variável $username com o valor registrado na sessão
	 */
	public function setUsername() {
		$this->username = $_SESSION['nickname'];
	}
	
	/**
	 * Retorna o valir de $username
	 * @return unknown
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * Lê o valor do caixa em questão, soma com o valor passado por parâmetro
	 * e armazena no banco de dados
	 * @param double $quantia
	 */
	public function addQuantia(double $quantia) {
		//TODO: implementar
	}
	
	/**
	 * Lê o valor do caixa em questão, verifica se é possivel efetar uma retirada e,
	 * então, subtrai pelo valor passado por parâmetro e armazena no banco de dados.
	 * @param double $quantia
	 */
	public function rmvQuantia(double $quantia) {
		//TODO: implementar
	}
	
	/**
	 * Prepara os dados para enviar ao banco, verifica se já existe um caixa aberto
	 * com a mesma data e, se sim, lança uma exceção, senão, conclui o registro
	 * @throws Exception
	 * @return boolean
	 */
	public function save() {
		$dados = array(
				'data' => $this->data,
				'quantia' => $this->quantia,
				'username' => $this->username
		);
		
		$verifyCaixa = SqlQuery::select('caixa', array('data' => $this->data), '*', '=', '', 'bus_caixa');
		if($verifyCaixa) {
			throw new Exception('Caixa já aberto!');
		}
		
		$insert = SqlQuery::insert('caixa', $dados, '', '', 'cad_caixa');
		if($insert) {
			return true;
		}
		return false;
	}
	
	/**
	 * Retorna uma lista com todos os caixas registrados
	 */
	public function listCaixa(array $dados) {
		$select = SqlQuery::select('caixa', $dados, '*', 'BETWEEN', 'AND', 'bus_caixa');
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Remove um caixa do banco de dados
	 */
	public function delCaixa(string $data) {
		$delete = SqlQuery::drop('caixa', array('data', $data), 'del_caixa');
		if($delete) {
			return true;
		}
		return false;
	}
 }