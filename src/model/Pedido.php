<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;
use App\model\Caixa;
use DateTime;
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
	 * @var string tipoPagamento Tipo do pagamento que será efetuado
	 * @var float valorTotal Preço total do pedido
	 * @var float valorPago Valor (R$) pago pelo cliente
	 * @var float valorTroco Valor (R$) que será devolvido ao cliente
	 * @var float desconto Desconto sob a compra
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
	private $tipoPagamento;
	private $valorTotal;
	private $valorPago;
	private $valorTroco;
	private $desconto;
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
	public function setData(string $data) {
		date_default_timezone_set("America/Fortaleza");
		$this->data = new DateTime($data);
	}
	
	/**
	 * Retorna o valor armazenado na variável data
	 * @return string
	 */
	public function getData() {
		return $this->data->format("Y-m-d H:i:s");
	}
	
	/**
	 * Armazena o código do cliente na variável $codigo
	 * @param int $codigo
	 * @return void
	 */
	public function setCliente(int $codigo) {
		$this->cliente = intval($this->cleanInput($codigo));
	}
	
	/**
	 * Armazena uma lista dos produtos contidos no pedido
	 * @param string $produto
	 * @return void
	 */
	public function setProduto(string $produto) {
		$this->produto = $this->cleanInput($produto);
	}
	
	/**
	 * Armazena o valor repassado no campo $valorTotal
	 * @param float $valorTotal
	 * @return void
	 */
	public function setValorTotal(float $valorTotal) {
		$this->valorTotal = floatval($valorTotal);
	}
	
	/**
	 * Armazena o valor repassado no campo $tipoPagamento
	 * @param string $tipoPagamento
	 * @return void
	 */
	public function setTipoPagamento(string $tipoPagamento) {
		$this->tipoPagamento = $tipoPagamento;
	}
	
	/**
	 * Armazena o valor repassado no campo $valorPago
	 * @param float $valorPago
	 * @return void
	 */
	public function setValorPago(float $valorPago) {
		$this->valorPago = floatval($valorPago);
	}
	
	/**
	 * Armazena o valor repassado no campo $valorTroco
	 * @param float $valorTroco
	 * @return void
	 */
	public function setValorTroco(float $valorTroco) {
		$this->valorTroco = floatval($valorTroco);
	}
	
	/**
	 * Armazena o valor repassado no campo $desconto
	 * @param float $desconto
	 * @return void
	 */
	public function setDesconto(float $desconto) {
		$this->desconto = floatval($this->cleanInput($desconto));
	}
	
	/**
	 * Armazena o valor repassado no campo $entregador
	 * @param float $entregador
	 * @return void
	 */
	public function setEntregador(int $entregador) {
		$this->entregador = intval($this->cleanInput($entregador));
	}
	
	/**
	 * Armazena o valor repassado no campo $status
	 * @param float $status
	 * @return void
	 */
	public function setStatus(string $status) {
		$this->status = $this->cleanInput($status);
	}
	
	/**
	 * Armazena o valor repassado no campo $vendedor
	 * @param float $vendedor
	 * @return void
	 */
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
	 * Realiza uma busca por todos os pedidos compreendidos entre as datas
	 * repassadas e retorna um array com os dados
	 * @param array $datas (dataInicio e dataFim para realizar a busca)
	 * @return array $dados Resultado da busca
	 */
	public function getDadosEntregas(array $datas) {
		$dados = SqlQuery::select('pedido', $datas, ['entregador', 'status'], 'BETWEEN', 'AND', 'bus_pedido');
		return (array)$dados;
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
		$caixa->setData(date('Y-m-d'));
		$caixa->setUsername();
		$search = SqlQuery::select('caixa', ['data'=>$caixa->getData()], ['quantia','codigo'], '=', '', 'bus_caixa');
		$caixa->setCodigo($search[0]->codigo);
		$caixa->setQuantia($search[0]->quantia);
		$caixa->setQuantia($caixa->getQuantia()+$this->valorPago);
		if($this->valorTroco > 0) {
			if($caixa->getQuantia() < $this->valorTroco) {
				throw new Exception('Não há valor suficiente no caixa para o troco!');
			}
			$caixa->setQuantia($caixa->getQuantia()-$this->valorTroco);
		}
		$caixa->addQuantia();
		
		$dados = [
			'data' => $this->getData(),
			'cliente' => $this->cliente,
			'produtos' => $this->produto,
			'tipoPagamento' => $this->tipoPagamento,
			'valorTotal' => $this->valorTotal-$this->desconto,
			'valorPago' => $this->valorPago,
			'valorTroco' => $this->valorTroco,
			'desconto' => $this->desconto,
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
	
	/**
	 * Realiza uma busca no sgbd por todos os dados do pedido
	 * com base no campo $codigo da classe
	 * @return array|boolean
	 */
	public function search() {
		$search = SqlQuery::select('pedido', ['codigo'=>$this->getCodigo()], '*', '=', '', 'bus_pedido');
		if($search) {
			return (array)$search;
		}
		return false;
	}
	
	/**
	 * Atualiza o status do pedido com base nos campos
	 * $codigo da classe.
	 */
	public function update() {
		$upd = SqlQuery::update('pedido', ['codigo'=>$this->codigo,'status'=>$this->status], 'upd_pedido');
		if($upd) {
			return true;
		}
		return false;
	}
	
	/**
	 * Remove um pedido do banco de dados
	 * @param int codigo Código do pedido
	 * @return bool
	 */
	public function delete(int $codigo) {
		$delete = SqlQuery::drop('pedido', ['codigo', $codigo], 'del_pedido');
		if($delete) {
			return true;
		}
		return false;
	}
}