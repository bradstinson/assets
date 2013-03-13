<?php namespace Assets;

class CollectionCompiler {

	/**
	 * Path to store compiled assets.
	 * 
	 * @var string
	 */
	protected $compilePath;

	/**
	 * Constructor.
	 * 
	 * @param  string  $compilePath
	 * @return void
	 */
	public function __construct($compilePath)
	{
		$this->compilePath = $compilePath;
	}

	/**
	 * Compile an asset collection.
	 * 
	 * @param  Assets\Collection  $collection
	 * @param  bool  $force
	 * @return void
	 */
	public function compile(Collection $collection, $force = false)
	{
		// If the compile path does not exist, attempt to create it.
		if ( ! file_exists($this->compilePath))
		{
			mkdir($this->compilePath, 0777, false);
		}

		$groups = $collection->getAssets();

		// If no assets found, return FALSE
		if (empty($groups))
		{
			return false;
		}

		// Compile file for each group
		foreach ($groups as $group => $assets)
		{
			// Filename for compiled file
			$path = $this->compilePath.$collection->getCompiledName($group);
			
			// If compiled file doesn't exist yet or an asset file has been changed since file was
			// originally compiled, create new file
			if (file_exists($path) and filemtime($path) >= $collection->lastModified($group))
			{
				// If the force variable has been set then recompile, otherwise this collection does not need
				// to be changed.
				if ( ! $force) { continue; }
			}

			$compiled = $collection->compile($group);

			file_put_contents($path, $compiled, LOCK_EX);
		}
	}

}