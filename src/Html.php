<?php namespace Assets;

class Html {

	/**
	 * HTML tag group.
	 * 
	 * @var string
	 */
	protected $group;

	/**
	 * Extension of compiled assets.
	 * 
	 * @var string
	 */
	protected $extension;

	/**
	 * URL to compiled asset.
	 * 
	 * @var string
	 */
	protected $url;

	/**
	 * Create a new html instance.
	 * 
	 * @param  string  $type
	 * @param  string  $extension
	 * @param  string  $url
	 * @return void
	 */
	public function __construct($type, $url)
	{
		$this->type = $type;
		$this->url = $url;
	}

	/**
	 * Render the HTML tags.
	 * 
	 * @return string
	 */
	public function render()
	{
		switch ($this->type)
		{
			// Generate the HTML link tag for stylesheets.
			case 'css':
				return '<link rel="stylesheet" href="'.$this->url.'">';
				break;

			// Generate the HTML script tag for scripts.
			case 'js':
				return '<script type="text/javascript" src="'.$this->url.'"></script>';
				break;
		}
	}
}