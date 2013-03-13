<?php

require_once('src/Assets.php');
require_once('src/Asset/AssetInterface.php');
require_once('src/Asset/Asset.php');
require_once('src/AssetsException.php');
require_once('src/Collection.php');
require_once('src/CollectionCompiler.php');
require_once('src/Html.php');



class AssetsTest extends PHPUnit_Framework_TestCase {
	
	public function testRenderCss()
	{
		// Set path and add files for processing
		$assets = new Assets();
		$assets->setPath(__DIR__.'/assets/');
		$assets->css(array('test.css', 'test2.css'));

		// Does returned tag match expected output?
		$this->assertTrue($assets->renderCss() == "<link rel=\"stylesheet\" href=\"/assets/cache/". $assets->getCompiledName('style') ."\">");

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->getCompiledName('style');
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
		$this->assertTrue($assets->renderJs() == "<script type=\"text/javascript\" src=\"/assets/cache/". $assets->getCompiledName('script') ."\"></script>");

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->getCompiledName('script');
		$this->assertTrue(file_exists($filename));
		
		// Delete file and remove assets object
		@ unlink($filename);
		unset($assets);
	}	
}