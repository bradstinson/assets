<?php namespace Assets;

use Assetic\Asset\AssetCollection;

class Compiler {

	/**
	 * Path to store compiled assets.
	 * 
	 * @var string
	 */
	protected $collection;

	/**
	 * Type of files (css or js)
	 * 
	 * @var string
	 */
	protected $type;
	/**
	 * Path to store compiled assets.
	 * 
	 * @var string
	 */
	protected $compilePath;

	/**
	 * Constructor.
	 * 
	 * @param  Assetic\Asset\AssetCollection  $collection	 
	 * @param  string  $type
	 * @param  string  $compilePath
	 * @return void
	 */
	public function __construct(AssetCollection $collection, $type, $compilePath)
	{
		$this->collection = $collection;
		$this->type = $type;				
		$this->compilePath = $compilePath;
	}

	/**
	 * Compile an asset collection.
	 * 
	 * @param  bool  $force
	 * @return void
	 */
	public function compile($force = false)
	{
		// If the compile path does not exist, attempt to create it.
		if ( ! file_exists($this->compilePath))
		{
			mkdir($this->compilePath, 0777, false);
		}

		// Filename for compiled file
		$path = $this->compilePath.$this->getCompiledName();
		
		// If compiled file doesn't exist yet or an asset file has been changed since file was
		// originally compiled, create new file
		if (! file_exists($path) && filemtime($path) < $this->collection->getLastModified() && ! $force)
		{
			file_put_contents($path, $this->collection->dump(), LOCK_EX);
		}
	}

	/**
	 * Get the MD5 fingerprint of the collection.
	 * 
	 * @return string
	 */
	public function getFingerprint()
	{
		$names = array();

		foreach ($this->collection->all() as $asset)
		{
			$names[] = $asset->getLastModified();

			foreach ($asset->getFilters() as $filter => $options)
			{
				$names[] = $filter;
			}
		}

		return md5(implode(PHP_EOL, $names));
	}

	/**
	 * Get the compiled name of the collection.
	 * 
	 * @return string
	 */
	public function getCompiledName()
	{
		return "{$this->getFingerprint()}.{$this->type}";
	}	

}