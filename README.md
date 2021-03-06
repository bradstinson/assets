Assets
======
[![Build Status](https://travis-ci.org/bradstinson/assets.png?branch=master)](https://travis-ci.org/bradstinson/assets)


Assets is yet another CSS/JavaScript minificaton and combination library. However, unlike most other libraries (which rely on Assetic), this ones takes a more simplistic approach.

Assets supports minifying and combining stylesheets and scripts, in an effort to reduce the number and size of http requests needed to load a given page. The generated file is also cached, which prevents an application from having to generate the same file for every new request.


Basic usage
-----------

### CSS

CSS files can be added using the following command, where "file1.css" and "file2.css" are the CSS files you want to include,
and are located at assets/css/file1.css and assets/css/file2.css.

```php
Assets::css('file1.css');
Assets::css('file2.css');
```

You may also submit files as an array. 

```php
Assets::css(array('file1.css', 'file2.css'));
```

Assets will minify both CSS files and combine them into a single file (which is written to 'assets/cache' directory). The proper tags will then be returned.
To include this file in your page, use the following:

```php
echo Assets::renderCss();
/*
Returns something like:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
*/
```

### LESS

Less files can be added using the following command, where "file1.less" is the Less file you want to compile, and is located at assets/css/file1.less.

```php
Assets::less('file1.less');
```

You may also submit multiple files as an array. 

```php
Assets::css(array('file1.less', 'file2.less'));
```

Your Less files will be automatically compiled and combined with your CSS files into a single file (which is written to 'assets/cache' directory). The proper tags will then be returned.

### JS

Javascript files can be added using the following command, where "file1.js" and "file2.js" are the javascript files you want to include,
and are located at assets/js/file1.js and assets/js/file2.js.

```php
Assets::js('file1.js');
Assets::js('file2.js');
```

You may also submit files as an array. 

```php
Assets::js(array('file1.js', 'file2.js'));
```

Assets will minify both JS files and combine them into a single file (which is written to 'assets/cache' directory). The proper tags will then be returned.
To include this file in your page, use the following:

```php
echo Assets::renderJs();
/*
Returns something like:
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>
*/
```

### CoffeeScript

CoffeeScript files can be added using the following command, where "file1.coffee" is the CoffeeScript file you want to compile, and is located at assets/js/file1.coffee.

```php
Assets::coffee('file1.coffee');
```

You may also submit multiple files as an array. 

```php
Assets::coffee(array('file1.coffee', 'file2.coffee'));
```

Your CoffeeScript files will be automatically compiled and combined with your JS files into a single file (which is written to 'assets/cache' directory). The proper tags will then be returned.

If you would like to generate the CSS and JS tags together, you can call:
```php
echo Assets::render();
/*
Which will return:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>
*/
```


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
Assets::setPath('public/assets/');
```

### BaseURL
```php
Assets::setBaseurl('/');
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
