<?php

namespace App\model;

use App\utils\FilterInput;
use App\model\EmpresaTerceirizada;

class Entregador extends FilterInput
{
	private $codigo;
	private $nome;
	private $cpf;
	private $rg;
	private $celular;
	private $empresa;
	
	public function setCodigo(int $codigo) {
		
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function setNome(string $nome) {
		
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setCpf(string $cpf) {
		
	}
	
	public function getCpf() {
		return $this->cpf;
	}
	
	public function setRg(string $rg) {
		
	}
	
	public function getRg() {
		return $this->rg;
	}
	
	public function setCelular(string $celular) {
		
	}
	
	public function getCelular() {
		return $this->celular;
	}
	
	public function setEmpresa(EmpresaTerceirizada $empresa) {
		
	}
	
	public function getEmpresa() {
		return $this->empresa;
	}
}