<?php

namespace App\widgets\dialog;

/**
 * Configura e exibe uma mensagem ao usuário
 * @author Jorge Lucas
 */
class Message
{
	/**
	 * @var string $title Título da mensagem
	 * @var string $content Texto secundário da mensagem
	 * @var string $type Tipo da mensagem que será exibida
	 */
	private $title;
	private $content;
	private $type;
	
	/**
	 * Armazena os dados da mensagem
	 * @var string $title Título da mensagem
	 * @var string $content Texto secundário da mensagem
	 * @var string $type Tipo da mensagem que será exibida
	 * @return void
	 */
	public function setContent(string $title, string $text, string $type) {
		$this->content = $text;
		$this->type = $type;
		$this->title = $title;
	}
	
	/**
	 * Configura e exibe a mensagem
	 * @return string Mensagem
	 */
	public function show() {
		$message = <<<MSG
		&nbsp;
		<div class="alert alert-{$this->type} alert-dismissible form" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>{$this->title}</strong> {$this->content}
		</div>
		<br>
MSG;
		return $message;
	}
}