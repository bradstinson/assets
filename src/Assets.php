<?php

use Assets\Filesystem;
use Assets\Filter\Filter;
use Assets\Path;
use Assetic\Asset\Asset;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCollection;

class Assets {

    // All assets go in here
    protected static $collections;

    // All filters go in here
    protected static $filters = array(
                    'style' => array(),
                    'script' => array()
    );    

    // Accepted file extensions and their corresponding collection
    protected static $extensions = array(
                    'css' => 'style',
                    'less' => 'style',
                    'js' => 'script',
                    'coffee' => 'script'
    );
    
    // Paths and URL storage variable
    protected static $assetPath;
    protected static $paths;

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
        // If not already done, initialize
        if(! self::$init){
            
            // Set paths
            self::$paths = new Path(self::$assetPath);

            // Set initialization to TRUE
            self::$init = true;

            // Setup inital filters for collections
            // self::$filters['style'] = array();
            self::$filters['style'] = array(Filter::add('CssMinFilter'), Filter::add('CssRewriteFilter'));
            self::$filters['script'] = array();

            // Re-setup inital collections
            self::$collections['style'] = new AssetCollection(array(), self::$filters['style']);
            self::$collections['script'] = new AssetCollection(array(), self::$filters['script']);
        }
    }         

    /**
     * Add assets to be rendered
     * @param  string  $files
     * @param  string  $type     
     * @return boolean
     */
    public static function add($files)
    {
        // If needed, initalize
        self::init();

        // If string passed, convert to array
        $files = is_string($files) ? array($files) : $files;
    
        $path = self::$paths->get('assets');

        // Load each asset, if file exists
        foreach($files as $file){

            $filepath = $path.$file;
            $extension = Filesystem::extension($filepath);

            // If file is Less or Coffeescript, apply required filter
            if ($extension == 'less') $filters = array(Filter::add('LessphpFilter'));
            elseif ($extension == 'coffee') $filters = array(Filter::add('CoffeeScriptFilter'));
            else $filters = array();

            self::$collections[self::$extensions[$extension]]->add(new FileAsset($filepath, $filters));
        }
    }    

    /**
     * Compiles CSS/JS files (returns HTML tags)
     * @return string
     */
    public static function render($type='')
    {
        // If $type is null, render both types
        if(! $type){ $type = array('style', 'script'); }

        // If $type is string, convert to array
        $types = is_string($type) ? array($type) : $type;

        $response = array();

        foreach($types as $type){

            $collection = self::$collections[$type];

            $collection->load();

            $compiler = new Assets\Compiler($collection, $type, self::$paths->get('cache'));

            $compiler->compile();

            if (Filesystem::exists(self::$paths->get('cache').$compiler->getCompiledName($type)))
            {
                $url = self::$paths->get('cacheUrl').$compiler->getCompiledName();
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
    public static function styles()
    {
        return self::render('style');
    }   

    /**
     * Combines, minifies, and renders JS file (returns HTML tags)
     * @return string
     */
    public static function scripts()
    {
        return self::render('script');
    }

    /**
     * Sets path to the assets directory
     * @param  string  $dir
     * @return boolean
     */
    public static function setPath($path='')
    {
        // Set path
        self::$assetPath = $path;

        // Directory Exist?
        if(! Filesystem::isDirectory(self::$assetPath)) throw new Assets\AssetsException('The provided directory "' . self::$assetPath . '" does not exist.');
    }      

    /**
     * Sets baseUrl
     * @param  string  $baseUrl
     * @return boolean
     */
    public static function setBaseUrl($baseUrl='')
    {
        // Set baseUrl
        self::$paths->set('baseUrl', $baseUrl);

        // Set baseUrl
        self::$paths->set('cacheUrl', self::$paths->get('baseUrl').self::$paths->get('cache'));
    }

    /**
     * Returns compiled filename
     * @param  string  $type
     * @return boolean
     */
    public static function getCompiledName($type)
    {
        $compiler = new Assets\Compiler(self::$collections[self::$extensions[$type]], $type, self::$paths->get('cachePath'));
        return $compiler->getCompiledName();
    }

    /**
     * Reinitializes Assets object (removes all files from collections)
     * @return boolean
     */
    public static function reset()
    {
        self::$init = false;
        self::init();
    } 
}