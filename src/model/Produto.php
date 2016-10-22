<?php

namespace App\model;

use App\utils\FilterInput;
use App\database\SqlQuery;

/**
 * Classe responsável por gerenciar os dados do produto repassados
 * pela classe ControlProduto e rquisitar operações com o banco de dados
 * @author Jorge Lucas
 */
class Produto
{
	/**
	 * @trait SqlQuery "Classe" que monstará as instruções sql
	 * @trait FilterIput "Classe" que realiza a remoção de caracteres nocivos
	 * @var $codigo Código vinculado ao cliente
	 * @var $nome Nome vinculado ao cliente
	 * @var $descricao Conjunto de palavras-chave dos itens que a compõe
	 * @var $tamanho Tamanho da marmita
	 * @var $valor Preço da marmita
	 */
	use SqlQuery;
	use FilterInput;
	private $codigo;
	private $nome;
	private $descricao;
	private $tamanho;
	private $valor;
	
	/**
	 * Recebe uma variável do tipo inteiro, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param int $codigo
	 */
	public function setCodigo(int $codigo) {
		$this->codigo = intval($this->cleanInput($codigo));
	}
	
	/**
	 * Retorna o valor armazenado na variável $codigo
	 * @return int $codigo
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $nome
	 */
	public function setNome(string $nome) {
		$this->nome = $this->cleanInput($nome);	
	}
	
	/**
	 * Retorna o valor armazenado na variável $nome
	 * @return string $nome
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	* Recebe uma variável do tipo string, chama o método de remoção de caracteres
	* e armazena o valor retornado para a variável
	* @param string $descricao
	*/
	public function setDescricao(string $descricao) {
		$this->descricao = $this->cleanInput($descricao);	
	}
	
	/**
	 * Retorna o valor armazenado na variável $descricao
	 * @return string $descricao
	 */
	public function getDescricao() {
		return $this->descricao;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $tamanho
	 */
	public function setTamanho(string $tamanho) {
		$this->tamanho = $this->cleanInput($tamanho);
	}
	
	/**
	 * Retorna o valor armazenado na variável $tamanho
	 * @return string $tamanho
	 */
	public function getTamanho() {
		return $this->tamanho;
	}
	
	/**
	 * Recebe uma variável do tipo string, chama o método de remoção de caracteres
	 * e armazena o valor retornado para a variável
	 * @param string $valor
	 */
	public function setValor(string $valor) {
		$this->valor = $this->cleanInput($valor);
		$this->valor = str_replace(',', '.', $this->valor);
	}
	
	/**
	 * Retorna o valor armazenado na variável $valor
	 * @return string $valor
	 */
	public function getValor() {
		return $this->valor;
	}
	
	/**
	 * Coleta os dados armazenados nos campos da classe e tenta realizar
	 * a inserção no banco de dados
	 * @return boolean
	 */
	public function save() {
		$dados = array(
				'nome' => $this->nome,
				'descricao' => $this->descricao,
				'tamanho' => $this->tamanho,
				'valor' => $this->valor
		);
		$insert = SqlQuery::insert('produto', $dados, '', '', 'cad_produto');
		if($insert) {
			return true;
		}
		return false;
	}
	
	/**
	 * Recebe um array com os dados a serem buscados e, de acordo com os mesmos,
	 * deterima quais serão utilizados na busca no banco de dados usando o trait SqlQuery
	 * @param array $dados Dados a serem usados na busca
	 * @return array|boolean Caso complete a busca, retorna os dados, senão,
	 * retorn falso
	 */
	public function search(array $dados) {
	
		$select = SqlQuery::select('produto', $dados, '*', 'LIKE', 'OR', 'bus_produto');
		if(!empty($select)) {
			return (array)$select;
		}
		return false;
	}
	
	/**
	 * Lê os dados armazendos nos campos da classe e tenta atualizar os dados, utilizando
	 * o trait SqlQuery, no banco de dados.
	 * @return boolean
	 */
	public function update() {
		$dados = array(
				'codigo' => $this->codigo,
				'nome' => $this->nome,
				'descricao' => $this->descricao,
				'tamanho' => $this->tamanho,
				'valor' => $this->valor
		);
	
		if(SqlQuery::update('produto', $dados, 'upd_produto')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Lê o campo $codigo e solicita ao trait SqlQuery para deletar a linha
	 * no banco de dados
	 * @return boolean
	 */
	public function delete() {
		$delete = SqlQuery::drop('produto', array('codigo', $this->codigo), 'del_produto');
		if($delete) {
			return true;
		}
		return false;
	}
}