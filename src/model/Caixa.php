<?php

namespace App\model;

use App\database\SqlQuery;
use App\utils\FilterInput;

/**
 * Classe responsável por gerenciar a movimentação de caixa (R$)
 * @author Jorge Lucas
 */
class Caixa
{
	use SqlQuery;
	use FilterInput;
	private $data;
	private $quantia;
	
	public function setData() {
		$this->data = date("d-m-Y");
	}
	
	public function setQuantia(double $quantia) {
		$this->quantia = $quantia;
	}
	
	public function getQuantia() {
		return $this->quantia;
	}
	
	public function addQuantia(double $quantia) {
		
	}
	
	public function rmvQuantia(double $quantia) {
		
	}
	
	public function listCaixa() {
		
	}
	
	public function delCaixa() {
		
	}
 }