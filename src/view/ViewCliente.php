<?php

namespace App\view;

use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\form\Button;
use App\widgets\form\Input;
use Exception;

/**
 * Trata da exibição dos formulários envolvendo o
 * Cliente
 * @author Jorge Lucas
 */
class ViewCliente implements FormBasic
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
		
		switch ($config)
		{
			case 'cadastrar':
				$form->setAttribute("action", "?class=ControlCliente&method=cadastrar&action=submit");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('nome', 'Nome:');
				$content .= $input->show();
				
				//input Data de nascimento
				$input->setType('date');
				$input->setAttributes('name', 'dtNascimento');
				$input->setAttributes('id', 'dtNascimento');
				$input->setAttributes('size', '30');
				$input->setAttributes('required', 'required');
				$input->setLabel('dtNascimento', 'Data de nascimento:');
				$content .= $input->show();
				
				//input Telefone
				$input->setType('text');
				$input->setAttributes('name', 'telefone');
				$input->setAttributes('id', 'telefone');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('telefone', 'Número para contato:');
				$content .= $input->show();
				
				//input Endereço
				$input->setType('text');
				$input->setAttributes('name', 'endereco');
				$input->setAttributes('id', 'endereco');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('endereco', 'Endereço:');
				$content .= $input->show();
				
				//input Bairro
				$input->setType('text');
				$input->setAttributes('name', 'bairro');
				$input->setAttributes('id', 'bairro');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('bairro', 'Bairro:');
				$content .= $input->show();
				
				//input Ponto de referência
				$input->setType('text');
				$input->setAttributes('name', 'ptReferencia');
				$input->setAttributes('id', 'ptReferencia');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('ptReferencia', 'Ponto de referência:');
				$content .= $input->show() . '<br>';
				
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
				$this->title = 'Novo cliente';
				$this->form = $content;
				break;
		
			case 'consultar':
				$form->setAttribute("action", "?class=ControlCliente&method=consultar&action=search");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setLabel('nome', 'Buscar por nome:');
				$content .= $input->show();
				
				//input Telefone
				$input->setType('text');
				$input->setAttributes('name', 'telefone');
				$input->setAttributes('id', 'telefone');
				$input->setAttributes('size', '300');
				$input->setAttributes('maxlength', '11');
				$input->setLabel('telefone', 'Buscar por telefone:');
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
				$this->title = 'Buscar cliente';
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
		
		$form->setAttribute("action", "?class=ControlCliente&method=editar&action=atualizar");
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
		$this->title = 'Atualizar dados do cliente';
		$this->form = $content;
		
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
		
		echo $div->closeTag();
	}
}