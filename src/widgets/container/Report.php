<?php

namespace App\widgets\container;

class Report
{
	private $totalEntregas;
	private $entregadores;
	
	public function __construct(array $dados, int $totalEntregas) {
		$this->totalEntregas = $totalEntregas;
		$this->entregadores = $dados;
	}
	
	public function show() {
		$report = '';
		$valuemax = 0;
		foreach ($this->entregadores as $entregador => $QtdEntrega) {
			$percent = ($QtdEntrega / $this->totalEntregas) * 100;
			$report .= <<<PROGRESS
			<label for='{$entregador}'>{$entregador} - {$QtdEntrega} entregas realizadas</label>
			<div class='progress' id='{$entregador}'>
				<div class='progress-bar' role='progressbar' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='{$this->totalEntregas}' style='width:{$percent}%;'>		
				</div>
			</div>
PROGRESS;
		}
		return $report;
	}
}
