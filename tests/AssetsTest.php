<?php

require_once('src/Assets.php');
require_once('src/AssetsException.php');

require_once('src/Minify/Minify.php');
require_once('src/Minify/MinifyException.php');
require_once('src/Minify/MinifyCSS.php');
require_once('src/Minify/MinifyJS.php');


class AssetsTest extends PHPUnit_Framework_TestCase {
	
	public function testRenderCss()
	{
		// Set path and add files for processing
		$assets = new Assets();
		$assets->setPath(__DIR__.'/assets/');
		$assets->css(array('test.css', 'test2.css'));

		// Does returned tag match expected output?
		$this->assertTrue($assets->renderCss() == "<link rel=\"stylesheet\" href=\"/assets/cache/". $assets->generateCacheFilename('css') ."\">".PHP_EOL);

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->generateCacheFilename('css');
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
		$this->assertTrue($assets->renderJs() == "<script src=\"/assets/cache/". $assets->generateCacheFilename('js') ."\"></script>".PHP_EOL);

		// Does file exist?
		$filename = __DIR__.'/assets/cache/'. $assets->generateCacheFilename('js');
		$this->assertTrue(file_exists($filename));
		
		// Delete file and remove assets object
		@ unlink($filename);
		unset($assets);
	}	
}