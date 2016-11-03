<?php

namespace App\model;

use App\database\SqlQuery;
use App\utils\FilterInput;
use DateTime;
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
	private $codigo;
	private $data;
	private $quantia;
	private $username;
	
	/**
	 * Armazena um inteiro na variável código
	 * @param int $codigo
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));
	}
	
	/**
	 * Retorna o valor armazenado em código
	 * @return int código
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Atribui a data atual à variável $data
	 */
	public function setData(string $data) {
		date_default_timezone_set("America/Fortaleza");
		$this->data = new DateTime($data);
	}
	
	/**
	 * Retorna a data armazenada em $data
	 * @return string data
	 */
	public function getData() {
		return $this->data->format("Y-m-d H:i:s");
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
	 * Lê a quantia existente no caixa e adiciona com a
	 * quantia passada
	 * @return void
	 */
	public function addQuantia() {
		$dados = [
			'codigo' => $this->getCodigo(),
			'quantia' => $this->quantia
		];
		$insert = SqlQuery::update('caixa', $dados, 'upd_caixa');
		if($insert) {
			return true;
		}
		return false;
	}
	
	public function rmvQuantia() {
		
	}
	
	/**
	 * Prepara os dados para enviar ao banco, verifica se já existe um caixa aberto
	 * com a mesma data e, se sim, lança uma exceção, senão, conclui o registro
	 * @throws Exception
	 * @return boolean
	 */
	public function save() {
		$dados = array(
				'data' => $this->getData(),
				'quantia' => $this->quantia,
				'username' => $this->username
		);
		
		$verifyCaixa = SqlQuery::select('caixa', ['data' => $this->getData()], '*', '=', '', 'bus_caixa');
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
		$select = SqlQuery::select('caixa', $dados, ['codigo','data', 'quantia', 'username'], 'BETWEEN', 'AND', 'bus_caixa');
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Remove um caixa do banco de dados
	 */
	public function delCaixa(string $codigo) {
		$delete = SqlQuery::drop('caixa', ['codigo', $codigo], 'del_caixa');
		if($delete) {
			return true;
		}
		return false;
	}
 }