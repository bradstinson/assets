<?php namespace Assets;


class Path{

	/**
	 * Array of paths
	 * @var array
	 */
	protected $paths;

	/**
     * Constructor
     */
	public function __construct($assetsPath = 'assets/')
	{
		$this->paths = array();

		$this->set('assets', $assetsPath);


        // Throw error, if asset directory does not exist
        if(! Filesystem::isDirectory($this->get('assets'))) throw new AssetsException('The provided directory "' . $this->get('assets') . '" does not exist.');

        $this->set('baseUrl', '/'.$this->get('assets'));

		$this->set('cache', $this->get('assets').'cache/');

		$this->set('cacheUrl', $this->get('baseUrl').'cache/');
	}

	/**
	 * Returns key variable
	 *
	 * @param  $key
	 * @param  mixed   $default
	 **/
	public function get($key = null, $default = null)
	{
		// If path not found, return null
		if(! is_null($key))
		{
			return $this->paths[$key];
		}

		return $default;
	}

	/**
	 * Sets path variable
	 *
	 * @param  $key
	 * @param  $value
	 * @author 
	 **/
	public function set($key, $value)
	{
		// If no key passed, return false
		if ( ! is_null($key))
		{
			$this->paths[$key] = $value;	
		}

		return false;
	}

	/**
	 * Determines if a path has been set and is not null.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function has($key)
	{
		return ! is_null($this->get($key));
	}	
}