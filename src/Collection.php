<?php namespace Assets;

class Collection {

	/**
	 * Name of collection.
	 * 
	 * @var string
	 */
    protected $name;

    /**
     * Collection directory.
     * 
     * @var string
     */
    protected $directory;

    /**
     * Array of assets in collection.
     * 
     * @var array
     */
    protected $assets = array();

    /**
     * Array of assets waiting to be compiled.
     * 
     * @var array
     */
    protected $pending = array();

    /**
     * Array of filters to apply to entire collection.
     * 
     * @var array
     */
    protected $filters = array();    

    /**
     * Constructor.
     *
     * @param string $name 	Name of collection
     * @param string $directory  Location of directory
     */
    public function __construct($name, $directory)
    {
        // Set collection name
        $this->name = $name;

        // Set collection path
        $this->directory = $directory;
    }

    /**
     * Add asset, if file found
     *
     * @param  string  $filename
     */
    public function add($filename){

        // Raise error if file not found
        if(! file_exists($directory.$filename)) throw new AssetsException('The following file "' . $directory.$filename . '" could not be found.');

        // Create new asset obeject
        $asset = new Asset($filename);

        // If asset not already loaded, and contains valid extension, load
        if (( ! in_array($asset, $this->assets) or ! in_array($asset, $this->pending)) and $asset->isValid())
        {
            return $this->pending[] = $asset;
        }        
    }

    /**
     * Returns timestamp of most recently modified asset
     * 
     * @param  string  $group
     * @return int
     */
    public function lastModified($group)
    {
        $lastModified = 0;

        foreach ($this->assets[$group] as $asset)
        {
            if($asset->getLastModified() > $lastModified)
            {
                $lastModified = $asset->getLastModified();
            }
        }

        return $lastModified;
    }   

    /**
     * Get the valid assets.
     * 
     * @return array
     */
    public function getAssets($group = null)
    {
        // Move all pending assets to proper asset groups
        foreach ($this->pending as $asset)
        {
            $this->assets[$asset->getGroup()][] = $asset;   
        }

        // Once processed, clear assets from pending array
        $this->pending = array();

        // Return assets for specific group. If no group specified, return all.
        if (is_null($group))
        {
            return $this->assets;
        }
        else 
        {
            return isset($this->assets[$group]) ? $this->assets[$group] : array();
        }
    }

    /**
     * Get the MD5 fingerprint of the collection.
     * 
     * @param  string  $group
     * @return string
     */
    public function getFingerprint($group)
    {
        $names = array();

        foreach ($this->getAssets($group) as $asset)
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
     * @param  string  $group
     * @return string
     */
    public function getCompiledName($group)
    {
        $extension = ($group == 'style' ? 'css' : 'js');

        return "{$this->name}-{$this->getFingerprint($group)}.{$extension}";
    }

    /**
     * Return collection name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Compile the group of assets within the collection.
     * 
     * @param  string  $group
     * @return string
     */
    public function compile($group)
    {
        $assets = $this->getAssets($group);

        $return = array();

        foreach ($assets as $asset)
        {
            $return[] = $asset->compile();
        }

        return implode(PHP_EOL, $return);
    }

    /**
     * Apply a filter to  entire collection.
     * 
     * @param  string  $filter
     * @param  array  $options
     * @return Assets\Collection
     */
    public function apply($filter, $options = array())
    {
        foreach ($this->getAssets('style') as $key => $asset)
        {
            $this->assets['style'][$key]->apply($filter, $options);
        }

        foreach ($this->getAssets('script') as $key => $asset)
        {
            $this->assets['script'][$key]->apply($filter, $options);
        }

        return $this;
    }
}
