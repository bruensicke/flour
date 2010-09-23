flour, a CakePHP Plugin
==========

flour is a plugin for CakePHP that enables developers to focus on the real problems of an application.

It is actively developed and used in production by many, since early 2009.

Created by [Dirk Brünsicke][1], flour is still under heavy development, but can boost your application development time within [CakePHP][2].

  [1]: http://bruensicke.com/
  [2]: http://cakephp.org/


Installation
------------

<pre><code class="shell">$  cd your_app
$  git submodule add http://github.com/bruensicke/flour.git plugins/flour
</code></pre>

After adding it as a submodule, you need to init them within your repository.

<pre><code class="shell">$  cd your_app
$  git pull
$  git submodule init
$  git submodule update
</code></pre>


Design Goals
------------
  * allow transition from simple usage to more advanced without refactoring your app
  * allow scheduling of content (see below)
  * allow configurations to customize contents/types/behaviors
  * allow easy usage of simple things like a elements and helpers
  * allow developers to grasp usage from within the plugin itself (code and docs)


Scheduling
----------

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

