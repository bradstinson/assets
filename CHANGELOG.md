1.1.0-dev (March 20, 2013)
-----------------------

 * Combined css(), less(), js(), and coffee() functions into single add()
 * Renamed renderCss() and renderJs() functions to styles() and scripts() (respectively)
 * Created Filesystem class for common file operations
 * Created Path class for managing path and url variables
 * Fixed inconsistent variable naming throughout library (now all camel case)

1.0.0 (January 23, 2013)
-----------------------

 * Refactored library to utilize Assetic package and filters
 * Refactored code into more modular classes
 * Added support for Less and CoffeeScript files
 * Removed support for HTML5 tag configuration (only HTML5 tags are supported now)

0.0.9 (March 4, 2013)
---------------------

 * Corrected namespace issues with AssetsException class

0.0.8 (February 28, 2013)
-----------------------

 * Provided more bug fixes autoClearCache()


0.0.7 (January 23, 2013)
-----------------------

 * Added html5() function to allow user to specify what type of tags to generate

0.0.6 (January 22, 2013)
---------------------

 * Modified renderAssets() to only create minify objects when needed (performance enhancement)

0.0.4 (January 07, 2013)
-----------------------

 * Provided bug fixes for lastModified() and autoClearCache()

0.0.2 (December 10, 2012)
---------------------

 * Removed dependency to "matthiasmullie/minify" package
 * Added functionality to automatically clear cache
 * Renamed functions to use camel case
 * Added proper namespaces

0.0.1 (December 4, 2012)
---------------------

 * Initial release