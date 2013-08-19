ZF2 Browscap, v1.0
=======

[![Build Status](https://travis-ci.org/neilime/zf2-browscap.png?branch=master)](https://travis-ci.org/neilime/zf2-browscap)
[![Latest Stable Version](https://poser.pugx.org/neilime/zf2-browscap/v/stable.png)](https://packagist.org/packages/neilime/zf2-browscap)
[![Total Downloads](https://poser.pugx.org/neilime/zf2-browscap/downloads.png)](https://packagist.org/packages/neilime/zf2-browscap)
![Code coverage](https://raw.github.com/zf2-boiler-app/app-test/master/ressources/100%25-code-coverage.png "100% code coverage")

NOTE : If you want to contribute don't hesitate, I'll review any PR.

Introduction
------------

ZF2 Browscap is a Zend Framework 2 module that provides an improved [get_browser](http://www.php.net/manual/en/function.get-browser.php) function. 
This module could be standalone if "browscap" configuration setting in php.ini is undefined or does not point to the correct location of the browscap.ini file. 

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Installation
------------

### Main Setup

#### By cloning project

1. Install [browscap](https://github.com/browscap/browscap) by cloning it into `./vendor/`.
2. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/zf2-browscap": "dev-master"
    }
    ```

2. Now tell composer to download __ZF2 Browscap__ by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'Neilime\Browscap',
        ),
        // ...
    );
    ```
    
# How to use _ZF2 Browscap_

__ZF2 Browscap__ module provides a service, helper for views and plugin for controllers

1. Call Browscap with the service manager

	```php
	
	/* @var $serviceManager \Zend\ServiceManager\ServiceLocatorInterface */	
	
   	$browscap = $serviceManager->get('BrowscapService'); //Retrieve "\Neilime\Browscap\BrowscapService" object
   	var_dump($browscap->getBrowser()); //Display an object which will contain various data elements representing, for instance, the browser's major and minor version numbers and ID string;
   	```

2. Call Browscap in a controller
	"get_browser" plugin expects the same params as the native php function [get_browser](http://www.php.net/manual/en/function.get-browser.php).

 	```php
 	$browscap = $this->get_browser(); //Retrieve an object
   	echo $browscap->parent;
 	
   	$browscap = $this->get_browser(null,true); //Retrieve an array
   	echo $browscap['parent'];
   	
   	$browscap = $this->get_browser(
   		\Zend\Http\Header\UserAgent::fromString('User-Agent: Mozilla/5.0 (Linux; Android 4.0.4; Desire HD Build/IMM76D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19')
   	); //Retrieve an object with an arbitrary user agent
   	echo $browscap->parent;   	
    ```
    
3. Call Browscap in a view
	"get_browser" helper expects the same params as the native php function [get_browser](http://www.php.net/manual/en/function.get-browser.php).

 	```php
   	$browscap = $this->get_browser(); //Retrieve an object
   	echo $browscap->parent;
   	
   	$browscap = $this->get_browser(null,true); //Retrieve an array
   	echo $browscap['parent'];
   	   	
   	$browscap = $this->get_browser(
   		\Zend\Http\Header\UserAgent::fromString('User-Agent: Mozilla/5.0 (Linux; Android 4.0.4; Desire HD Build/IMM76D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19')
   	); //Retrieve an object with an arbitrary user agent
   	echo $browscap->parent;
   	```
# Configuration

The default configuration is setup to use the native php function [get_browser](http://www.php.net/manual/en/function.get-browser.php) if it's available. 
Load browscap.ini from "http://browsers.garykeith.com/stream.asp?BrowsCapINI" and cache it in a file otherwise;

 * boolean `allows_native_get_browser`: Define if the native php function [get_browser](http://www.php.net/manual/en/function.get-browser.php) could be used if it's available.
 * string `browscap_ini_path` : (optionnal) only needed if the native php function [get_browser](http://www.php.net/manual/en/function.get-browser.php) if it's unavailable or if `allows_native_get_browser` option is set to false. Define the borwscap.ini file path (could be an url)
 * Zend\Cache\Storage\Adapter|array `cache` : (optionnal) define the cache adapter. This not only saves you from loading and parsing the "browscap.ini" each time, but also guarantees an optimized loading procedure. 
 
# Tools

_ZF2 Browscap_ provides console tools.

## Features

    Load & parse "browscap.ini" file (or update cached browscap.ini)

## Usage

### Load & parse "browscap.ini" file 

    php public/index.php load-browscap
