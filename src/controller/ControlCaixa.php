<?php

namespace App\controller;

use App\view\ViewCaixa;

class ControlCaixa
{
	public function novo() {
		$view = new ViewCaixa();
		$view->show();
	}
	
	public function consultar() {
		
	}
}