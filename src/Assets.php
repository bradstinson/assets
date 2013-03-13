<?php

class Assets {

    // All assets go in here
    protected static $collection;

    // Is document HTML5?
    protected static $HTML5         = true;

    // Directories
    protected static $assets_dir    = 'assets/';
    protected static $css_dir       = 'css/';
    protected static $js_dir        = 'js/';    
    protected static $cache_dir     = 'cache/';
    
    // Paths
    protected static $assets_path;
    protected static $css_path;
    protected static $js_path;
    protected static $cache_path;

    // URLs
    protected static $base_url = '/assets/';
    protected static $cache_url;   

    // Clear previous cache files
    protected static $auto_clear_cache = true;

    // Has library been initialized?
    protected static $init = false;

    /**
     * Constructor
     */
    public function __construct(){}

    /**
     * Initialize Library (Set paths/directories)
     */
    public static function init()
    {
        // Configure paths, URLs
        if(! self::$init) self::setPaths();

        // Set initialization to TRUE
        self::$init = true;

        // Setup inital collections
        self::$collection = new Assets\Collection('global', 'assets/');
    }

    /**
     * Set paths and urls for future processing
     */
    public static function setPaths()
    {
        // Set Assets Path
        if(! is_dir(self::$assets_dir)) throw new Assets\AssetsException('The provided directory "' . self::$assets_dir . '" does not exist.');

        // Set paths
        self::$css_path     = self::$assets_dir.self::$css_dir;
        self::$js_path      = self::$assets_dir.self::$js_dir;
        self::$cache_path   = self::$assets_dir.self::$cache_dir;

        // Set URLs
        self::$cache_url    = self::$base_url.self::$cache_dir;
    }        

    /**
     * Adds a CSS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function css($files='')
    {
        self::init();
        return self::addAssets($files, 'css');
    }

    /**
     * Adds a JS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function js($files='')
    {
        self::init();
        return self::addAssets($files, 'js');
    }    

    /**
     * Add assets to be rendered
     * @param  string  $files
     * @param  string  $type     
     * @return boolean
     */
    protected static function addAssets($files='', $type='')
    {
        // If string passed, convert to array
        $files = is_string($files) ? array($files) : $files;

        // Get path
        if ($type === 'css') $path = self::$assets_dir.self::$css_dir;
        elseif ($type === 'js') $path = self::$assets_dir.self::$js_dir;

        // Load each asset, if file exists
        foreach($files as $file){
            // If file exists, add to array for processing
            if(! file_exists($path.$file)) throw new Assets\AssetsException('The following file "' . $path . $file . '" could not be found.');
            
            // Add file to list of assets
            self::$collection->add($path.$file);
        }
        return TRUE;
    }    

    /**
     * Combines, minifies, and renders CSS file (returns HTML tags)
     * @return string
     */
    public static function renderCss()
    {
        return self::render();
    }   

    /**
     * Combines, minifies, and renders JS file (returns HTML tags)
     * @return string
     */
    public static function renderJs()
    {
        return self::render();
    }

    /**
     * Renders CSS/JS files (returns HTML tags)
     * @return string
     */
    public static function render()
    {

        $collection = self::$collection;

        $compiler = new Assets\CollectionCompiler(self::$cache_path);

        $compiler->compile($collection);

        $groups = $collection->getAssets();

        foreach ($groups as $group => $assets)
        {
            if (file_exists(self::$cache_path.$collection->getCompiledName($group)))
            {
                $url = self::$cache_url.$collection->getCompiledName($group);
                $html = new Assets\Html($group, $extension, $url);
                echo $html->render();
            }

            // Spin through each of the assets for the particular group and store the raw HTML response.
            $response = array();
        
            foreach ($assets as $asset)
            {
                $response[] = new Assets\Html($asset->getGroup(), $asset->getExtension(), '');
            }

            return implode(PHP_EOL, $response);
        }
    } 

    /**
     * Sets path to the assets directory
     * @param  string  $dir
     * @return boolean
     */
    public static function setPath($path='')
    {
        // Directory Exist?
        if(! is_dir($path)) throw new AssetsException('The provided directory "' . $path . '" does not exist.');

        // Set path
        self::$assets_dir = $path;
    }      

    /**
     * Sets base_url
     * @param  string  $base_url
     * @return boolean
     */
    public static function setBaseurl($base_url='/assets/')
    {
        // Set base_url
        self::$base_url = $base_url;
    }               
}