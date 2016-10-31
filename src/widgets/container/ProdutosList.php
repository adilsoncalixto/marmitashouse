<?php

namespace App\widgets\container;

/**
 * Cria uma seção e lista todos os produtos enviados via 'setItens' e,
 * entçao gera campos com nome, botão add, rem e quantia
 * @author Jorge Lucas
 */
class ProdutosList
{
	/**
	 * @var string label Label associada ao campo
	 * @var string id Id da tag HTML
	 * @var array itens Conjunto dos itens a serem exibidos
	 */
	private $label;
	private $id;
	private $itens;
	
	/**
	 * Define label e id
	 * @param string $label
	 * @param string $id
	 */
	public function __construct(string $label, string $id) {
		$this->label = $label;
		$this->id = $id;
	}
	
	/**
	 * Armazena o conjunto dos itens a serem exibidos
	 * @param array $itens Conjunto dos itens
	 * @return void
	 */
	public function setItens(array $itens) {
		$this->itens = $itens;
	}
	
	/**
	 * Reune os dados e gera a estrutura com os dados
	 * @return string Estrutura montada
	 */
	public function mount() {
		$lastId = 0;
		$content  = "<label for='{$this->id}'>{$this->label}</label>\n";
		$content .= "<div class='produtosList'>";
		
		foreach ($this->itens as $item) {
			$content .= "<div class='input-group'>\n";
			$content .= "<input type='text' class='form-control' value='{$item->nome} - {$item->tamanho} - R$ {$item->valor} - Contém: {$item->descricao}' readonly>\n";
			$content .= "<span class='input-group-btn'><button class='btn btn-info' type='button'  title='Adicionar item' id='add{$item->codigo}'>+</button></span>\n";
			$content .= "<span class='input-group-btn'><button class='btn btn-danger' type='button' title='Remover item' id='rmv{$item->codigo}'>-</button></span>\n";
			$content .= "<span class='input-group-addon' title='Quantidade de itens adicionados' id='qtd{$item->codigo}'>0</span>\n";
			$content .= "</div>\n";
			$lastId = $item->codigo;
		}
		$content .= "<input type='hidden' id='lastId' value='{$lastId}'>";
		$content .= "</div>";
		return $content;
	}
}