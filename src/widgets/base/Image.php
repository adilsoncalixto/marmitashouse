<?php

namespace App\widgets\base;

class Image
{
	private $src;
	private $title;
	private $basedir = "src/resources/images/";
	
	public function setSource(string $src) {
		$this->src = $this->basedir . $src;
	}
	
	public function setTitle(string $title) {
		$this->title = $title;
	}
	
	public function show() {
		$image = <<<IMG
		<img src="{$this->src}" title="{$this->title}">
IMG;
		return $image;
	}
}