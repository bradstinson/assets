<?php

class AssetsTest extends PHPUnit_Framework_TestCase {
	

	public function testRenderCss()
	{
		// Set path and add files for processing
		$assets = new Assets();
		$assets->setPath(__DIR__.'/assets/');
		$assets->css(array('test.css', 'test2.css'));
		$assets->less(array('test.less'));

		// Does returned tag match expected output?
		$this->assertTrue($assets->renderCss() == "<link rel=\"stylesheet\" href=\"/assets/cache/". $assets->getCompiledName('css') ."\">");

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->getCompiledName('css');
		$this->assertTrue(file_exists($filename));
		
		// Delete file and remove assets object
		@ unlink($filename);
		unset($assets);
	}

	public function testRenderJs()
	{
		// Set path and add files for processing
		$assets = new Assets();
		$assets->setPath(__DIR__.'/assets/');
		$assets->js(array('plugins.js', 'functions.js'));

		// Does returned tag match expected output?
		$this->assertTrue($assets->renderJs() == "<script type=\"text/javascript\" src=\"/assets/cache/". $assets->getCompiledName('js') ."\"></script>");

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->getCompiledName('js');
		$this->assertTrue(file_exists($filename));
		
		// Delete file and remove assets object
		@ unlink($filename);
		unset($assets);
	}	
}