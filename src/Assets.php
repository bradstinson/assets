<?php

use Assetic\Asset\Asset;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCollection;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\CssRewriteFilter;
use Assetic\Filter\LessphpFilter;
use Assets\Filter\CoffeeScriptFilter;

class Assets {

    // All assets go in here
    protected static $collections;

    // All assets go in here
    protected static $filters;    

    // Directories
    protected static $assets_dir    = 'assets/';  
    protected static $cache_dir     = 'cache/';
    
    // Paths
    protected static $assets_path;
    protected static $cache_path;

    // URLs
    protected static $base_url = '/assets/';
    protected static $cache_url;   

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
        if(! self::$init){

            // Set paths and urls
            self::setPaths();

            // Set initialization to TRUE
            self::$init = true;

            // Setup inital collections
            self::$filters['css'] = array(new CssMinFilter(), new CssRewriteFilter());
            self::$filters['js'] = array();

            // Setup inital collections
            self::$collections['css'] = new AssetCollection(array(), self::$filters['css']);
            self::$collections['js'] = new AssetCollection(array(), self::$filters['js']);
        }
    }

    /**
     * Set paths and urls for future processing
     */
    public static function setPaths()
    {
        // Set Assets Path
        if(! is_dir(self::$assets_dir)) throw new Assets\AssetsException('The provided directory "' . self::$assets_dir . '" does not exist.');

        // Set cache path and url
        self::$cache_path   = self::$assets_dir.self::$cache_dir;
        self::$cache_url    = self::$base_url.self::$cache_dir;
    }        

    /**
     * Adds a CSS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function css($files)
    {
        self::init();
        return self::add($files, 'css');
    }

    /**
     * Adds a Less file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function less($files)
    {
        self::init();
        return self::add($files, 'css', array(new LessphpFilter));
    }    

    /**
     * Adds a JS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function js($files)
    {
        self::init();
        return self::add($files, 'js');
    }    

    /**
     * Adds a coffeescript file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function coffee($files)
    {
        self::init();
        return self::add($files, 'js', array(new CoffeeScriptFilter));
    }  

    /**
     * Add assets to be rendered
     * @param  string  $files
     * @param  string  $type     
     * @return boolean
     */
    protected static function add($files, $type, $filters=array())
    {
        // If string passed, convert to array
        $files = is_string($files) ? array($files) : $files;

        // Get path
        $path = self::$assets_dir.$type.'/';

        // Load each asset, if file exists
        foreach($files as $file){
            self::$collections[$type]->add(new FileAsset($path.$file, $filters));
        }
    }    

    /**
     * Renders CSS/JS files (returns HTML tags)
     * @return string
     */
    public static function render($type)
    {
        // If $type is null, render both types
        if(! $type){ $type = array('css', 'js'); }

        // If $type is string, convert to array
        $types = is_string($type) ? array($type) : $type;

        $response = array();

        foreach($types as $type){

            $collection = self::$collections[$type];

            $collection->load();

            $compiler = new Assets\Compiler($collection, $type, self::$cache_path);

            $compiler->compile();

            if (file_exists(self::$cache_path.$compiler->getCompiledName($type)))
            {
                $url = self::$cache_url.$compiler->getCompiledName();
                $html = new Assets\Html($type, $url);
                $response[] = $html->render();
            }
        }
        return implode(PHP_EOL, $response);
    } 

    /**
     * Combines, minifies, and renders CSS file (returns HTML tags)
     * @return string
     */
    public static function renderCss()
    {
        return self::render('css');
    }   

    /**
     * Combines, minifies, and renders JS file (returns HTML tags)
     * @return string
     */
    public static function renderJs()
    {
        return self::render('js');
    }

    /**
     * Sets path to the assets directory
     * @param  string  $dir
     * @return boolean
     */
    public static function setPath($path='')
    {
        // Directory Exist?
        if(! is_dir($path)) throw new Assets\AssetsException('The provided directory "' . $path . '" does not exist.');

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

    /**
     * Returns compiled filename
     * @param  string  $base_url
     * @return boolean
     */
    public static function getCompiledName($type)
    {
        $compiler = new Assets\Compiler(self::$collections[$type], $type, self::$cache_path);
        return $compiler->getCompiledName();
    }                   
}