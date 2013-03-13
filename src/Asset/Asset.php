<?php namespace Assets;

class Asset implements AssetInterface{

	/**
	 * Filename of asset.
	 * 
	 * @var string
	 */
    protected $filename;


    /**
     * Extension of asset.
     * 
     * @var string
     */
    protected $extension;

	/**
	 * Array of filters to apply to the asset.
	 * 
	 * @var array
	 */
    protected $filters = array();

	/**
	 * File contents of asset.
	 * 
	 * @var string
	 */
    protected $content;

	/**
	 * Timestamp of asset.
	 * 
	 * @var int
	 */
    protected $modified = 0;

    /**
     * Array of extension groups.
     * 
     * @var array
     */
    protected $groups = array(
        'js'     => 'script',
        'coffee' => 'script',
        'css'    => 'style',
        'less'   => 'style',
        'sass'   => 'style',
        'scss'   => 'style'
    );

    /**
     * Constructor.
     *
     * @param string $filename 	Filename of asset
     * @param array  $filters   Filters for the asset
     */
    public function __construct($filename='', $filters=array())
    {
        // If file exists, set $filename
        if(file_exists($filename)) $this->filename = $filename;
                
        // Get modified time
        $this->modified = filemtime($this->filename);

        // Get contents of file for later processing
        $this->content = file_get_contents($this->filename);

        // Get contents of file for later processing
        $this->extension = $this->getExtension();        
    }

    /**
     * Returns file contents of asset
     *
     * @return string
     */
    public function getContents(){
    	return $this->contents;
    }

    /**
     * Sets file contents of asset
     *
     * @param  $content  string
     * @return string
     */
    public function setContents($content){
        $this->content = $content;
    } 

    /**
     * Gets extension for file
     *
     * @return string
     */
    public function getExtension(){
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Returns timestamp for file
     *
     * @return string
     */
    public function getLastModified(){
        return $this->modified;
    }

    /**
     * Get the group for the asset.
     * 
     * @return string
     */
    public function getGroup()
    {
        return $this->groups[$this->extension];
    }

    /**
     * Returns filters assigned to the asset.
     * 
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Determines if an asset has valid extension
     * 
     * @return bool
     */
    public function isValid()
    {
        return isset($this->groups[$this->extension]);
    }      

    /**
     * Applies filters to an asset before it is dumped.
     *
     * @return  FilterInterface $asset An asset
     */
    public function compile(){
        return $this->content;
    }
}
