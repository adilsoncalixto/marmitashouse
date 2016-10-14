<?php

namespace App\widgets\container;

/**
 * Geradora de tabela
 * @author Jorge Lucas
 */
class DataGrid
{
	private $control;
	private $tableHeader;
	private $colunHeader;
	private $rowItens;

	public function __construct(string $header, string $control) {
		$this->tableHeader = $header;
		$this->control = $control;
	}
	
	public function setColunHeaders(array $itens) {
		$this->colunHeader = $itens;
	}
	
	public function setRowItens(array $itens) {
		$this->rowItens = $itens;
	}
	
	public function mount() {
		$table = <<<TABLE
		
		<div class='panel panel-default form'>
			<div class='panel-heading'>
				{$this->tableHeader}
			</div>
			<table class='table table-hover'>
				<thead>
					<tr>
TABLE;
		
		foreach ($this->colunHeader as $colun) {
			$table .= "<th>$colun</th>\n";
		}
		
		$table .= <<<TABLE
					</tr>
				</thead>
				<tbody>
TABLE;
		
		foreach ($this->rowItens as $item) {
			$table .= "<tr>";
			$propertie;
			$value;
			foreach($item as $prop => $val) {
				if($prop == 'codigo' || $prop == 'data') {
					$propertie = $prop;
					$value = $val;
				}
				$table .= "<td>$val</td>";
			}
			$table .= <<<TABLE
				<td>
					<a href='?class={$this->control}&method=editar&{$propertie}={$value}'>Editar</a>
					<a href='?class={$this->control}&method=deletar&{$propertie}={$value}'>Deletar</a>
				</td>
TABLE;
			$table .= "</tr>";
		}
			
		$table .= <<<TABLE
				</tbody>
			</table>
		</div>
		<br>
TABLE;
		
		return $table;

	}
}