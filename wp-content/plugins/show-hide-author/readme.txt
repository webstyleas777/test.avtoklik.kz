=== Show Hide Author ===
Contributors: mspyratos
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=EP9SLMTGLMRGY&lc=CA&item_name=WP%20Plugin%20%2d%20Show%20Hide%20Author&item_number=plugin_show_hide_author&currency_code=CAD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: show author, hide author, remove author, author name
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Choose whether to show or hide the author's name.

== Description ==

With this plugin you can choose whether to show or hide the author's name from specific pages and post categories.

= Choose to show or hide the author name =

* In Different Post Types
* In Individual Pages
* In Custom URLs

= Themes Tested =

* Twenty Ten by Wordpress
* Twenty Eleven by Wordpress
* Twenty Twelve by Wordpress
* Theme Revenge on themerevenge.com
* Magnificent by ElegantThemes
* Portfolio Press by Devin Price
* Clearphoto by Solostream

= Problems with the plugin? =

Visit the FAQ or Support page. If you don't find the answer, just email me.
*m.spyratos@hotmail.com*

= In Future Versions =

Choose to show or hide the author name

* In Individual Categories
* By Author Role and Name

Expanding the plugin's functionality, will depend on user's correspondence and the free time I have.
So if you need more from this plugin, don't hesitate to ask for it.

== Installation ==

= Method 1: Online =

Go to your WP plugin installation page and search for "Show Hide Author". Then choose the plugin and install it.

= Method 2: Download the File =

Download the plugin to your computer, then go to your WP plugin installation page and choose to upload the plugin. Choose the file and install it.

= After Installation =

The plugin settings will be available under the "Plugins" main menu.
Click on "Plugins" main menu and then click on "Show Hide Author".
(Check the screenshots)

== Frequently Asked Questions ==

= How to remove the "by" word? =

If you are unfamiliar with using firebug (or similar debug tools), then send me an email ( m.spyratos@hotmail.com ).
Otherwise, follow these simple steps.

**STEPS**  

1. Locate the `by` word you want to remove, by using firebug.  
2. Find the `class` of the parent container that contains the `by` word and its tag.  
3. Go to the plugin settings, near the bottom of the page and fill-in:

Advanced: Hide the "by" word

The parent classes: `class`  
The regular expressions: `by`

**EXAMPLE**  
Let's say that when you locate the `by` word with firebug, you find this html code:

`<div class="meta-text">
	<span class="meta-author">by </span>
</div>`  
  
Then you should enter:  
  
The parent classes: `meta-text`  
The regular expressions: `by`

*BE CAREFULL*  
This will replace all `by` words inside the `meta-text` class (if more than one exists) and it won't remove the `<span>` tags surrounding `by`.  
So even better use something like this:

The parent classes: `meta-text`  
The regular expressions: `<span class="meta-author">by </span>`

*OR* by using regular expressions.

The parent classes: `meta-text`  
The regular expressions: `<span class="meta-author(.*?)/span>`


== Screenshots ==

1. One page settings, under the main menu "Plugins".
2. A post without the Author name in Twenty Eleven.

== Changelog ==

= 2.2 =
* Support for IE8 and below.

= 2.1 =
* Added more themes support.

= 2.0 =
* Fixed a problem when multiple Regular Expressions were used.

= 1.3 =
* More themes support

= 1.2 =
* More themes support

= 1.1 =
* Fixed wrong quotes somewhere in code.

= 1.0 =
* Beta - First Version
