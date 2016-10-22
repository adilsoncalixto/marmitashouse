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
 * Produto
 * @author Jorge Lucas
 */
class ViewProduto implements FormBasic
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
		
		switch($config)
		{
			case 'cadastrar':
				$form->setAttribute("action", "?class=ControlProduto&method=cadastrar&action=submit");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('nome', 'Nome:');
				$content .= $input->show();
				
				//input Descrição
				$input->setType('text');
				$input->setAttributes('name', 'descricao');
				$input->setAttributes('id', 'descricao');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('descricao', 'Descrição:');
				$content .= $input->show();
				
				//input Tamanho
				$select = new Select('tamanho');
				$select->setLabel('tamanho', 'Tamanho da marmita:');
				$select->setOptions(['P' => 'Pequena','M' => 'Média','G' => 'Grande']);
				$content .= $select->show();
				
				//input Valor
				$input->setType('text');
				$input->setAttributes('name', 'valor');
				$input->setAttributes('id', 'valor');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('valor', 'Valor:');
				$content .= $input->show();
				
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
				$this->title = 'Novo produto';
				$this->form = $content;
				break;
				
			case 'consultar':
				$form->setAttribute("action", "?class=ControlProduto&method=consultar&action=search");
				$content .= $form->show();
				
				//input Nome
				$input->setType('text');
				$input->setAttributes('name', 'nome');
				$input->setAttributes('id', 'nome');
				$input->setAttributes('size', '300');
				$input->setLabel('nome', 'Buscar por nome:');
				$content .= $input->show();
				
				//input Descrição
				$input->setType('text');
				$input->setAttributes('name', 'descricao');
				$input->setAttributes('id', 'descricao');
				$input->setAttributes('size', '300');
				$input->setLabel('nome', 'Buscar por descrição:');
				$content .= $input->show();
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				$bt = new Button();
				$bt->setContent("Consultar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "entrar");
				$content .= $bt->show();
				
				$bt->setContent('Limpar');
				$bt->setAttributes('type', 'reset');
				$bt->setAttributes('value', 'limpar');
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Consultar produtos';
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
	
		$form->setAttribute("action", "?class=ControlProduto&method=editar&action=atualizar");
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
		$this->title = 'Atualizar dados do produto';
		$this->form = $content;
	
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
	
		echo $div->closeTag();
	}
}