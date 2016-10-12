<?php

namespace App\controller;

use App\model\Usuario;
use App\view\ViewUsuario;
use App\widgets\dialog\Message;
use App\widgets\container\DataGrid;
use Exception;

class ControlUsuario
{
	public function cadastrar() {
		
		if($_SESSION['permission'] !== 'all') {
			throw new Exception('Usuário sem permissão de acesso! Contate o adinistrador so sistema!');
		}
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		$verif = true;
		
		if($action == 'submit') {
			
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
			
			$model = new Usuario();
			$model->setUsername($_POST['username']);
			$model->setPassword($_POST['password']);
			if($model->getPassword() !== md5($_POST['passwordCheck'])) {
				$verif = false;
				goto verif;
			}
			$model->setNickname($_POST['nickname']);
			$model->setPermission($_POST['permissoes']);
			if($model->send()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Usuário {$model->getNickname()} cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar o usuário {$model->getNickname()}! Contate o suporte!");
			}
		}
		
		//goto verif
		verif:		
		if(!$verif) {
			$msg = new Message();
			$msg->setContent('Atenção!', 'Senhas não conferem! Revise-as!', 'danger');
			echo $msg->show();
		}
		
		$view = new ViewUsuario();
		$view->show();
	}
	
	public function consultar() {

		if($_SESSION['permission'] !== 'all') {
			throw new Exception('Usuário sem permissão de acesso! Contate o adinistrador so sistema!');
		}
		
		$model = new Usuario();
		$result = $model->search();
		$tableResult;
		if($result) {
			$table = new DataGrid('Lista de usuário cadastrados', 'ControlUsuario');
			$table->setColunHeaders(array('#','Usuário','Senha','Nome','Perissões'));
			$table->setRowItens($result);
			$tableResult = $table->mount();
		} else {
			$msg = new Message();
			$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
			echo $msg->show();
		}
		
		echo $tableResult;
	}
	
	public function deletar() {
		
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
		
		$model = new Usuario();
		$model->setCodigo($codigo);
		$result = $model->delete();
		
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Usuário deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o usuário!');
		}
		
		/** chama a view usuário para montar o formulário de consulta **/
		$this->consultar();
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
				
			$model = new Usuario();
			$model->setCodigo($_POST['codigo']);
			$model->setUsername($_POST['username']);
			$model->setPassword($_POST['password']);
			$model->setNickname($_POST['nickname']);
			$model->setPermission($_POST['permissoes']);
				
			/** envia requisição para atualizar **/
			if($model->upd()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Usuário {$model->getNickname()} atualizado com sucesso!", 'success');
				echo $msg->show();
			}
				
			/** chama a view cliente para montar o formulário de consulta **/
			$view = new ViewUsuario();
			$view->show();
				
		} else {
				
			$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
			$model = new Usuario();
			$model->setCodigo($codigo);
			$dados = array(
					'codigo' => $model->getCodigo()
			);
			$dados = $model->search($dados);
			$view = new ViewUsuario();
			$view->showEditForm($dados);
		}		
	}
}