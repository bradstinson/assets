<?php

class AssetsTest extends PHPUnit_Framework_TestCase {
	
	protected $assets;
	protected $filename;

	/**
	 * Tests whether CSS files can be combined, minified,
	 * and cached successfully. Verfies returned HTML 
	 * tag and checks to see whether cached file exists.
	 * @return void
	 */
	public function testRenderCss()
	{
		$this->setup();

		Assets::add(array('css/test.css', 'css/test2.css'));
		Assets::add('js/test.less');

		// Does returned tag match expected output?
		$this->assertTrue(Assets::styles() == "<link rel=\"stylesheet\" href=\"/assets/cache/". Assets::getCompiledName('css') ."\">");

		// Does file exist?
		$this->filename = __DIR__.'/assets/cache/'. Assets::getCompiledName('css');
		$this->assertTrue(file_exists($this->filename));
		
		$this->tearDown();
	}

	/**
	 * Tests whether JS files can be combined, minified,
	 * and cached successfully. Verfies returned HTML 
	 * tag and checks to see whether cached file exists.
	 * @return void
	 */
	public function testRenderJs()
	{
		Assets::add(array('js/plugins.js', 'js/functions.js'));
		Assets::add('js/test.coffee');

		// Does returned tag match expected output?
		$this->assertTrue(Assets::scripts() == "<script type=\"text/javascript\" src=\"/assets/cache/". Assets::getCompiledName('js') ."\"></script>");

		// Does file exist?
		$this->filename = __DIR__.'/assets/cache/'. Assets::getCompiledName('js');
		$this->assertTrue(file_exists($this->filename));
	}

	/**
	 * Tests whether Less file is compiled correctly.
	 * Verfies returned HTML tag, file contents, and
	 * whether cached file exists.
	 * @return void
	 */
	public function testRenderLess()
	{
		Assets::add('css/test.less');

		// Does returned tag match expected output?
		$this->assertTrue(Assets::styles() == "<link rel=\"stylesheet\" href=\"/assets/cache/". Assets::getCompiledName('css') ."\">");

		// Does file exist?
		$this->filename = __DIR__.'/assets/cache/'. Assets::getCompiledName('css');
		$this->assertTrue(file_exists($this->filename));

		// Do file contents match excpectations?
		$this->assertTrue(file_get_contents($this->filename) == 'body{background-color:red}');
	}

	/**
	 * Tests whether CoffeeScript file has compiled correctly.
	 * Verfies returned HTML tag, file contents, and
	 * whether cached file exists.
	 * @return void
	 */
	public function testRenderCoffee()
	{
		Assets::add('js/test.coffee');

		// Does returned tag match expected output?
		$this->assertTrue(Assets::scripts() == "<script type=\"text/javascript\" src=\"/assets/cache/". Assets::getCompiledName('js') ."\"></script>");

		// Does file exist?
		$this->filename = __DIR__.'/assets/cache/'. Assets::getCompiledName('js');
		$this->assertTrue(file_exists($this->filename));
	}		


	protected function setUp()
	{
		// Set path for processing
		Assets::setPath(__DIR__.'/assets/');
	}


	protected function tearDown()
	{
		// Reset asset collections and destroy assets variable
		Assets::reset();

		// Delete cached file and destroy filename variable
		@ unlink($this->filename);
		unset($this->filename);
	}	
}