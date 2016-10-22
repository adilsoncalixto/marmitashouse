<?php

namespace App\controller;

use App\model\Produto;
use App\view\ViewProduto;
use App\widgets\dialog\Message;
use App\widgets\container\DataGrid;
use Exception;

/**
 * Controla todas as requisições envolvendo o produto, ou seja, basicamente
 * o seu CRUD
 * @author Jorge Lucas
 */
class ControlProduto
{
	/**
	 * Responsável por realizar o recebimento dos dados do formuário
	 * cadastrar produto e enviá-los a classe model
	 * @throws Exception Lança uma excessão caso o cadastro retorne um erro
	 */
	public function cadastrar() {
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		/** variável ação (action) passada pela url **/
		if($action == 'submit') {
				
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
				
			$model = new Produto();
			$model->setNome($_POST['nome']);
			$model->setDescricao($_POST['descricao']);
			$model->setTamanho($_POST['tamanho']);
			$model->setValor($_POST['valor']);
			if($model->save()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Produto {$model->getNome()} cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar o produto {$model->getNome()}! Contate o suporte!");
			}
		}
		
		$view = new ViewProduto();
		$view->show();
	}
	
	/**
	 * Responsável por gerenciar as requisições de consulta do formulário consultar produto
	 * @throws Exception
	 */
	public function consultar() {
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		$tableResult = null;
		$msg = null;
		
		/** variável ação (action) passada pela url **/
		if($action == 'search') {
				
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
				
			$model = new Produto();
			$model->setNome(empty($_POST['nome']) ? '' : $_POST['nome']);
			$model->setDescricao(empty($_POST['descricao']) ? 0 : $_POST['descricao']);
				
			/**
			 * GAMBIARRA
			 * Verifica os 3 principais dados para realizar a consulta segura e correta e
			 * evitar conflito durante a consulta e a requisição da funcção para a atualização de dados
			 */
			if(empty($model->getNome()) && empty($model->getDescricao()) && empty($model->getCodigo())) {
				$dados = array(
						'nome' => $model->getNome(),
						'descricao' => $model->getDescricao()
				);
			} else if(!empty($model->getNome()) && empty($model->getDescricao())) {
				$dados = array(
						'nome' => $model->getNome()
				);
			} else if(empty($model->getNome()) && !empty($model->getDescricao())) {
				$dados = array(
						'descricao' => $model->getDescricao()
				);
			} else {
				$dados = array(
						'codigo' => $model->getCodigo()
				);
			}
				
			$result = $model->search($dados);
				
			/** caso retorne dados, é montado uma tabela carregada com os mesmos, senão retorna
			 * uma mensagem informando que não há dados **/
			if($result) {
				$table = new DataGrid('Resultados da busca', 'ControlProduto');
				$table->setColunHeaders(array('#','Nome','Descrição','Tamanho', 'Preço','Ação'));
				$table->setRowItens($result);
				$tableResult = $table->mount(['editar', 'deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewProduto();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}
	
	/**
	 * Responsável por gerenciar as requisições para edição dos dados do código do
	 * produto passado via url
	 * @throws Exception
	 */
	 public function editar() {
	
	 	/** variável ação (action) passada pela url **/
	 	$action = isset($_GET['action']) ? $_GET['action'] : null;
	
	 	/**
	 	 * Verifica se a ação é atualizar, caso não seja, significa que terá de montar primeiro
	 	 * o formulário para edição dos dados para enfim sua submissão
	 	 */
	 	if($action == 'atualizar') {
	 			
	 		/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
	 		if($_POST['token'] !== $_SESSION['_token']) {
	 			throw new Exception('Token inválido!');
	 		}
	 			
	 		$model = new Produto();
	 		$model->setCodigo($_POST['codigo']);
	 		$model->setNome($_POST['nome']);
	 		$model->setDescricao($_POST['descricao']);
	 		$model->setTamanho($_POST['tamanho']);
	 		$model->setValor($_POST['valor']);
	 			
	 		/** envia requisição para atualizar **/
	 		if($model->update()) {
	 			$msg = new Message();
	 			$msg->setContent('Concluído!', "Produto {$model->getNome()} atualizado com sucesso!", 'success');
	 			echo $msg->show();
	 		}
	 			
	 		/** chama a view cliente para montar o formulário de consulta **/
	 		$view = new ViewProduto();
	 		$view->show();
	 			
	 	} else {
	 			
	 		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
	 		$model = new Produto();
	 		$model->setCodigo($codigo);
	 		$dados = array(
	 				'codigo' => $model->getCodigo()
	 		);
	 		$dados = $model->search($dados);
	 		$view = new ViewProduto();
	 		$view->showEditForm($dados);
	 	}
	 }
	
	/**
	 * Gerencia as requisições para deletar os dados do produto baseado em seu código
	 * e exibe uma mensagem de sucesso, senão, lança uma excessão
	 * @throws Exception
	 */
	public function deletar() {
	
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
	
		$model = new Produto();
		$model->setCodigo($codigo);
		$result = $model->delete();
	
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Produto deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o cliente!');
		}
	
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewProduto();
		$view->show();
	}
}