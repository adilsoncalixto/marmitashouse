<?php

namespace App\model;

use App\utils\FilterInput;

class Produto extends FilterInput
{
	private $codigo;
	private $nome;
	private $descricao;
	private $tamanho;
	private $valor;
	
	public function setCodigo(int $codigo) {
		
	}
	
	public function getCodigo() {
		
	}
	
	public function setNome(string $nome) {
		
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setDescricao(string $descricao) {
		
	}
	
	public function getDescricao() {
		return $this->descricao;
	}
	
	public function setValor(string $valor) {
		
	}
	
	public function getValor() {
		return $this->valor;
	}
}