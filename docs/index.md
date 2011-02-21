# Say Hello to Flour

## What is Flour?

First of all: it is a toolset. It consists of several parts, that can interact, but must not. They rely on some principles that are the same through all parts, but that is by convention.

## What parts are there?

There are the following Parts:

 * [Contents][1]
 * [Configurations][2]
 * Collections
 * Navigations
 * Tags
 * Widgets

[1]: /contents        "Contents"
[2]: /config        "Configurations"


## What else?

On top of that there are some more things, you should get to know like:

 * templates
 * iterator
 * box element
 * MailerComponent
 * FlashComponent

All of these parts are configured using a file, that is app.php, that resides usually under app/config/app.php and is loaded via config('app'); within bootstrap.php
You should take a look at 'flour.php' under config/flour.php to get a hang of, what this usually looks like. There is also another config-file called 'service.php' which works almost the same, but usually controls what keys/settings are needed to connect to various web-services like google-maps, akismet, postmark, whatever.
One thing, that you should understand, is, every object in the database is accessible via a so-called slug. I guess, a slug is, what you call an alias, it is a technical name, that consists only of lowercase letters without spaces, and so on. One thing to note here is, that while in most cases a slug is somehow unique, for flour this is not the case. Instead, one can have unlimited "editions" of the same content with a given slug.

Because every content has a valid start- and end-date, it is possible to get, lets say, a logo for a homepage via a slug: "logo", which is different in december than the rest of the year. This is completely controlled by the system, because you just include a logo via the slug and the system knows, when to deliver which one.

I call this editionable (which is currently in flour_app_model, but should be a behavior, as soon as possible. If you check the readme in flour, you should see some cool ascii-art explaining when what is active.

Now, on to the hard things:

### Contents

First of all, i try to avoid the word content (like used above) because i may mean one row in the contents-table, but i may also mean a thing from another table, which is somehow "content", something, that is for a website, web-application or whatever. Complicated stuff :)

The real "Content" is one row in the contents-table, which is a polymorphic thing. It usually is a ContentObject, which is a generic thing for all data you can think of. Because it has the "flexible" behavior attached to it, it allows to store any amount of data, even unlimited nested, that gets stored in the meta_fields table (and gets json_encoded, in case of nested data). This makes the mysql-db behave almost like a non-sql db.

Every Content has a type, which can be configured (see above). So, every type gets a unique form that usually resides in an element.

Then, there is a ContentLibHelper, that you can use to retrieve Content in Views, and render it with different templates.

### Configurations

I really like Configure::write / read because of its flexibility: You can use it almost everywhere and can even change values in run-time, as you need. That is why i use it for configuring Flour and everything in it. Because i also use it within a lot of applications, i am in the middle of developing an interface, to change some values, based on various conditions. It should allow e.g. to set certain values for a specific user or user-group. Let's say you have a beta-tester group, every member of this group will have different settings. I guess you can imagine the rest.

### Collections

Think if Collections as Key => Value Store. The benefit here is, it is completely database-driven and can be used with the editionable behavior explained above. That way, one can use different collections with the same slug, based on different conditions. It is easy, to add or remove another option, if a certain date/time is arrived.

### Navigations

Should become what you would expect, a Navigation, that can be managed via an interface. The facts about this one is, it consists of different types of NavigationItems, which makes it very modular and flexible. It is not very well developed, but a good start. It may be a good idea, to intersect with some of the other Parts. The NavHelper also allows usage within views, to generate navigations on-the-fly. That way, it is easy possible to change navigations based on run-time conditions. I usually use like i prepare navigations within files (to have them in source-control) and when the project grows, and the client needs access to change navigations, i transfer all navigations into the database, and then the client is able to use the (not implemented) interface, to change the navigations/items.

### Tags

A Tagging-Engine that is based on CakeDC ones. Must be extended, to support so-called custom tags, that have a : in them, and work like delicious, where you can tag something like for_user:d1rk or something else. This is not yet started, but basic tagging is already there, to get contens with a given tag, for example.

### Widgets

A complete, and feature-rich Widget Implementation started here. It is very flexible, but has no UI (except create/edit) yet. It also supports source-code based Widgets, as well as db-driven Widgets, even within a mix. They support templates, with rows of different columns. I have a clear vision, of how it should come out, but it needs a lot of work. The real benefit here is, when i start a new project i develop custom tailored widgets for the application and use them within the views. That way, it is extremely flexible and powerful. I can show you some examples, so you can get a hang out of it. There is a good Helper, that makes it easy to work with.


That's it for now: I actually suggest you to have a look at one example-app. I try to setup a new one (cakepress) which should become a wordpress-clone within cakephp/flour. I struffle with markup at the moment, but i want to keep it up.

But i can give you access to some other projects, where we already utilize these things, because the examples are the best way to get to know it.

The minor things like iterators, item-templates, templates in general and the almost famous box-element are subject of another email, i will prepare these days. I hope, i can use these emails as a starting point to more in-depth documentation, as i know, it lacks docs.

