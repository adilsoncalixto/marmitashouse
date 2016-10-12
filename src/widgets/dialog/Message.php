<?php

namespace App\widgets\dialog;

class Message
{
	private $title;
	private $content;
	private $type;
	
	public function setContent(string $title, string $text, string $type) {
		$this->content = $text;
		$this->type = $type;
		$this->title = $title;
	}
	
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