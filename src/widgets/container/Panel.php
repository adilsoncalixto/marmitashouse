<?php

namespace App\widgets\container;

class Panel
{
	private $header;
	private $content;
	
	public function __construct(string $text) {
		$this->header = $text;	
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	
	public function show() {
		$panel = <<<PANEL
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<h3 class='panel-title'> $this->header </h3>
			</div>
			<div class='panel-body'>
				$this->content
			</div>
		</div>
PANEL;
		
		echo $panel;
	}
	
}