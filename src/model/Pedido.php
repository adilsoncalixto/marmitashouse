<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;
use App\model\Caixa;
use Exception;

/**
 * Realiza o tratamento dos dados que serão enviados/retornados
 * ao banco de dados
 * @author Jorge Lucas
 */
class Pedido
{
	/**
	 * @var int codigo Código do pedido
	 * @var date data Data de abertura do pedido
	 * @var Cliente cliente Instância da classe Cliente
	 * @var Produto produto Array com instâncias de Produtos
	 * @var int quantidade Quantidade de itens no pedido
	 * @var string tipoPagamento Tipo do pagamento que será efetuado
	 * @var double valorTotal Preço total do pedido
	 * @var double valorPago Valor (R$) pago pelo cliente
	 * @var double valorTrovo Valor (R$) que será devolvido ao cliente
	 * @var caixa caixa Dados do caixa que está sendo usado
	 * @var Entregador entregador Dados do entregadoe que efetuará a entrega
	 * @var int status Detalhes do andamento do pedido
	 * @var string vendedor Usuário que efetuou a venda
	 * @trait SqlQuery Classe simplificada para geração de intruções sql
	 * @trait FilterInput Classe simplificada para remoção de caracteres nocivos
	 */
	private $codigo;
	private $data;
	private $cliente;
	private $produto;
	private $quantidade;
	private $tipoPagamento;
	private $valorTotal;
	private $valorPago;
	private $valorTroco;
	private $caixa;
	private $entregador;
	private $status;
	private $vendedor;
	use SqlQuery;
	use FilterInput;
	
	/**
	 * Armazena o valor repassado na variável codigo
	 * @param int $codigo Código do pedido
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = $codigo;
	}
	
	/**
	 * Retorna o valor armazenado na variável codigo
	 * @return int codigo
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Gera a data baseada no dia da abertura e armazena o valor 
	 * repassado na variável data
	 * @param string $data
	 */
	public function setData() {
		date_default_timezone_set("America/Fortaleza");
		$this->data = date("d/m/Y");
	}
	
	/**
	 * Retorna o valor armazenado na variável data
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
	
	public function setCliente(int $codigo) {
		$this->cliente = intval($this->cleanInput($codigo));
	}
	
	public function getCliente() {
		return $this->cliente;
	}
	
	public function setProduto(string $produto) {
		$this->produto = $this->cleanInput($produto);
	}
	
	public function getProduto() {
		return $this->produto;
	}
	
	public function setValorTotal(float $valorTotal) {
		$this->valorTotal = floatval($valorTotal);
	}
	
	public function setTipoPagamento(string $tipoPagamento) {
		$this->tipoPagamento = $tipoPagamento;
	}
	
	public function setValorPago(float $valorPago) {
		$this->valorPago = floatval($valorPago);
	}
	
	public function setValorTroco(float $valorTroco) {
		$this->valorTroco = floatval($valorTroco);
	}
	
	public function setEntregador(int $entregador) {
		$this->entregador = intval($this->cleanInput($entregador));
	}
	
	public function setStatus(string $status) {
		$this->status = $this->cleanInput($status);
	}
	
	public function setVendedor(string $vendedor) {
		$this->vendedor = $vendedor;
	}
	
	/**
	 * Retorna uma lista com todos os pedidos registrados
	 */
	public function listPedidos(array $dados) {
		$select = SqlQuery::select('pedido', $dados, ['codigo','data', 'cliente', 'valorTotal', 'usuario', 'status', 'entregador'], 'BETWEEN', 'AND', 'bus_pedido');
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Coleta os dados armazenados nos campos da classe e tenta realizar
	 * a inserção no banco de dados
	 * @return boolean
	 */
	public function save() {
		
		/*
		 * Verifica a quantia em caixa para determinar se existe
		 * troco e, então, finalizar a execuçã da consulta
		 */
		$caixa = new Caixa();
		$caixa->setData();
		$caixa->setUsername();
		$quantia = SqlQuery::select('caixa', ['data'=>$caixa->getData()], ['quantia'], '=', '', 'bus_caixa');
		$caixa->setQuantia($quantia[0]->quantia);
		$caixa->setQuantia($caixa->getQuantia()+$this->valorPago);
		if($this->valorTroco > 0) {
			if($caixa->getQuantia() < $this->valorTroco) {
				throw new Exception('Não há valor suficiente no caixa para o troco!');
			}
			$caixa->setQuantia($caixa->getQuantia()-$this->valorTroco);
		}
		$caixa->attQuantia();
		
		$dados = [
			'data' => $this->data,
			'cliente' => $this->cliente,
			'produtos' => $this->produto,
			'tipoPagamento' => $this->tipoPagamento,
			'valorTotal' => $this->valorTotal,
			'valorPago' => $this->valorPago,
			'valorTroco' => $this->valorTroco,
			'entregador' => $this->entregador,
			'status' => $this->status,
			'usuario' => $this->vendedor
		];
		$insert = SqlQuery::insert('pedido', $dados, '', '', 'cad_pedido');
		if($insert) {
			return true;
		}
		return false;
	}
	
	public function search() {
		$search = SqlQuery::select('pedido', $dados, '*', '=', '', 'bus_pedido');
		if($search) {
			return (array)$search;
		}
		return false;
	}
	
	/**
	 * Remove um pedido do banco de dados
	 */
	public function delete(int $codigo) {
		$delete = SqlQuery::drop('pedido', ['codigo', $codigo], 'del_pedido');
		if($delete) {
			return true;
		}
		return false;
	}
}