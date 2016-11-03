<?php

namespace App\widgets\container;

use DateTime;

/**
 * Geradora de tabela
 * @author Jorge Lucas
 */
class DataGrid
{
	/**
	 * @var string $control Nome da classe controller a ser inserida da url
	 * @var string $tableHeader Nome da teabela
	 * @var array $conlunHeader Títulos das colunas que farão parte da tabela
	 * @var array $rowItens Conjunto de dados que serão exibidos na tabela
	 */
	private $control;
	private $tableHeader;
	private $colunHeader;
	private $rowItens;

	/**
	 * Construtor da classe. Recebe o título e a classe de controle
	 * @param string $header
	 * @param string $control
	 * @return void
	 */
	public function __construct(string $header, string $control) {
		$this->tableHeader = $header;
		$this->control = $control;
	}
	
	/**
	 * Armazena o cabeçalho da tabela
	 * @param array $itens Conjunto com os cabeçalhos da tabela
	 * @return void
	 */
	public function setColunHeaders(array $itens) {
		$this->colunHeader = $itens;
	}
	
	/**
	 * Armazena os dados que serão exibidos na tabela
	 * @param array $itens
	 * @return void
	 */
	public function setRowItens(array $itens) {
		$this->rowItens = $itens;
	}
	
	/**
	 * Configura e exibe a tabela
	 * @param array $acoes Array com as ações cabíveis ao dados da linha
	 * @return string $table Tabela montada
	 */
	public function mount(array $acoes) {
		$table = <<<TABLE
		
		<div class='panel panel-default form'>
			<div class='panel-heading'>
				{$this->tableHeader}
			</div>
			<table class='table table-hover'>
				<thead>
					<tr>
TABLE;
		//Configura o cabeçalho
		foreach ($this->colunHeader as $colun) {
			$table .= "<th>$colun</th>\n";
		}
		
		$table .= <<<TABLE
					</tr>
				</thead>
				<tbody>
TABLE;
		//Configura os itens das linhas
		foreach ($this->rowItens as $item) {
			$table .= "<tr>";
			$propertie;
			$value;
			//Salva as propriedades pra montar o link
			foreach($item as $prop => $val) {
				if($prop == 'codigo') {
					$propertie = $prop;
					$value = $val;
				}
				
				/* formata as datas para o formato dd/mm/AAAA */
				if($prop == 'data') {
					$val = new DateTime($val);
					$val = $val->format('d/m/Y');
				}
				$table .= "<td>$val</td>";
			}
			$table .= "<td>";
			//Configura as ações
			foreach ($acoes as $acao) {
				$text = ucfirst($acao);
				$table .= <<<TABLE
				
					<a href='?class={$this->control}&method={$acao}&{$propertie}={$value}'>{$text}</a>
				
TABLE;
			}
			$table .= "</td></tr>";
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