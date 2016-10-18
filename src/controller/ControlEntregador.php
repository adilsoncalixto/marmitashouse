<?php

namespace App\controller;

use App\database\SqlQuery;
use App\model\Entregador;
use App\view\ViewEntregador;
use App\widgets\dialog\Message;
use App\widgets\container\DataGrid;
use Exception;


/**
 * Gerencia as requisições relacionadas a Entregador
 * @author Jorge Lucas
 */
class ControlEntregador
{
	use SqlQuery;
	
	/**
	 * Responsável por realizar o recebimento dos dados do formuário
	 * cadastrar entregador e enviá-los a classe model
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
				
			$model = new Entregador();
			$model->setNome($_POST['nome']);
			$model->setCpf($_POST['cpf']);
			$model->setRg($_POST['rg']);
			$model->setTelefone($_POST['telefone']);
			$model->setEmpresa($_POST['empresa']);
			if($model->save()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Entregador {$model->getNome()} cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar o entregador {$model->getNome()}! Contate o suporte!");
			}
		}
		
		$view = new ViewEntregador();
		
		/** busca todas es empresas no banco para formar uma lista **/
		$search = SqlQuery::select('empresa', [], '*', '', '', 'bus_empresa');
		$dados = [];
		foreach ($search as $array) {
			foreach ($array as $key => $value) {
				if($key == 'nome') {
					$dados[$value] = $value . " (código: {$array->codigo})";
				}
			}
		}
		
		$view->select = $dados;
		$view->show();
	}
	
	/**
	 * Responsável por gerenciar as requisições de consulta do formulário consultar entregador
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
				
			$model = new Entregador();
			$model->setNome(empty($_POST['nome']) ? '' : $_POST['nome']);
			$model->setCpf(empty($_POST['cpf']) ? '' : $_POST['cpf']);
				
			/**
			 * GAMBIARRA
			 * Verifica os 3 principais dados para realizar a consulta segura e correta e
			 * evitar conflito durante a consulta e a requisição da funcção para a atualização de dados
			 */
			if(empty($model->getNome()) && empty($model->getCpf()) && empty($model->getCodigo())) {
				$dados = array(
						'nome' => $model->getNome(),
						'cpf' => $model->getCpf()
				);
			} else if(!empty($model->getNome()) && empty($model->getCpf())) {
				$dados = array(
						'nome' => $model->getNome()
				);
			} else if(empty($model->getNome()) && !empty($model->getCpf())) {
				$dados = array(
						'cpf' => $model->getCpf()
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
				$table = new DataGrid('Resultados da busca', 'ControlEntregador');
				$table->setColunHeaders(array('#','Nome','CPF','RG','Telefone','Cód. Empresa','Ação'));
				$table->setRowItens($result);
				$tableResult = $table->mount(['editar', 'deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewEntregador();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}
	
	/**
	 * Responsável por gerenciar as requisições para edição dos dados do código do
	 	* cliente passado via url
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
	 			
	 		$model = new Entregador();
	 		$model->setCodigo($_POST['codigo']);
	 		$model->setNome($_POST['nome']);
	 		$model->setCpf($_POST['cpf']);
	 		$model->setRg($_POST['rg']);
	 		$model->setTelefone($_POST['telefone']);
	 		$model->setEmpresa($_POST['empresa']);
	 			
	 		/** envia requisição para atualizar **/
	 		if($model->update()) {
	 			$msg = new Message();
	 			$msg->setContent('Concluído!', "Entregador {$model->getNome()} atualizado com sucesso!", 'success');
	 			echo $msg->show();
	 		}
	 			
	 		/** chama a view cliente para montar o formulário de consulta **/
	 		$view = new ViewEntregador();
	 		$view->show();
	 			
	 	} else {
	 			
	 		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
	 		$model = new Entregador();
	 		$model->setCodigo($codigo);
	 		$dados = array(
	 				'codigo' => $model->getCodigo()
	 		);
	 		$dados = $model->search($dados, '=');
	 		$view = new ViewEntregador();
	 		
	 		/** busca todas es empresas no banco para formar uma lista **/
	 		$search = SqlQuery::select('empresa', [], '*', '', '', 'bus_empresa');
	 		$options = [];
	 		foreach ($search as $array) {
	 			foreach ($array as $key => $value) {
	 				if($key == 'nome') {
	 					$options[$value] = $value . " (código: {$array->codigo})";
	 				}
	 			}
	 		}
	 		
	 		$view->select = $options;
	 		$view->showEditForm($dados);
	 	}
	 }
	
	/**
	 * Gerencia as requisições para deletar os dados do cliente baseado em seu código
	 * e exibe uma mensagem de sucesso, senão, lança uma excessão
	 * @throws Exception
	 */
	public function deletar() {
	
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
	
		$model = new Entregador();
		$model->setCodigo($codigo);
		$result = $model->delete();
	
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Entregador deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o entregador!');
		}
	
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewEntregador();
		$view->show();
	}
}