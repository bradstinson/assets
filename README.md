Assets
======
[![Build Status](https://travis-ci.org/bradstinson/assets.png?branch=master)](https://travis-ci.org/bradstinson/assets)


Assets is yet another CSS/JavaScript minificaton and combination library. However, unlike most other libraries, this ones takes a more simplistic approach.

Assets supports minifying and combining stylesheets and scripts, in an effort to reduce the number and size of http requests needed to load a given page. The generated file is also cached, which prevents an application from having to generate the same file for every new request.


Basic usage
-----------

By default, this library supports CSS, Less, Javascript, and CoffeeScript files. The add method will use the file extension of each asset to determine the type of file we are registering:

```php
Assets::add('file1.css');
```

```php
Assets::add('file1.less');
```

```php
Assets::add('file1.js');
```

```php
Assets::add('file1.coffee');
```

In addition to passing a single file as a string, the add method also allows you to submit files as an array:

```php
Assets::add(array('file1.css', 'file2.css'));
```

### Dumping Assets

Assets will compile and combine your files and write them to the 'assets/cache' directory. When you are ready to place the links to the registered assets on your view, you may use the styles or scripts methods:


```php
echo Assets::styles();
/*
Returns something like:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
*/
```

```php
echo Assets::scripts();
/*
Returns something like:
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>

*/
```

You may also return both scripts and style tags by using the render method:
```php
echo Assets::render();
/*
Returns something like:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>

*/

Configuration
------

By default, this library assumes the following directory structure:

```
assets/
   css/
   js/
   cache/
   
```

However, you are now able to adjust the path to the assets directory (if needed): 

```php
Assets::setPath('public/assets/'));
```

### BaseURL
```php
Assets::setBaseurl('/'));
```

Groups
------

This library currently does not offer support for groups. This is because the project I was working on did not require it. If enough
requests are made, this may be added in a future version.

Thanks
------

This asset library was inspired by and uses components from the following libraries:

 - [Assets](https://github.com/bstrahija/assets)
 - [Assetic](https://github.com/kriswallsmith/assetic)
 - [Basset](https://github.com/jasonlewis/basset)
 - [CssMin](https://github.com/natxet/CssMin) 
 - [LessPHP](https://github.com/leafo/lessphp)
 - [CoffeeScript PHP](https://github.com/alxlit/coffeescript-php)


Contributing
------------

If you have any issues/complaints/suggestions, let me know and I will see if I can implement them as time permits. Also, pull requests are also welcome.
