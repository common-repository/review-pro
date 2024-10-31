Review Pro

Contributors: Smile Savvy
Donate link: https://www.smilesavvy.com
Tags: review management, reviews, review pro
Requires at least: 3.0
Tested up to: 4.9.6
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin was developed in order to easily install Review Pro within a WordPress environment. The plugin uses a shortcode to activate the required CSS and JavaScript assets, as well as providing the markup that is needed. Requires an active [Review Pro](https://www.smilesavvy.com/services/review-pro/) account. 

== Shortcode Defaults ==

"color" => 'light',
"type" => 'stream',
"id" => '999',
"datamax" => '999',
"datasort" => '',
"multi" => ''

To recap, using [reviewpro id="123"] will load required CSS and JavaScript assets and load the markup:
`<div id="review-stream" class="reviews light"></div>`

If you want to change the type, color, datamax, or datasort, you simply add these attributes to the shortcode, as follows:
	
`[reviewpro id="123" type="post" color="dark" datamax="5" datasort="random"]`

== Multiple Account Usage ==

There are two multi-account options to use with Review Pro. Adding a comma separated list of IDs on the shortcode will automatically initiate support for multi-accounts if "stream" is being used as the type.

If you want to have multiple unique streams for each account, you simply need to add multi="yes" to the shortcode. As long as there are multiple IDs set for the ID attribute, it will load a separate DIV for each unique ID. Example:
	
`[reviewpro id="123,456" multi="yes"]`

Please note that usage of multi="yes" will now load a different script source for the javascript. https://reviewpro.smilesavvy.com/Modules/external_module/embed1.js instead of https://reviewpro.smilesavvy.com/Modules/external_module/embed.js

== Shortcode Shortcut Templates ==

__Load Review Pro stream__
[reviewpro id="123"]

__Load Review Pro post__
[reviewpro id="123" type="post"]

__Load Review Pro stream, with data-max__
[reviewpro id="123" datamax="5"]

__Load Review Pro stream, with data-sort__
[reviewpro id="123" datasort="random"]

__Load Review Pro stream, multiple accounts within same stream__
[reviewpro id="123,456"]

__Load Review Pro stream, separate streams on same page__
[reviewpro id="123,345" multi="yes"] 

== Installation ==

1. Upload the Review Pro plugin via the Plugin management area within WordPress 
2. Activate the plugin through the "Plugins" menu in WordPress
3. Use "[reviewpro id="123"]" in your templates

== Frequently Asked Questions ==

Coming soon

== Changelog ==

= 1.0 =
* Initial base plugin, no current changes

== Upgrade Notice ==

= 1.0 =
No upgrade notes for this initial version