<?php

class Assets {

    // All assets go in here
    public static $assets   = array('js' => array(), 'css' => array());

    // Is document HTML5?
    public static $html5    = true;

    // Paths/URL
    public static $paths    = array(
                                'css' => 'assets/css/',
                                'js' => 'assets/js/',
                                'cache' => 'assets/cache/'
                             );

    // URL to prepend to all generated tags
    public static $baseurl  = '/';

    // Clear previous cache files
    public static $auto_clear_cache = true;


    /**
     * Constructor
     */
    public function __construct($paths= array(), $baseurl='/')
    {

    }


    /**
     * Adds a CSS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function css($files='')
    {
        return self::addAssets($files, 'css');
    }


    /**
     * Adds a JS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function js($files='')
    {
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

        foreach($files as $file){
            
            // If file exists, add to array for processing
            if(! file_exists(self::$paths[$type].$file)) throw new AssetsException('The following file "' . self::$paths[$type].$file . '" could not be found.');
            
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

            if($type === 'css'){
                $asset =  new Assets\Minify\MinifyCSS();
            }elseif($type === 'js'){
                $asset =  new Assets\Minify\MinifyJS();
            }
            
            $last_modified = self::lastModified(self::$assets[$type], $type);
            
            $cached_filename = self::$paths['cache'].md5(implode('', self::$assets[$type]).$last_modified).'.'.$type;

            if(! file_exists($cached_filename)){

                if(self::$auto_clear_cache){self::autoClearCache($type);}

                foreach(self::$assets[$type] as $file){
                    $asset->add(self::$paths[$type].$file);
                }

                $asset->minify($cached_filename);
            }
    
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
        if($type === 'css'){
            if(self::$html5){
                $tag = '<link rel="stylesheet" href="'.self::$baseurl.$file.'"'.$attributes.'>'.PHP_EOL;
            } else {
                $tag = '<link type="text/css" href="'.self::$baseurl.$file.'"'.$attributes.' />'.PHP_EOL;
            }
        }elseif($type === 'js'){
            if (self::$html5){
                $tag = '<script src="'.self::$baseurl.$file.'"'.$attributes.'></script>'.PHP_EOL; 
            }else{
                $tag = '<script src="'.self::$baseurl.$file.'" type="text/javascript"'.$attributes.'></script>'.PHP_EOL;
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

        foreach($files as $file){
            $filename = self::$paths[$type].$file;
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
        $files = scandir(self::$paths['cache']);

        foreach($files as $file){
            $file_info = pathinfo(self::$paths['cache'].$file);

            if ($type === 'css')
            {
                if (isset($file_info['extension']) and strtolower($file_info['extension']) === 'css') unlink(self::$paths['cache'].$file);
            }
            elseif ($type === 'js')
            {
                if (isset($file_info['extension']) and strtolower($file_info['extension']) === 'js') unlink(self::$paths['cache'].$file);
            }
        }

        return $last_modified;
    }


    /**
     * Sets path variables
     * @param  string  $type
     * @param  string  $path     
     * @return boolean
     */
    public static function setPath($type='', $path='')
    {
        foreach(array('css', 'js', 'cache') as $path_type)
        {
            if($type == $path_type)
            {
                // Directory Exist?
                if(! is_dir($path)) throw new AssetsException('The provided path "' . $path . '" does not exist.');

                // Set path
                self::$paths[$type] = $path;
            }
        }
    } 


    /**
     * Sets baseurl
     * @param  string  $baseurl
     * @return boolean
     */
    public static function setBaseurl($baseurl='/')
    {
        // Set baseurl
        self::$baseurl = $baseurl;
    }           
}