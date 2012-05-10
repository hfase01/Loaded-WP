=== The Welcomizer ===
Contributors: sebwordpress
Donate link: http://www.sebastien-laframboise.com/the-welcomizer-souvenir-shop/
Tags: jquery, move, movement, simple, le welcomizeur, animation, animate, welcome, div, opacity, effects, Homepage, plugin, javascript, ajax, code, style, formatting, advertising, ads, promotions, easy, montreal, admin, wordpress, transparency, posts, Post, sidebar, images, image, page, pages, categories, category, android, blackberry, cellular, device, iphone, mobile, ipad, blog, enqueue, css, js, event, onclick, ondblclick, onmouseover, onmouseenter, onmouseout, onmousedown, onfocus, rotate, rotation, free, scale, like, bird, word, class, id, name, attribute
Requires at least: 3.1
Tested up to: 3.3.2
Stable tag: 1.4.4.1
License: GPLv2

This plugin allows you to quickly animate your WordPress blog using jQuery effects. (100% AJAX) + .js/.css Includer.

== Description ==

Quickly add animations to your WordPress blog. 
    
Designed for webmasters, marketing consultants, web designers and bloggers.

[Demo page](http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/) <- Learn by Example & Useful Tips

Stay tuned, and do your updates...

The Welcomizer has Spirit!

∞

== Installation ==

1. Create and make this directory writable: 'wp-content/twiz/'
2. Upload `/the-welcomizer` directory and files to the `/wp-content/plugins/` directory.
3. Activate the plugin through the Plugins menu in WordPress.
4. Find the plugin under the menu Appearance,
5. Configure and save your first movement, or Import a sample twz/xml file.


Useful jQuery plugins to upload through the Library:
- 
- jQuery UI/Effects - [Download page](http://jqueryui.com/download) 
- Rotate3Di - [Download page](https://github.com/zachstronaut/rotate3Di) 
- jquery-animate-css-rotate-scale - [Download page](https://github.com/zachstronaut/jquery-animate-css-rotate-scale)
- transform  - [Download page](https://github.com/heygrady/transform/)

== Screenshots ==

1. Panel when adding a new movement. 
2. Panel when viewing data. (Right click - View image) 

== Changelog ==

= 1.4.4.1 = 

* Fixed a string replacement within Twiz functions.

= 1.4.4 = 

* Added [Short code] to the output choices.
* Fixed offline status of sections with custom logic.
* Minor fixes.

= 1.4.3 =

* Added the ability to sort the list.
* Added links to twiz functions in the right panel.
* Added an editable `Event` column.
* Added random ads in the footer.
* Modified the display of `More configurations`.
* Modified the closing of the vertical menu.
* Solved compatibility with Opera.
* Sorted items in the vertical menu.
* Sorted the `Event` column.
* Various bug fixes, adjusments and code review.

= 1.4.2.1 = 

* Removed auto-draft items from lists.

= 1.4.2 = 

* Added unpublished pages & articles to lists.

= 1.4.1 = 

* Added preloading of 2 loading images.

= 1.4 = 

* Optimized the behavior of all loading images.
* Updating the names of functions twiz etc…when the name is changed.
* Added current animation to functions list.

= 1.3.9.9 = 

* Fixed event bug.
* Modified the skin preloads.

= 1.3.9.8 = 

* Added the `e` parameter for events.
* Added the ability to unbind and bind events.
* Added Admin option `Starting position by default`.
* Changed output type by default to `Multiple`.
* Modified the data type and validation of `Start delay` and `Duration` to accept variables.
* Preloads the default skin.
* Minor fixes.
* Improved the UI speed.
* Fixed later: The event bug.

= 1.3.9.7 = 

* Turned some images into CSS background.
* Re-established CSS compatibility with WP v3.1 
* Minor fixes.

= 1.3.9.6 = 

* Modified the effect after saving.(finally)
* Modified stylesheets.
* Created ajax gif for skins.
* Happy holidays to All.
* Updated later: Italian translation.

= 1.3.9.5 = 

* Fixed activation button.
* Added hooks for scripts: front-end, and admin panel.
* Added some skins, click the logo.
* Fixed stylesheet.
* Fixed later: js skin var.
* Updated later: skins css.

= 1.3.9.4 = 

* Added the ability to create custom menu.
* Added support for multiple $(document).twizReplay(); on the same page from different sections.
* Added Admin option `Maximum number of posts in lists`.
* Multiple layout adjustements.
* Fixed later: Menu translation.
* Fixed undefined ABSPATH in twiz-ajax.php

= 1.3.9.3 = 

* Added a new OPTIONAL parameter to $(document).twizRepeat(X);  X = number of times to repeat, BLANK equal infinite repetition.
* Added current object to `Repeat` & `Replay` functions.
* Replaced `this` inside the 2 JavaScript textbox.
* Fixed later: Fixed repeat parameter.
* Updated later: Updated Italian translation.

= 1.3.9.2 = 

* Fixed `Home` activation button display.
* Fixed plugin update.

= 1.3.9.1 = 

* Added Admin option `Minimum Role to access this plugin`.
* Fixed some display activation(Everywhere).
* Fixed `Home` translation.
 
= 1.3.9 =

* Added positions `fixed` and `static`.
* Added Admin option `Delete all when disabling the plugin`. (Now inactive by default)
* Fixed the Library Order feature. 
* Adjusted the layout.

= 1.3.8.9 =

* Added a vertical alignment to the right panel.
* Excluded auto-repeated animations from the Replay.
* Updated Italian translation

= 1.3.8.8 =

* Layout fixes.

= 1.3.8.7 =

* Bug fixes.
* Restructured sections.

= 1.3.8.6 =

* Added a vertical menu.
* Removed radio buttons from the header.
* Added status image on each sections.
* Added Ajax refresh to the section scroll list, no need to refresh the page anymore.
* Remove the default `***` when saving an empty element.
* Added a title section for the Library and the Admin section.
* Merged the date with the title of a post.
* Fixed bugs.

= 1.3.8.5 =

* Added `swing` and `linear` option above each moves. (swing by default)
* Added links `Edit - Copy - Delete` under each element.
* Added option `Register jQuery default library`. (active by default)
* Modified the right panel view.
* Fixed layout stylesheet.

= 1.3.8.4 =

* Fixed `The plugin generated 2 characters of unexpected output.`
* Modified global status. (Active by default)

= 1.3.8.3 =

* Added `Output code compression` option. (Inactive by default)

= 1.3.8.2 =

* Fixed time of second move when empty.
* Rewritten the output code.
* Minor fixes.

= 1.3.8.1 =

* Updated Italian translation.
* Fixed button translation.
* Fixed directional images.

= 1.3.8 =

* Added Output code hook choices - Admin section. (default wp-head)
* Added `empty` for Top and Left under the First and Second move.
* Many minor fixes.

= 1.3.7.9 =

* Added `px, %, em, in` as choices for Top and Left.
* Layout fixes.

= 1.3.7.8 =

* Reordered the animations list by ID.
* Fixed Twiz functions calls.
* Minor adjustements.

= 1.3.7.7 =

* Fixed JavaScript OnReady.
* Fixed some output bugs.
* Fixed status bug.
* Adjusted Labels.

= 1.3.7.6 =

* Added New export id for functions and variables.
* Added Hotfix to replace current ids.
* Role is set to `manage_options`.

= 1.3.7.5 =

* Adjusted Right panel inside two conditional blocks.
* Renamed functions to twiz_*
* Fixed Animations list.
* Layout fixes.
* Minor fixes.

= 1.3.7.4 =

* Added the ability to easily call manually triggered animations and others into the JavaScript.
* The id of each row is displayed on status image.
* Simplified the right panel view.
* Layout adjustements.

= 1.3.7.3 =

* Added the ability to choose a post when creating menus.

= 1.3.7.2 =

* Fixed conflict with upload media manager in post page.

= 1.3.7.1 =

* Fixed wp-minify incompatibility(new).

= 1.3.7 =

* Added the ability to output starting positions OnReady, before or after the delay. 
* Added the ability to output the JavaScript also OnReady.
* Export files are created and moved to /wp-content/twiz/export/
* All scripts are enqueued only on plugin page.
* Layout adjustements.
* Minor fixes.

= 1.3.6.9 =

* Optimized output code.

= 1.3.6.8 =

* Correction, small configs are always output before the delay. 
* The JavaScript has an option before or after the delay.

= 1.3.6.7 =

* Output small configs before the JavaScript.
* Small layout adustement.

= 1.3.6.6 =

* Simplified the editing panel.
* Added the ability to output config. before the delay, or after the delay.
* All previous starting configurations are switched before the delay by default.

= 1.3.6.5 =

* Fixed navigation import menu, and default action label.

= 1.3.6.4 =

* Added support for $(this). inside the textbox `JavaScript Before`.

= 1.3.6.3 =

* All menu buttons are now visible when editing.

= 1.3.6.2 =

* Added the layout of the admin button.
* Layout adjustements.
* Minor fixes.

= 1.3.6.1 =

* Added the ability to animate elements also with attribute "name" or "class".
* Pick an ID from list has been removed, get Firebug.
* Adjusted textarea resize behavior.
* Added New field to the database. 
* Layout adjustements.
* Minor fixes.

= 1.3.6 =

* Updated Italian translation
* Fixed Everywhere, Everywhere.

= 1.3.5.9 =

* Added the ability to add animations everywhere, all articles, all categories and all pages.
* Added another JavaScript textbox to the editing panel.
* Added a Z-Index textbox to the editing panel.
* Added the ability to export only one animation.
* Added New fields to the database. 
* Layout adjustements.
* Minor fixes.

= 1.3.5.8 =

* Restricted the `buildups` of animations.
* New fields are now `simply` added to the database.

= 1.3.5.7 =

* Added `Trigger by Event`.
* Fixes and adjusmtents.

= 1.3.5.6 = 

* Added `Copy` action and action label.
* Ordered the list by `Delay` and by `Element Id`.
* Layout adjustements and fixes.

= 1.3.5.5 = 

* .twz and .xml are supported for import. 

= 1.3.5.4 = 

* The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

* Added ability to upload `.css` files into the Library.
* Added ability to `reorder` the Library.
* The menu is displayed in alphabetical order.
* Textarea auto-resizing adjustements.

= 1.3.5.2 = 

* Repeat an animation with `$(document).twizRepeat();` inside the Extra JavaScript textbox.

= 1.3.5.1 =

* Transferred methods into the new TwizMenu class.
* Major and minor fixes.

= 1.3.5 =

* Fixed delete section menu.

= 1.3.4.9 = 

* Fixed notice and warning messages.

= 1.3.4.8 = 

* Replay all animations with `$(document).twizReplay();`.
* When deactivating and activating the plugin to reinitialize it, `/wp-content/twiz/` is no longer removed.
* Automatic recognition of the js files under `/wp-content/twiz/`.
* Minor fixes and adjustments.

= 1.3.4.7 =

* Bug fixes.

= 1.3.4.6 =
 
* Added a New `JavaScript File Manager`. 
* Added a preloader for directional images.
* Moved and renamed the upload directory to `/wp-content/twiz/`.
* Removed the `Saved!` message for faster editing and better focusing. 
* Optimized the auto-resizing of the textarea for better editing.
* The file uploader is ready for translation.
* Major and minor bug fixes.
* Adjustments.

= 1.3.4.5 =
 
* Fixed table reinitialization.

= 1.3.4.4 =
 
* Added a `Delete section` button.
* Fixed export multilingual filename.
* Other minor fixes.

= 1.3.4.3 =
 
* Single and double quotes are now allowed. 

= 1.3.4.2 =
 
* Fully compatible with Internet Explorer 9.
* Minor fixes.

= 1.3.4.1 =

* Minor adjustments.

= 1.3.4 =

* Fixed display code.

= 1.3.3.9 =

* Optimized code, new constants.
* Renamed elements. 
* Fixed Import-Export bug, missing value in action.

= 1.3.3.8 =

* Modified fileuploader stylesheet.

= 1.3.3.7 =

* Enqueued fileuploader stylesheet.
* Updated Italian translation.

= 1.3.3.6 =

* Added Import file(*.twz) feature. `Share lists and collaborate with friends!` 
* Major changes.

= 1.3.3.5 =

* Added compatibility with IE8...

= 1.3.3.4 =

* Fixed the visibility of the add section menu.

= 1.3.3.3 =

* Fixed export file.

= 1.3.3.2 =

* Added a new feature to export a list. 'Backup and Share lists with friends.'
* Added Layout for the Import feature, it will be available in the next version.
* Some adjustments.

= 1.3.3.1 =

* Added Spanish translation.
* Minor changes.

= 1.3.3 =

* Fixed bug (Internet Explorer).

= 1.3.2.9 =

* Added dynamic directional arrows to the editing panel.
* Updated screenshots.

= 1.3.2.8 =

* Modified the size and location of the arrows.
* Updated screenshots.

= 1.3.2.7 =

* Added directional arrows to the datapreview panel.
* Fixed the hidden datapreview panel.
* Updated screenshots.

= 1.3.2.6 =

* Fixed unresolved sections. 

= 1.3.2.5 =

* Added numeric validation on Left and Top.
* Modified Installation code.

= 1.3.2.4 =

* Fixed Installation.

= 1.3.2.3 =

* Added feature to build lists for Categories and also Pages.
* Modified stylesheet, navigation effects, and layout.
* Modified database, and installation code.
* Modified front-end generated code.
* Added a 2x label on the edition panel.
* Updated screenshots.
* Minor fixes.
* Enjoy!

= 1.3.2.2 =

* Linked the plugin version label to the plugin page on wordpress.org
* Replaced short PHP tags by full PHP tags.
* Applied some CSS Coding standards.
* Updated screenshots.
* Minor fixes.

= 1.3.2.1 =

* Fixed notice and warning messages.

= 1.3.2 =

* Fixed Call to undefined function file_get_html().

= 1.3.1.9 =

* Fixed Missing argument 1 for Twiz::getListArray().
* Fixed Missing argument 1 for Twiz::getHtmlForm().

= 1.3.1.8 =

* Added Ajax editable 'Duration' column.
* Improved Ajax editable 'Delay' column.

= 1.3.1.7 =

* Fixed Enlarged layout.

= 1.3.1.6 =

* Enlarged layout.

= 1.3.1.5 =

* Improved security using Nonces.

= 1.3.1.4 =

* Fixed the validation for the delay textbox.(after cancel)
* Added a nowrap on the title cell from the right panel.

= 1.3.1.3 =

* Fixed the display of the right panel after addnew.

= 1.3.1.2 =

* Replaced tabs by spaces, everywhere in all the source code.
* Disabled submit button on form submit.

= 1.3.1.1 =

* Added validation for the delay textbox.

= 1.3.1 =

* Added status color on the element Id inside the View.
* Display the View when mouseover the list.
* Added Ajax editable 'Delay' column.
* Renamed some variables.
* Fixed jQuery binding.
* Minor layout fixes.

= 1.3.0 =

* Added more personalized options.
* Fixed enqueued Stylesheet.
* Minor changes.

= 1.2.9 =

* Optimized Stylesheet. (twiz-style.css)
* Minor layout adjustements.

= 1.2.8 =

* Enqueued Stylesheet. (twiz-style.css)
* Minor layout fixes.

= 1.2.7 =

* Added a full advanced caching feature for the DataPreview results.
* Fixed empty Labels in the DataPreview.(has already been pushed)

= 1.2.6 =

* Layout adjustments, larger panel, larger textbox, other minor changes.
* Added a new DataPreview Panel.

= 1.2.5 =

* Fixed Pick List Options choices(duplicated).
* Fixed Pick List link, and image right space.
* Fixed Edition panel background color.
* Fixed Plugin Height.

= 1.2.4 =

* Added a Global Online/Offline status over the top.
* Added a big background logo.
* Some minor changes.

= 1.2.3 =

* Added 2 New `Easy Pick List` under `Personalized Options`.
* Some minor, and major layout adjustements.
* Auto-size textarea(in height).

= 1.2.2 =

* When editing an existing movement, `More Options` are now visible by default, if they aren't empty.
* Empty lines has been removed.
* readme.txt has been updated.

= 1.2.1 =

* In the listing panel, a `x2` in green is now displayed next to the `duration` value only when the second move has been configured.
* Added Italian translation.

= 1.2 =

* Now you can easily choose an element Id from a pick list. 100% AJAX.

= 1.1 =

* 2 minor css fixes.
* jQuery .animate() link added under Personalized Options.

= 1.0 =
* First release!

== Frequently Asked Questions == 

= I've translated your plugin in my language. Could I send you the .po and .mo files? =

Yes, I will include your translation in future releases. E-mail me at `wordpress [at] sebastien-laframboise [dot] com`

== Upgrade Notice ==

= 1.4.4.1 = 

* Fixed a string replacement within Twiz functions.

= 1.4.4 = 

* Added Short code to output choices.

= 1.4.3 =

* Major update. 

= 1.4.2.1 = 

* Removed auto-draft items from lists.

= 1.4.2 = 

* Added unpublished pages & articles to lists.

= 1.4.1 = 

* Added preloading of twiz-loading.gif.

= 1.4 =

* Optimizations.

= 1.3.9.9 = 

* Fixed event bug.

= 1.3.9.8 = 

* Added features.

= 1.3.9.7 = 

* Turned some images into css background.

= 1.3.9.6 = 

* Fixes and stabilization.

= 1.3.9.5 = 

* Fixes, and plugin skins.

= 1.3.9.4 = 

* Added the ability to create custom menu.

= 1.3.9.3 = 

* Output optimizations, added a new parameter to the function repeat.

= 1.3.9.2 = 

* Fixed `Home` activation button display.

= 1.3.9.1 = 

* Added Admin option.

= 1.3.9 =

* Added features, and fixed bug.

= 1.3.8.9 =

* Added a vertical alignment to the right panel.

= 1.3.8.8 =

* Layout fixes.

= 1.3.8.7 =

* Bug fixes.

= 1.3.8.6 =

* Added a vertical menu. Bug fixes.

= 1.3.8.5 =

* Added many features. Layout fixes.

= 1.3.8.4 =

* Fixes.

= 1.3.8.3 =

* Added `Output code compression` option.

= 1.3.8.2 =

* Rewritten the output code.

= 1.3.8.1 =

* Fixes.

= 1.3.8 =

* Added Output hook choices, and `empty` for Top and Left.

= 1.3.7.9 =

* Added px, %, em, in.

= 1.3.7.8 =

* Bug fixes.

= 1.3.7.7 =

* Bug fixes.

= 1.3.7.6 =

* Hotfix.

= 1.3.7.5 =

* Layout fixes. Minor fixes.

= 1.3.7.4 =

* Simplified right panel, and the ability to easily call animations.

= 1.3.7.3 =

* Added the ability to choose a post when creating menus.

= 1.3.7.2 =

* Fixed conflict with upload media manager in post page.

= 1.3.7.1 =

* Fixed wp-minify incompatibility(new).

= 1.3.7 =

* Major update.

= 1.3.6.9 =

* Optimized output code.

= 1.3.6.8 =

* Correction, only JS can be switched after or before.

= 1.3.6.7 =

* Output small configs before the JavaScript.

= 1.3.6.6 =

* The editing panel is simplified and more flexible.

= 1.3.6.5 =

* Fixed navigation import menu, and default action label.

= 1.3.6.4 =

* Added support for $(this). inside the textbox `JavaScript Before`.

= 1.3.6.3 =

* All menu buttons are now visible when editing.

= 1.3.6.2 =

* Added the layout of the admin button. Minor fixes.

= 1.3.6.1 =

* ID, NAME and CLASS are supported.

= 1.3.6 =

* Fixed Everywhere, Everywhere.

= 1.3.5.9 =

New features. (Everywhere / All Categories / All Pages)

= 1.3.5.8 =

* Major update and fixes.

= 1.3.5.7 = 

* Added Trigger animation by event. And fixes.

= 1.3.5.6 = 

* Added `Copy` action, list ordered by `Delay` and `Element Id`.

= 1.3.5.5 = 

* .twz and .xml are supported for import. 

= 1.3.5.4 = 

* The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

* Major update, new features.

= 1.3.5.2 = 

* Added Repeat function.

= 1.3.5.1 =

* Major and minor fixes...

= 1.3.5 = 

* Fixed delete section menu.

= 1.3.4.9 = 

* Fixed notice and warning messages.

= 1.3.4.8 =

* Added Replay function.

= 1.3.4.7 =

* Bug fixes.

= 1.3.4.6 =
 
* Added a JavaScript File Manager. Bug fixes. Adjustments.

= 1.3.4.5 =
 
* Fixed table reinitialization.

= 1.3.4.4 =

* Added a delete section button. Fixed export multilingual filename.

= 1.3.4.3 =
 
* Major fixes.

= 1.3.4.2 =

* Fully compatible with IE9, Minor Fixes.

= 1.3.4.1 =

* Minor adjustments.

= 1.3.4 =

* Fixed display code.

= 1.3.3.9 =

* Optimized code. New constants. Fixed Import-Export.

= 1.3.3.8 =

* Modified fileuploader stylesheet.

= 1.3.3.7 =

* Enqueued fileuploader stylesheet.

= 1.3.3.6 =

* Added Import file feature. Major changes.

= 1.3.3.5 =

* Added compatibility with IE8...

= 1.3.3.4 =

* Fixed the visibility of the add section menu.

= 1.3.3.3 =

* Fixed export file.

= 1.3.3.2 =

* Added a new feature to export a list. 

= 1.3.3.1 =

* Added Spanish translation. Minor changes.

= 1.3.3 =

* Fixed bug (Internet Explorer).

= 1.3.2.9 =

* Added dynamic arrows to the editing panel.

= 1.3.2.8 =

* Modified the size of the arrows.

= 1.3.2.7 =

* Fixed hidden datapreview panel. Added arrows.

= 1.3.2.6 =

* Fixed unresolved sections.

= 1.3.2.5 =

* Added validation, and modified Installation.

= 1.3.2.4 =

* Fixed Installation.

= 1.3.2.3 =

* This is the update we've all been waiting for.

= 1.3.2.2 =

* Minor fixes and adjustments.

= 1.3.2.1 =

* Fixed notice and warning messages.

= 1.3.2 =

* Fixed Call to undefined function file_get_html(). 

= 1.3.1.9 =

* Fixed Missing arguments.

= 1.3.1.8 =

* Added Ajax editable 'Duration' column. Layout adjustments, improvements.

= 1.3.1.7 =

* Fixed Enlarged layout.

= 1.3.1.6 =

* Enlarged layout.

= 1.3.1.5 =

* Improved security using Nonces.

= 1.3.1.4 =

* Fixed the validation for the delay textbox.(after cancel), minor fixes.

= 1.3.1.3 =

* Fixed the display of the right panel after addnew. 

= 1.3.1.2 =

* Disabled submit button on form submit. Minor fixes.

= 1.3.1.1 =

* Added validation for the delay textbox.

= 1.3.1 =

* Added Ajax editable 'Delay' column. + Major fixes.

= 1.3.0 =

* Major fixes. More options. Critical update.

= 1.2.9 =

* Optimized stylesheet. (twiz-style.css)

= 1.2.8 =

* Enqueued stylesheet. (twiz-style.css)

= 1.2.7 =

Added a full advanced caching feature for the DataPreview results.

= 1.2.6 =

New DataPreview Panel, larger layout.

= 1.2.5 =

Major fixes, and adjustements.

= 1.2.4 =

Added a Global Online/Offline status over the top.

= 1.2.3 =

Major update 1-2-3, new features, minor fixes.

= 1.2.2 =

`More options` are now visible, if they aren't empty + minor adjustments.

= 1.2.1 =

Added Italian translation + minor adjustments + Thanks for your support!

= 1.2 =

Now you can easily choose an element Id from a pick list.

= 1.1 =

Minor fixes.

= 1.0 =

First release!

== Languages ==

The Welcomizer is currently available in the following languages:

* English 
* Français - 100%
* Italiano ([by Gianni Diurno](http://gidibao.net/)) - 95%
* Español - 65%