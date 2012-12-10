Assets
======

Assets is yet another CSS/JavaScript minificaton and combination library. However, unlike most other libraries (which rely on Assetic), this takes a much more simplistic approach.

Assets supports minifying and combining stylesheets and scripts, in order to reduce the number and size of http requests need to load a given page. Also, the generated file is also cached, which will prevent an application from having to generate the same file with every new request.


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

By default, Assets will minify both of these files and combine them into a single file (which is written to 'assets/cache' directory).
To include this file in your page, use the following:

```php
echo Assets::renderCss();
/*
Returns something like:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
*/
```

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

By default, Assets will minify both of these files and combine them into a single file (which is written to 'assets/cache' directory).
To include this file in your page, use the following:

```php
echo Assets::renderJs();
/*
Returns something like:
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>
*/
```
If you like to generate the CSS and JS tags together, you can call:
```php
echo Assets::render();
/*
Which will return:
<link rel="stylesheet" href="/assets/cache/3cf89b9f723e22c1caf26f8d4d1fdc31.css">
<script src="/assets/cache/9cf2803d8f075cb7d1ad31940738f35e.js"></script>
*/
```

Groups
------

This library currently does not offer support for groups. This is because the project I was working on did not require it. If enough
requests are made, this may be added in a future version.


Directory Structure
---------------------

```
assets/
   css/
   js/
   cache/
   
```

Thanks
------

This asset library was inspired and uses components from the following libraries:

 - [Assets](https://github.com/bstrahija/assets)
 - [Minify](https://github.com/matthiasmullie/minify)
 - [fuelphp-casset](https://github.com/canton7/fuelphp-casset)


Contributing
------------

If you've got any issues/complaints/suggestions, please tell me and I'll do my best!