<?php

namespace App\model;

use App\utils\FilterInput;
use App\model\Cliente;
use App\model\Produto;
use App\model\Entregador;

class Pedido extends FilterInput
{
	private $codigo;
	private $cliente;
	private $produto;
	private $quantidade;
	private $valorTotal;
	private $valorPago;
	private $valorTroco;
	private $caixa;
	private $tipoPagamento;
	private $entregador;
	private $dataPedido;
	private $status;
	
	public function setCodigo(int $codigo) {
		
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function setCliente(Cliente $cliente) {
		
	}
	
	public function getCliente() {
		return $this->cliente;
	}
	
	public function setProduto(Produto $produto) {
		
	}

	public function getProduto() {
		return $this->produto;
	}
	
	public function setQuantidade(int $quantidade) {
		
	}
	
	public function getQuantidade() {
		return $this->quantidade;
	}
	
	public function setValorToral(double $valorTotal) {
		
	}
	
	public function getValorTotal() {
		return $this->valorTotal;
	}
	
	public function setValorPago(double $valorPago) {
		
	}
	
	public function getValorPago() {
		return $this->valorPago;
	}
	
	public function setValorTroco(double $valorTroco) {
		
	}
	
	public function getValorTroco() {
		return $this->valorTroco;
	}
	
	public function setTipoPagamento(string $tipoPAgamento) {
		
	}
	
	public function getTipoPagamento() {
		return $this->tipoPagamento;
	}
	
	public function setEntregador(Entregador $entregador) {
		
	}
	
	public function getEntregador() {
		return $this->entregador;
	}
	
	public function setDataPedido(string $data) {
		
	}
	
	public function getDataPedido() {
		return $this->dataPedido;
	}
	
	public function setStatus(int $status) {
		
	}
	
	public function getStatus() {
		return $this->status;
	}
}