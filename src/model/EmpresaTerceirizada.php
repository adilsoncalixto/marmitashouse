<?php

namespace App\model;

use App\utils\FilterInput;

class EmpresaTerceirizada extends FilterInput
{
	private $codigo;
	private $nome;
	private $cnpj;
	private $endereco;
	private $bairro;
	private $cidade;
	private $telefone;
	private $email;
	
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
	
	public function setCnpj(string $cnpj) {
		
	}
	
	public function getCnpj() {
		return $this->cnpj;
	}
	
	public function setEndereco(string $endereco) {
		
	}
	
	public function getEndereco() {
		return $this->endereco;
	}
	
	public function setCidade(string $cidade) {
		
	}
	
	public function getCidade() {
		return $this->cidade;
	}
	
	public function setTelefone(string $telefone) {
		
	}
	
	public function getTelefone() {
		return $this->telefone;
	}
	
	public function setEmail(string $email) {
		
	}
	
	public function getEmail() {
		return $this->email;
	}
}