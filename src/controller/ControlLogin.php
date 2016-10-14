<?php

namespace App\controller;

use App\view\ViewLogin;
use App\model\Login;
use App\utils\Redirect;
use App\utils\Session;
use App\widgets\dialog\Message;
use Exception;

/**
 * Gerencia as requisições de login no sistema
 * @author Jorge Lucas
 */
class ControlLogin
{
	/**
	 * Exibe o form para login
	 * @return void
	 */
	public function show() {
		$view = new ViewLogin();
		$view->show();
	}
	
	/**
	 * Recebe os dados do formulário login e tenta
	 * autenticá-los no banco de dados
	 * @throws Exception
	 */
	public function auth() {
		$model = new Login();
		$sessao = new Session();
		$redir = new Redirect();
		
		/** verifica o hash do formulário para evitar reenvio de dados **/
		if($_POST['token'] !== $_SESSION['_token']) {
			throw new Exception('Token inválido!');
		}
		
		$model->setUsername($_POST['username']);
		$model->setPassword($_POST['password']);
		if($model->auth()) {
			$sessao->setValue('nickname', $model->getNickname());
			$sessao->setValue('permission', $model->getPermission());
			$redir->setUrl('?class=ControlHome');
			$redir->reload();
		} else {
			$sessao->clean();
			$msg = new Message();
			$msg->setContent('Atenção!', 'Usuário não encontrado!', 'danger');
			$this->show();
			echo $msg->show();
		}
	}
	
	/**
	 * Destrói a sessão a atual e redireciona para a página de login
	 */
	public function logout() {
		$sessao = new Session();
		$redir = new Redirect();
		
		$sessao->clean();
		$redir->setUrl('?class=ControlLogin');
		$redir->reload();
	}
}