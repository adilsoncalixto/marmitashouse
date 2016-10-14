<?php

namespace App\view;

use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\form\Button;
use App\widgets\form\Input;
use App\widgets\form\Select;
use Exception;

/**
 * Gerencia a configuração e exibição do formulaŕio para
 * cadastrar e editar dados dos clientes
 * @author Jorge Lucas
 * @inheritdoc App\view\FormBasic
 */
class ViewUsuario implements FormBasic
{
	/**
	 * @var string $title Título do formulário
	 * @var string $form Formulário
	 */
	private $title;
	private $form;
	
	/**
	 * Configura e exibe o formulário de acordo com o método
	 * anteriormente chamado.
	 * {@inheritDoc}
	 * @see \App\view\FormBasic::show()
	 */
	public function show() {
		
		$sessao = new Session();
		$sessao->generateNewToken();
		
		$config = isset($_GET['method']) ? $_GET['method'] : null;
		$config = ($config == 'deletar') || ($config == 'editar') ? $config = 'cadastrar' : $config;
		
		$div = new Element("div");
		$div->setAttribute('class', 'form');
		echo $div->show();
		
		$form = new Element("form");
		$form->setAttribute("method", "post");
		$input = new Input();
		$content = '';
		
		switch($config) 
		{
			case 'cadastrar':
				$form->setAttribute("action", "?class=ControlUsuario&method=cadastrar&action=submit");
				$content .= $form->show();
				
				//input username
				$input->setType('text');
				$input->setAttributes('name', 'username');
				$input->setAttributes('id', 'username');
				$input->setAttributes('placeholder', 'ex: fulanodetal');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('username', 'Nome de usuário:');
				$content .= $input->show();
				
				//input username
				$input->setType('text');
				$input->setAttributes('name', 'nickname');
				$input->setAttributes('id', 'nickname');
				$input->setAttributes('placeholder', 'ex: Fulano (dois nomes, no máximo)');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('nickname', 'Nome:');
				$content .= $input->show();
				
				//input password
				$input->setType('password');
				$input->setAttributes('name', 'password');
				$input->setAttributes('id', 'password');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('password', 'Senha:');
				$content .= $input->show();
				
				//input password (para comparar)
				$input->setType('password');
				$input->setAttributes('name', 'passwordCheck');
				$input->setAttributes('id', 'passwordCheck');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('passwordCheck', 'Senha (confirmação):');
				$content .= $input->show();	
				
				//input permissões
				$select = new Select('permissoes');
				$select->setLabel('permissoes', 'Permissões:');
				$select->setOptions(array(
						'all' => 'Completo',
						'bas' => 'Básico'
				));
				$content .= $select->show();
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				$bt = new Button();
				$bt->setContent("Cadastrar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "entrar");
				$content .= $bt->show();
				
				$bt->setContent('Limpar');
				$bt->setAttributes('type', 'reset');
				$bt->setAttributes('value', 'limpar');
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Novo usuário';
				$this->form = $content;
				break;
				
			case 'consultar':
				unset($form);
				$this->title = 'Lista de usuários';
				$this->form = $content;
				break;
				
			default:
				throw new Exception('Requisição inválida!');
				break;
		}

		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
		echo $div->closeTag();
	}
	
	/**
	 * Configura e exibe o formulário de adição de dados com os mesmo
	 * carregados
	 * @param array $dados Dados invocados para realizar a edição
	 */
	public function showEditForm(array $dados) {
	
		$sessao = new Session();
		$sessao->generateNewToken();
	
		$div = new Element("div");
		$div->setAttribute('class', 'form');
		echo $div->show();
	
		$content = '';
		$form = new Element("form");
		$input = new Input();
		$form->setAttribute("method", "post");
	
		$form->setAttribute("action", "?class=ControlUsuario&method=editar&action=atualizar");
		$content .= $form->show();
	
		foreach ($dados as $obj) {
			foreach ($obj as $prop => $val) {
				if($prop == 'codigo') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$input->setAttributes('required', 'required');
					$input->setAttributes('value', $val);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, strtoupper($prop));
					$content .= $input->show();
					continue;
				}
				if($prop == 'permissoes') {
					//input permissões
					$select = new Select('permissoes');
					$select->setLabel('permissoes', 'Permissões:');
					$select->setOptions(array(
							'all' => 'Completo',
							'bas' => 'Básico'
					));
					$content .= $select->show();
					continue;
				}
				$input->setType('text');
				$input->setAttributes('name', $prop);
				$input->setAttributes('id', $prop);
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setAttributes('value', $val);
				$input->setLabel($prop, strtoupper($prop));
				$content .= $input->show();
			}
		}
	
		//hash de verificação
		$input->setType('hidden');
		$input->setAttributes('name', 'token');
		$input->setAttributes('value', $_SESSION['_token']);
		$input->setLabel('', '');
		$content .= $input->show();
	
		$bt = new Button();
		$bt->setContent("Atualizar");
		$bt->setAttributes("type", "submit");
		$bt->setAttributes("value", "entrar");
		$content .= $bt->show();
	
		$content .= $form->closeTag();
		$this->title = 'Atualizar dados do usuário';
		$this->form = $content;
	
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
	
		echo $div->closeTag();
	}
}