<?php

class Assets {

    // All assets go in here
    protected static $assets        = array('js' => array(), 'css' => array(), 'less' => array(), 'coffee' => array());

    // Is document HTML5?
    protected static $HTML5         = true;

    // Directories
    protected static $assets_dir    = 'assets/';
    protected static $css_dir       = 'css/';
    // protected static $less_dir      = 'less/';
    protected static $js_dir        = 'js/';    
    // protected static $coffee_dir    = 'coffee/';
    protected static $cache_dir     = 'cache/';
    
    // Paths
    protected static $assets_path;
    protected static $css_path;
    protected static $js_path;
    protected static $cache_path;

    // URLs
    protected static $base_url = '/assets/';
    protected static $css_url;
    protected static $js_url;
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
    }

    /**
     * Set paths and urls for future processing
     */
    public static function setPaths()
    {
        // Set Assets Path
        if(! is_dir(self::$assets_dir)) throw new AssetsException('The provided directory "' . self::$assets_dir . '" does not exist.');

        // Set paths
        self::$css_path     = self::$assets_dir.self::$css_dir;
        // self::$less_path    = self::$assets_dir.self::$less_dir;
        self::$js_path      = self::$assets_dir.self::$js_dir;
        // self::$coffee_path  = self::$assets_dir.self::$coffee_dir;
        self::$cache_path   = self::$assets_dir.self::$cache_dir;

        // Set URLs           
        self::$css_url      = self::$base_url.self::$css_dir;
        self::$js_url       = self::$base_url.self::$js_dir;
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

    // /**
    //  * Adds a Less file to be rendered
    //  * @param  string  $files
    //  * @return boolean
    //  */
    // public static function less($files='')
    // {
    //     self::init();
    //     return self::addAssets($files, 'less');
    // }

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

    // /**
    //  * Adds a CoffeeScript file to be rendered
    //  * @param  string  $files
    //  * @return boolean
    //  */
    // public static function coffeescript($files='')
    // {
    //     self::init();
    //     return self::addAssets($files, 'coffee');
    // }    

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
        // elseif ($type === 'less') $path = self::$assets_dir.self::$less_dir;        
        elseif ($type === 'js') $path = self::$assets_dir.self::$js_dir;
        // elseif ($type === 'coffee') $path = self::$assets_dir.self::$coffee_dir;

        // Load each asset, if file exists
        foreach($files as $file){
            
            // If file exists, add to array for processing
            if(! file_exists($path . $file)) throw new AssetsException('The following file "' . $path . $file . '" could not be found.');
            
            // Add file to list of assets            
            self::$assets[$type][] = $file;
        }

        return TRUE;
    }    

    /**
     * Combines, minifies, and renders CSS file (returns HTML tags)
     * @return string
     */
    public static function renderCss()
    {
        return self::renderAssets('css');
    }   

    /**
     * Combines, minifies, and renders JS file (returns HTML tags)
     * @return string
     */
    public static function renderJs()
    {
        return self::renderAssets('js');
    }

    /**
     * Combines, minifies, and renders asset file (returns HTML tags)
     * @param  string  $type
     * @return string
     */
    protected static function renderAssets($type='')
    {
        if(is_array(self::$assets[$type]) && count(self::$assets[$type])) {

            // Get path
            if ($type === 'css') $path = self::$css_path;
            elseif ($type === 'js') $path = self::$js_path;
            
            // Create cached filename
            $cached_filename = self::generateCacheFilename($type);

            // If cached file doesn't already exist
            if(! file_exists($cached_filename)){

                // Create cache directory if it does not exist
                if (!is_dir(self::$cache_path)) mkdir(self::$cache_path);

                // Find latest modified date from list of assets
                $last_modified = self::lastModified(self::$assets[$type], $type);
            
                // Create Minify Object
                if ($type === 'css') $asset = new Assets\Minify\MinifyCSS();
                elseif ($type === 'js') $asset = new Assets\Minify\MinifyJS();

                // Clear all existing cache files (if set)
                if(self::$auto_clear_cache){self::autoClearCache($type);}

                // Combine, minify assets into new cached file
                foreach(self::$assets[$type] as $file){
                    $asset->add($path.$file);
                }
                $asset->minify(self::$cache_path.$cached_filename);
            }
    
            // Return HTML tags
            return self::generateTag($cached_filename, $type);
        }
    }

    /**
     * Renders CSS/JS files (returns HTML tags)
     * @return string
     */
    public static function render()
    {
        $tag = self::renderCss();
        $tag .= self::renderJs();
        return $tag;
    }

    /**
     * Display an HTML tag
     * @param  string  $file
     * @param  string  $type
     * @return string
     */
    protected static function generateTag($file = null, $type = null, $attributes = '')
    {
        if($type === 'css') {
            if(self::$HTML5) {
                $tag = '<link rel="stylesheet" href="'.self::$cache_url.$file.'"'.$attributes.'>'.PHP_EOL;
            } else {
                $tag = '<link type="text/css" href="'.self::$cache_url.$file.'"'.$attributes.' />'.PHP_EOL;
            }
        } elseif($type === 'js') {
            if (self::$HTML5) {
                $tag = '<script src="'.self::$cache_url.$file.'"'.$attributes.'></script>'.PHP_EOL; 
            } else {
                $tag = '<script src="'.self::$cache_url.$file.'" type="text/javascript"'.$attributes.'></script>'.PHP_EOL;
            }
        }

        return $tag;
    }

    /**
     * Finds the last modified time for an array of files
     * @param  array   $files
     * @param  string  $type
     * @return string
     */
    protected static function lastModified($files = null, $type='')
    {
        $last_modified = 0;

        // Get path
        if ($type === 'css') $path = self::$css_path;
        elseif ($type === 'js') $path = self::$js_path;

        foreach($files as $file){
            $filename = $path.$file;
            $last_modified = (filemtime($filename) > $last_modified) ? filemtime($filename) : $last_modified; 
        }

        return date('YmdHis', $last_modified);
    }

    /**
     * If flagged, auto clear JS/CSS files from cache
     * @param  string  $type     
     * @return boolean
     */
    protected static function autoClearCache($type='')
    {
        // Find list of all files in cache path
        $files = scandir(self::$cache_path);

        foreach($files as $file){

            $file_info = pathinfo(self::$cache_path.$file);

            if ($file_info['extension'] == $type)
            {
                unlink(self::$cache_path.$file);
            }
        }
    }

    /**
     * Generate filename of rendered asset
     * @return string
     */
    public static function generateCacheFilename($type='')
    {
        // Find latest modified date from list of assets
        $last_modified = self::lastModified(self::$assets[$type], $type);
        
        // Create cached filename
        return md5(implode('', self::$assets[$type]).$last_modified).'.'.$type;
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

    /**
     * Sets HTML5 variable
     * @param  string  $html5
     * @return boolean
     */
    public static function html5($html5=TRUE)
    {
        // Set HTML5
        self::$HTML5 = $html5;
    }               
}