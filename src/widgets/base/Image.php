<?php

namespace App\widgets\base;

/**
 * Configura e exibe a tag HTML img
 * @author Jorge Lucas
 */
class Image
{
	/**
	 * @var string $src Caminho para a imagem
	 * @var string $title Título que a imagem receberá
	 * @var string $basedir Diretório padrão das imagens
	 */
	private $src;
	private $title;
	private $basedir = "src/resources/images/";
	
	/**
	 * Armazena o caminho para a imagem
	 * @param string $src Caminho (dir/dir/imagem.*)
	 * @return void
	 */
	public function setSource(string $src) {
		$this->src = $this->basedir . $src;
	}
	
	/**
	 * Armazena o título que receberá a imagem
	 * @param string $title Título
	 * @return void
	 */
	public function setTitle(string $title) {
		$this->title = $title;
	}
	
	/**
	 * COnfigura e exibe a tag
	 * @return string
	 */
	public function show() {
		$image = <<<IMG
		<img src="{$this->src}" title="{$this->title}">
IMG;
		return $image;
	}
}