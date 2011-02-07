# flour, a CakePHP Plugin

`flour` is a plugin for CakePHP that enables developers to focus on the real problems of an application.
It is actively developed and used in production by many, since early 2009.

Created by [Dirk Brünsicke][1], `flour` is still under heavy development, but can boost your application development time within [CakePHP][2].

  [1]: http://bruensicke.com/
  [2]: http://cakephp.org/

## Goals

**Coupled Features** Every Feature within `flour` is developed that way, that it can be used out of the box and stand-alone. If you need just one specific feature, it should work without using all of the rest.

Also:

  * allow transition from simple usage to more advanced without refactoring your app
  * allow scheduling of content (see below)
  * allow configurations to customize contents/types/behaviors
  * allow easy usage of simple things like a elements and helpers
  * allow developers to grasp usage from within the plugin itself (code and docs)


## Requirements

You need CakePHP 1.3 or higher and PHP 5.3 or higher.

## Installation

There are 2 ways of installing `flour`. The best is to use it as a git submodule within your own git-repository.

### Git Submodule

Within your CakePHP applications root type the following command to add `flour` as a git submodule.

	cd your_app
	git submodule add git@github.com/bruensicke/flour.git plugins/flour

After that, you should type the following:

	git pull
	git submodule init
	git submodule update


### Download

If you do not use git or want to download a specific release, type the following

	cd your_app
	wget https://github.com/bruensicke/flour/zipball/master

then unzip and put into `APP\plugins`



## Scheduling

Scheduling allows to be the same content object in the database twice or more. It is then scheduled which version gets found based on various conditions. See below for examples.


### Conflicts
When there are scheduling conflicts for multiple editions of the same content, the *start time* is used to determine which plate gets published.  The event latest start time takes priority.  

If start multiple editions have the same start time, the most recently modified edition takes priority


### Example
                    1     2                  3      4
    Timeline:  <----^-----^------------------^------^---->
    
    edition 1       |-------------------------------|
    edition 2             |------------------|

Edition start times:

 * time 1: edition 1
 * time 2: edition 2
 * time 3: back to edition 1
 * time 4: fallback edition


### Example
                    1     2                  3      4
    Timeline:  <----^-----^------------------^------^---->
    
    edition 1       |------------------------|
    edition 2             |-------------------------|

Edition start times:

* time 1: edition 1
* time 2: edition 2
* time 3: edition 2 continues
* time 4: fallback edition


### Example
                    1     2      3      4    5      6
    Timeline:  <----^-----^------^------^----^------^---->
    
    edition 1       |------------|
    edition 2             |------------------|
    edition 3                           |-----------|

Edition start times:

 * time 1: edition 1
 * time 2: edition 2
 * time 3: edition 2 continues
 * time 4: edition 3
 * time 5: edition 3 continues
 * time 6: fallback edition
 
 
### Example
                    1     2      3      4    5      6
    Timeline:  <----^-----^------^------^----^------^---->
    
    edition 1       |-------------------------------|
    edition 2             |-------------|
    edition 3                     |----------|

Edition start times:

 * time 1: edition 1
 * time 2: edition 2
 * time 3: edition 3
 * time 4: edition 3 continues
 * time 5: back to edition 1
 * time 6: fallback edition

