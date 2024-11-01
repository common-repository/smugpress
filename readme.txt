=== Plugin Name ===
Contributors: wfrench
Donate link: http://www.grepsedia.com
Tags: sidebar, widget, smugmug, photos, pictures, TinyMCE
Requires at least: 2.1
Tested up to: 2.3
Stable tag: 0.2.5

A plugin that helps you incorprate SmugMug pictures into your posts and in the sidebar.

== Description ==

Smugpress is a Free and Open Source Wordpress plugin which facilitates SmugMug photo integration into your posts, pages, and sidebar. This is my first Wordpress plugin so as a pedagogic exersize I included as many Wordpress features into the plugin that I could deem useful. Feel free to offer critic, criticisms, or questions.

Features:

* Random photo sidebar widget
* DB Cached Feed data including gallery and picture information.
* Easily insert SmugMug into your posts and pages.
* WYSIWYG post plugin.

== Installation ==

1. Download the smugpress package and uncompress it into your wp-content/plugins directory.
1. Activation the plugin.
1. Choose Options->Smugpress from the main menu and enter your Smugmug nickname.

== Frequently Asked Questions ==

= Why did you write this plugin? =

1. I am new to wordpress and wanted to learn how it worked. What better way then to rip the off the cover and start playing with the insides?? Unlike my dad’s CB radio I was able to put Wordpress back together.
1. My wife and I have a new son and I wanted a blog to brag/boast/show off. However, I didn’t want to manage the content myself. My wife is pretty technical, but using a plugin helped keep things consistent between our posts.
1. I've been writing code for well over 10 years and figured it was finally time to contribute to the open source community.

= Why are feeds cached, doesn’t Wordpress already cache RSS feed requests? =

Yes, but the feed request is not the only point for added latency. Once the feed is downloaded you still have to parse all of the feed requested. When dealing with several large galleries the latency was significant. By only parsing the feeds once we can select a random image with a single DB query, super fast!

== Screenshots ==

1. Insert an image using the post plugin into a message.
2. Smugpress administrative options page.
3. Post after the image insert dialog is closed.  It looks just like an image. :)
4. Widget configuration when using the RSS Gallery view.
5. Widget configuration when using the RSS Feed view.
6. Image of the Smugpress sidebar widget in action.
7. What the quicktag actually looks like in code.

== More Documentation ==

For the most up to date documention go to (http://www.grepsedia.com/development/smugpress). I'll try to keep this document
updated, but it's hard maintaining two sets of documentation.

== Feedback ==

This is my first Wordpress plugin.  I'm sure there are several areas where my plugin could be imporved or use more standard methods.  I would love the feedback.  Heck if I don't get 
feedback I just assume it's perfect!  You can contact me through (http://www.grepsedia.com/contact my website).

== Beta ==

This version of the plugin has been tested by three people, one of which is me.  I would consider this beta code until we get a few more users.  If you do find bugs please let me know, I want to get them fixed.  Also, if there is a feature you would like to see let me know.  If it makes sense and I have time I'll add it to the next version.

