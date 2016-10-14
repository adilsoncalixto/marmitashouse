<?php

namespace App\controller;

use App\view\ViewCliente;
use App\model\Cliente;
use App\widgets\dialog\Message;
use App\widgets\container\DataGrid;
use Exception;

/**
 * Controla todas as requisições envolvendo o cliente, ou seja, bsicamente
 * o seu CRUD
 * @author Jorge Lucas
 */
class ControlCliente
{	
	/**
	 * Responsável por realizar o recebimento dos dados do formuário
	 * cadastrar cliente e enviá-los a classe model
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
			
			$model = new Cliente();
			$model->setNome($_POST['nome']);
			$model->setDtNascimento($_POST['dtNascimento']);
			$model->setTelefone((int)$_POST['telefone']);
			$model->setEndereco($_POST['endereco']);
			$model->setBairro($_POST['bairro']);
			$model->setPtReferencia($_POST['ptReferencia']);
			if($model->send()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Cliente {$model->getNome()} cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar o cliente {$model->getNome()}! Contate o suporte!");
			}
		}
		
		$view = new ViewCliente();
		$view->show();
	}
	
	/**
	 * Responsável por gerenciar as requisições de consulta do formulário consultar cliente
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
			
			$model = new Cliente();
			$model->setNome(empty($_POST['nome']) ? '' : $_POST['nome']);
			$model->setTelefone(empty($_POST['telefone']) ? 0 : $_POST['telefone']);
			
			/** 
			 * GAMBIARRA
			 * Verifica os 3 principais dados para realizar a consulta segura e correta e
			 * evitar conflito durante a consulta e a requisição da funcção para a atualização de dados
			 */
			if(empty($model->getNome()) && empty($model->getTelefone()) && empty($model->getCodigo())) {
				$dados = array(
						'nome' => $model->getNome(),
						'telefone' => $model->getTelefone()
				);
			} else if(!empty($model->getNome()) && empty($model->getTelefone())) {
				$dados = array(
						'nome' => $model->getNome()
				);
			} else if(empty($model->getNome()) && !empty($model->getTelefone())) {
				$dados = array(
						'telefone' => $model->getTelefone()
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
				$table = new DataGrid('Resultados da busca', 'ControlCliente');
				$table->setColunHeaders(array('#','Nome','Data nascimento','Telefone','Endereço','Bairro','Ponto de Referencia','Ação'));
				$table->setRowItens($result);
				$tableResult = $table->mount(['editar', 'deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewCliente();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}

	/**
	 * Gerencia as requisições para deletar os dados do cliente baseado em seu código
	 * e exibe uma mensagem de sucesso, senão, lança uma excessão
	 * @throws Exception
	 */
	public function deletar() {
		
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
		
		$model = new Cliente();
		$model->setCodigo($codigo);
		$result = $model->delete();
		
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Cliente deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o cliente!');
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewCliente();
		$view->show();
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
			
			$model = new Cliente();
			$model->setCodigo($_POST['codigo']);
			$model->setNome($_POST['nome']);
			$model->setDtNascimento($_POST['dtNascimento']);
			$model->setTelefone((int)$_POST['telefone']);
			$model->setEndereco($_POST['endereco']);
			$model->setBairro($_POST['bairro']);
			$model->setPtReferencia($_POST['ptReferencia']);
			
			/** envia requisição para atualizar **/
			if($model->update()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Cliente {$model->getNome()} atualizado com sucesso!", 'success');
				echo $msg->show();
			}
			
			/** chama a view cliente para montar o formulário de consulta **/
			$view = new ViewCliente();
			$view->show();
			
		} else {
			
			$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
			$model = new Cliente();
			$model->setCodigo($codigo);
			$dados = array(
					'codigo' => $model->getCodigo()
			);
			$dados = $model->search($dados);
			$view = new ViewCliente();
			$view->showEditForm($dados);
		}
	}
}