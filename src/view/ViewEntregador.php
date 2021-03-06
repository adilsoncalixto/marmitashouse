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
 * Trata da exibição dos formulários envolvendo o
 * Entregador
 * @author Jorge Lucas
 */
class ViewEntregador implements FormBasic
{
	/**
	 * @var string $title Título do formulário
	 * @var string $form Formulário
	 * @var array $select Empresas Terceirizadas
	 */
	private $title;
	private $form;
	public $select;
	
	/**
	 * Configura e exibe o formulário de acordo com o método
	 * anteriormente chamado.
	 * {@inheritDoc}
	 * @see \App\view\FormBasic::show()
	 */
	public function show() {
		
		$config = isset($_GET['method']) ? $_GET['method'] : null;
		
		if($config == 'deletar' || $config == 'editar') {
			$config = 'consultar';
		}
		
		$sessao = new Session();
		$sessao->generateNewToken();
		
		$div = new Element("div");
		$div->setAttribute('class', 'form');
		echo $div->show();
		
		$content = '';
		$form = new Element("form");
		$input = new Input();
		$form->setAttribute("method", "post");
		
		switch($config)
		{
			case 'cadastrar':
				$form->setAttribute("action", "?class=ControlEntregador&method=cadastrar&action=submit");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('nome', 'Nome:');
				$content .= $input->show();
				
				//input CPF
				$input->setType('text');
				$input->setAttributes('name', 'cpf');
				$input->setAttributes('id', 'cpf');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('cpf', 'CPF:');
				$content .= $input->show();
				
				//input RG
				$input->setType('text');
				$input->setAttributes('name', 'rg');
				$input->setAttributes('id', 'rg');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('rg', 'RG:');
				$content .= $input->show();
				
				//input Telefone
				$input->setType('text');
				$input->setAttributes('name', 'telefone');
				$input->setAttributes('id', 'telefone');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('telefone', 'Número para contato:');
				$content .= $input->show();
				
				//input Empresas
				$select = new Select('empresa');
				$select->setLabel('empresa', 'Empresa Terceirizada:');
				$select->setOptions($this->select);
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
				$this->title = 'Novo Entregador';
				$this->form = $content;
				break;
			
			case 'consultar':
				$form->setAttribute("action", "?class=ControlEntregador&method=consultar&action=search");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setLabel('nome', 'Buscar por nome:');
				$content .= $input->show();
				
				//input CPF
				$input->setType('text');
				$input->setAttributes('name', 'cpf');
				$input->setAttributes('id', 'cpf');
				$input->setAttributes('size', '300');
				$input->setAttributes('maxlength', '11');
				$input->setLabel('cpf', 'Buscar por CPF:');
				$content .= $input->show() . '<br>';
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				//input Submit
				$bt = new Button();
				$bt->setContent("Buscar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "buscar");
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Buscar entregador';
				$this->form = $content;
				break;
				
			default:
				throw new Exception('Método não encontrado!');
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
	
		$form->setAttribute("action", "?class=ControlEntregador&method=editar&action=atualizar");
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
				
				if($prop == 'empresa') {
					//input Empresas
					$select = new Select('empresa');
					$select->setLabel('empresa', 'Empresa Terceirizada:');
					$select->setOptions($this->select);
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
		$this->title = 'Atualizar dados do entregador';
		$this->form = $content;
	
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
	
		echo $div->closeTag();
	}
}