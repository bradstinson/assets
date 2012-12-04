<?php

class Assets_Exception extends Exception {}

class Assets {

	// All assets go in here
	public static $assets 	= array('js' => array(), 'css' => array());

	// Is document HTML5?
    public static $html5    = true;

    // Paths/URL
    public static $paths 	= array(
							   	'css' => 'assets/css/',
							   	'js' => 'assets/js/',
							  	'cache' => 'assets/cache/'
							 );

    // URL to prepend to all generated tags
    public static $baseurl 	= '/';

    // Clear previous cache files
    public static $auto_clear_cache = true;


    /**
     * Adds a CSS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function css($files='')
    {
    	return self::_add_assets($files, 'css');
    }


    /**
     * Adds a JS file to be rendered
     * @param  string  $files
     * @return boolean
     */
    public static function js($files='')
    {
    	return self::_add_assets($files, 'js');
    }


    /**
     * Add assets to be rendered
     * @param  string  $files
     * @param  string  $type     
     * @return boolean
     */
    protected static function _add_assets($files='', $type='')
    {
        // If string passed, convert to array
        $files = is_string($files) ? array($files) : $files;

        foreach($files as $file){
            // If file exists, add to array for processing
            if(file_exists(self::$paths[$type].$file)) {
                self::$assets[$type][] = $file;
            }
        }

        return TRUE;
    }    


    /**
     * Combines, minifies, and renders CSS file (returns HTML tags)
     * @return string
     */
    public static function render_css()
    {
    	return self::_render_assets('css');
    }   


    /**
     * Combines, minifies, and renders JS file (returns HTML tags)
     * @return string
     */
    public static function render_js()
    {
    	return self::_render_assets('js');
    }


    /**
     * Combines, minifies, and renders asset file (returns HTML tags)
     * @param  string  $type
     * @return string
     */
    protected static function _render_assets($type='')
    {
        if(is_array(self::$assets[$type]) && count(self::$assets[$type])) {

	        if($type === 'css'){
	            $asset =  new MinifyCSS();
	        }elseif($type === 'js'){
	            $asset =  new MinifyJS();
	        }
            
            $last_modified = self::_last_modified(self::$assets[$type], $type);
            
            $cached_filename = self::$paths['cache'].md5(implode('', self::$assets[$type]).$last_modified).'.'.$type;

            //Helpers::debug($cached_filename);

            if(! file_exists($cached_filename)){

                if(self::$auto_clear_cache){self::_auto_clear_cache($type);}

            	foreach(self::$assets[$type] as $file){
	                $asset->add(self::$paths[$type].$file);
	            }

                $asset->minify($cached_filename);
            }
    
            return self::_generate_tag($cached_filename, $type);
        }
    }


    /**
     * Renders CSS/JS files (returns HTML tags)
     * @return string
     */
    public static function render()
    {
        $tag = self::render_css();
        $tag .= self::render_js();
        return $tag;
    }


    /**
     * Display an HTML tag
     * @param  string  $file
     * @param  string  $type
     * @return string
     */
    protected static function _generate_tag($file = null, $type = null, $attributes = '')
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
    protected static function _last_modified($files = null, $type='')
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
    protected static function _auto_clear_cache($type='')
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
}