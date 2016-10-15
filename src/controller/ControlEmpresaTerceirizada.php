<?php

namespace App\controller;

use App\model\EmpresaTerceirizada;
use App\view\ViewEmpresaTerceirizada;
use App\widgets\container\DataGrid;
use App\widgets\dialog\Message;
use Exception;

/**
 * Gerencia as requisições envolvendo as movimentações da empresa, enfim,
 * o CRUD
 * @author Jorge Lucas
 */
class ControlEmpresaTerceirizada
{
	/**
	 * Responsável por realizar o recebimento dos dados do formuário
	 * cadastrar empresa e enviá-los a classe model
	 * @throws Exception Lança uma excessão caso o cadastro retorne um erro
	 */
	public function cadastrar() {
		
		/** variável ação (action) passada pela url **/
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		if($action == 'submit') {
			
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
			
			$model = new EmpresaTerceirizada();
			$model->setNome($_POST['nome']);
			$model->setCnpj($_POST['cnpj']);
			$model->setEndereco($_POST['endereco']);
			$model->setBairro($_POST['bairro']);
			$model->setCidade($_POST['cidade']);
			$model->setTelefone($_POST['telefone']);
			$model->setEmail($_POST['email']);
			if($model->save()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Empresa {$model->getNome()} cadastrada com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar a empresa {$model->getNome()}! Contate o suporte!");
			}
		}
		
		$view = new ViewEmpresaTerceirizada();
		$view->show();
	}
	
	public function consultar() {
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		$tableResult = null;
		$msg = null;
		
		if($action == 'search') {
			
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
			
			$model = new EmpresaTerceirizada();
			$model->setNome($_POST['nome']);
			if($_POST['cnpj'] == 14) {
				$model->setCnpj($_POST['cnpj']);
			}
			
			/**
			 * GAMBIARRA
			 * Verifica os 3 principais dados para realizar a consulta segura e correta e
			 * evitar conflito durante a consulta e a requisição da funcção para a atualização de dados
			 */
			if(empty($model->getNome()) && empty($model->getCnpj()) && empty($model->getCodigo())) {
				$dados = array(
						'nome' => $model->getNome(),
						'cnpj' => $model->getCnpj()
				);
			} else if(!empty($model->getNome()) && empty($model->getCnpj())) {
				$dados = array(
						'nome' => $model->getNome()
				);
			} else if(empty($model->getNome()) && !empty($model->getCnpj())) {
				$dados = array(
						'cnpj' => $model->getCnpj()
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
				$table = new DataGrid('Resultados da busca', 'ControlEmpresaTerceirizada');
				$table->setColunHeaders(['#','Nome', 'CNPJ', 'Endereço', 'Bairro', 'Cidade', 'Telefone', 'E-mail', 'Ação	']);
				$table->setRowItens($result);
				$tableResult = $table->mount(['editar', 'deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view empresa terc. para montar o formulário de consulta **/
		$view = new ViewEmpresaTerceirizada();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}
	
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
				
			$model = new EmpresaTerceirizada();
			$model->setCodigo($_POST['codigo']);
			$model->setNome($_POST['nome']);
			$model->setCnpj($_POST['cnpj']);
			$model->setEndereco($_POST['endereco']);
			$model->setBairro($_POST['bairro']);
			$model->setCidade($_POST['cidade']);
			$model->setTelefone((int)$_POST['telefone']);
			$model->setEmail($_POST['email']);			
				
			/** envia requisição para atualizar **/
			if($model->update()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Empresa {$model->getNome()} atualizada com sucesso!", 'success');
				echo $msg->show();
			}
				
			/** chama a view cliente para montar o formulário de consulta **/
			$view = new ViewEmpresaTerceirizada();
			$view->show();
				
		} else {
				
			$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
			$model = new EmpresaTerceirizada();
			$model->setCodigo($codigo);
			$dados = array(
					'codigo' => $model->getCodigo()
			);
			$dados = $model->search($dados);
			$view = new ViewEmpresaTerceirizada();
			$view->showEditForm($dados);
		}
	}
	
	/**
	 * Gerencia as requisições para deletar os dados da empresa baseado em seu código
	 * e exibe uma mensagem de sucesso, senão, lança uma excessão
	 * @throws Exception
	 */
	public function deletar() {
		
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
		
		$model = new EmpresaTerceirizada();
		$model->setCodigo($codigo);
		$result = $model->delete();
		
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Empresa deletada com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar a empresa!');
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewEmpresaTerceirizada();
		$view->show();
	}
}