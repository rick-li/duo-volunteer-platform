=== Easy Translation Manager for WordPress ===
Author: RightHere LLC
Author URL: http://plugins.righthere.com/easy-translation-manager/
Tags: WordPress, WordPress Multisite, Multi Language, Multi-Lingual, Translation, Translator, Translation, Custom Capabilities, WordPress SEO Support, Multiple Domains, Yandex Automated Translation
Requires at least: 3.4
Tested up to: 4.0.0
Stable tag: 4.0.0.54597

======== CHANGELOG ========
Version 4.0.0.54597 - November 1, 2014
* New Feature: Support for translating serialized data arrays. Multiple top-selling themes on Themeforest use their own builders, which stores the information as serialized data in the custom fields. Compatible with Themify Builder.
* New Feature: Drag and Drop language (flag) order for widgets.
* New Feature: SVG (Scalable Vector Graphics) flags for selecting language
* New Feature: Added five new Language selection types: Bouncing List, Box Slide, Rotating Bars, Fluid Grid, Responsive Circle
* New Feature: Yandex Linguistic technologies. Click on the Translate button and Yandex Translate API will suggest a translation for you. Remember this is only a suggestion and you should review this whether it is grammatically correct.
* New Feature: Added support for using Multiple Domain Names. Enter a domain name for each enabled language or simply use the default.
* Compatibility Update: Support for function reference _n which is for the parameters $single and $plural. This is often used by WooCommerce shop solution.
* Compatibility Update: Easy Translation Manager is now supporting the following functions for translation (WordPress Codex function reference) translate|__|_e|_n|_x|_ex|_nx|esc_attr__|esc_attr_e|esc_attr_x|esc_html__|esc_html_e|esc_html_x|_n_noop|_nx_noop
* New Feature: Site Translations provides translation for native WordPress features like: Admin Email, Blog Description, Blog Name, Date Format, Start of Week, Time Format. This allow for individual “settings” for each language
* Compatibility Fix: Buttons in translation dialog was hidden when Multi-Level Push Menu for WordPress is installed.
* Compatibility Fix: Improved support for Right-to-Left language.
* Improvement: Plugins and Theme search for text strings has been optimized. Search time reduced with 82%
* Improvement: Plugins and Theme scanning for text strings has been optimized. Search time reduced with 12%
* Improvement: Optimized .po/.mo file import and export
* Bug Fixed: utf8 encode problem when doing theme and plugin import/export fixed.
* Bug Fixed: scanning themes and plugins for text strings now show the right number of text translatable text strings

Version 3.0.4.54068 - September 22, 2014
* Bug Fixed: Export .po files was broken

Version 3.0.3.53501 - September 4, 2014
* Bug Fixed: Remove PHP warning added by WordPress to terms
* Bug Fixed: Custom terms are loaded after ETM fix

Version 3.0.2 rev53364 - August 14, 2014
* Bug Fixed: Remove PHP warning if language not selected

Version 3.0.1 rev53012 - July 30, 2014
* Bug Fixed: Notification error when ID numbers was returned twice. Caused translation not to show in the frontend

Version 3.0.0 rev52462 - July 15, 2014
* Bug Fixed: Remove PHP warnings when an option is not set
* Update: Adjust ETM path for theme integration, use horizontal layout, disable license tab

Version 2.9.9 rev51684 - June 29, 2014
* Bug Fixed: To comply with ISO language codes en_UK was changed to en_GB

Version 2.9.8 rev51635 - June 27, 2014
* New Feature: Added support for Sri Lanka, Sinhalese, සිංහල ජාතිය (si_LK)
* New Feature: Added support for Canada, French Canadian (fr_CA)

Version 2.9.7 rev49186 - April 24, 2014
* Bug Fixed: Alignment of radio buttons in language selection (WordPress 3.9 compatibility)

Version 2.9.6 rev49048 - April 22, 2014
* Bug Fixed: Z-index on dialog (wp-admin menu in left side was above after updating to WordPress 3.9)
* Bug Fixed: Positioning of buttons in dialog (WordPress 3.9 compatibility)

Version 2.9.5 rev48336 - April 1, 2014
* Bug Fixed: Translated permalinks broken. 

Version 2.9.4 rev47979 - March 21, 2014
* Compatibility Update: Support for latest version of WordPress SEO (1.5.2.5)

Version 2.9.3 rev44364 - January 12, 2014
* Bug Fixed: Scroll problem in Chrome and Safari

Version 2.9.2 rev44050 - January 6, 2014
* Bug Fixed: Removed php warning

Version 2.9.1 rev43931 - December 27, 2013
* New Feature: Added support for translating Calendarize it! add-ons 

Version 2.9.0 rev43489 - December 17, 2013
* Bug Fixed: Problem with symbols at import and export of .po files
* Bug Fixed: wp-text-edit issue with buttons fixed
* Bug Fixed: Missing auto detection of certain Custom Post Type
* Update: Change loading of extra files to standard instead of custom URL (wp-content/plugins/easy-translation-manager/frames/... to /?etm_fn=...&etm_data=true)

Version 2.8.5 rev42754 - December 3, 2013
* Bug Fixed: List problem only shows post data not other types
* Update: Compatibility fix for translating Events in Calendarize it! for WordPress

Version 2.8.4 rev42534 - November 25, 2013
* Update: Rewritten how information is loaded into lists. This is to accommodate users with thousands of posts.

Version 2.8.3 rev40404 - September 26, 2013
* Bug Fixed: Problem with frame load

Version 2.8.2 rev39180 - August 23, 2013
* Bug Fixed: install.php notification error
* Bug Fixed: Post Meta list not showing 

Version 2.8.1 rev39011 - August 20, 2013
* New Feature: Added two new custom capabilities etm_options and etm_license. Makes it possible to restrict access to the Options Panel and the License tab. This is useful if you are using the plugin on a clients website.

Version 2.8.0 rev36620 - August 2, 2013
* Bug Fixed: SEO Meta description was missing (after updating to latest version of WordPress SEO by Yoast)
* New Feature: Added option to easier edit Manual Strings added for translation
* New Feature: Added support for import and export of .po files
* Update: Improved the text string scanner so that it will find all variables like this: __('text','textdomain') _e('text','textdomain') __('text', $texdomain) _e('text', $textdomain) __('text',APP_Textdomain) _e('text',APP_Textdomain) __('text') _e('text')

Version 2.7.1 rev36580 - May 21, 2013
* Bug Fixed: Fixed missing language variable, which is passed on to CWA Easy Translation Manager add-on (used for translating content in the Arbitrary HTML/Text Widget)

Version 2.7.0 rev36458 - May 12, 2013
* New Feature: New Language based search feature 
* New Feature: Option to deactivate the language based search feature
* New Feature: Pop-Up Cancel notification added when clicking outside the editor window
* New Feature: Option to change the location of the rtl.css
* Bug Fixed: Editor out of bounce problem
* Bug Fixed: Toolbar jumps and resizing problem when translating Post and Pages
* Bug Fixed: PHP warnings
* Update: Remove resize arrows for text areas
* Update: Add auto resize tool to editor window when you hide or show the second line of icons.

Version 2.6.3 rev36355 - May 7, 2013
* Bug Fixed: Problem with RTL support fixed.

Version 2.6.2 rev36156 - April 24, 2013
* Update: Fixed spelling error on theme translation page

Version 2.6.1 rev35804 – April 8, 2013
* Bug Fixed: Updated Support for WordPress SEO by Yoast. Support for version 1.4.6
* Bug Fixed: Fixed issue with buttons in editor
* New Feature: Update Options Panel with Auto Update
* New Feature: Set the layout for english text use [ENG] and the original text use [ORG] in select language widget.

Version 2.6 rev32644 - January 15, 2013
* Update: Updated Support for WordPress SEO by Yoast. Support for version 1.3.4.4
* New Feature: Optional Select Language bar (top, left side, right side, bottom)
* Bug Fixed: Issue with buttons in editor

Version 2.5.7 rev28781 - August 13, 2012
* New Feature: Added support for Hong Kong (香港) (zh_HK)

Version 2.5.6 rev26079 - June 14, 2012
* Bug Fixed: .mo and .po files for wp-admin not updating
* Bug Fixed: Filtering on Posts and Pages

Version 2.5.5 rev25696 - June 4, 2012
* Bug Fixed: Removed php Warnings

Version 2.5.4 rev24794 - May 11, 2012
* New Feature: Shortcode added for Language Menu in content
* New Feature: Button added for easily inserting Shortcode for Language Menu
* Bug Fixed: Problem with Permalinks fixed
* Bug Fixed: Problem with LRT/RTL editor fixed

Version 2.5.3 rev24242 - April 27, 2012
* New Feature: Added support for Taiwanese (臺語, Tâi-gí)
* Bug Fixed: Removed PHP warnings when no language is set.

Version 2.5.2 rev23107 - March 21, 2012
* Bug Fixed: Translated Permalink was not switching to original link when changing language

Version 2.5.1 rev23015 - March 19, 2012
* Bug Fixed: Translated Permalink was displaying Page on wrong WP template.

Version 2.5.0 rev22775 - March 10, 2012
* Update: Core updated and optimized for faster loading
* Update: Optimized SEO XML sitemap
* New Feature: Support for translation of Custom Fields on Pages, Posts and Custom Post Types.
* New Feature: Support for translation of Attachment image
* New Feature: Support for translation of ALT text for featured image
* New Feature: Support for translation of Permalinks
* New Feature: Enable usage of custom language Permalinks
* New Feature: Support for translation of Post Slug
* New Feature: Support for translation of Post Description
* New Feature: Enable feature that makes a flag inactive if content has not been translated
* New Feature: Enable hide elements that has not been translated (Pages, Posts, Tags, Categories, Menus)
* New Feature: Support for translation of Excerpt content for all Posts and Pages
* New Feature: Support for translation of custom URL in menus
* New Feature: Support for translation of Title attribute in menus
* New Feature: Update support for WordPress SEO by Yoast XML site map to include translated Permalinks
* New Feature: Support for translation of Custom Fields
* New Feature: Added function to extract active language with code and name array
* New Feature: Added support for CWA (Custom Widget Area) add-on for Easy Translation Manager (assign Custom Widget Areas to a specific language)
* New Feature: Added Support for Slovak (Slovenský jazyk)
* New Feature: Added function for getting current language set in Easy Translation Manager
* Bug Fix: Problem with get_cat_name function
* Bug Fix: Problem with using two different 'textdomains' for the same text string (within the same plugin, theme)
* Bug Fix: Problem with Editor (this was an issue for some browsers)
* Bug Fix: Problem with language code system
* Bug Fix: Problem when having multiple translators. Created a rescan button to scan for __() _e() if there is no __() registered will auto scan.
* Bug Fix: Problem with same string using Multiple 'textdomains' (if a plugin have the same string with 2 different text domains both will be shown)


Version 2.0.0 rev20254 - February 8, 2011
* Bug Fix: Visual editor bug fixed on Post and Pages translation
* New Feature: Added Support for Português do Brasil (Brazilian Portuguese)
* New Feature: Set width and alignment for do_action
* New Feature: Set width and alignment for Widget
* New Feature: New widget for selecting language
* New Feature: Add Dashboard metabox with wp-admin languages
* New Feature: Change wp-admin language (require download of .mo files)
* New Feature: Upload .mo language files to /wp-content/languages/
* New Feature: Export Post and Pages including all translations
* New Feature: Support for SEO (If you use WordPress SEO by Yoast the plugin will be supported)
* New: Completely new database structure (Reduced the number of tables from 7 to only 2. Previous version created 7 tables on main site and all sub-sites if used on a WordPress Multisite installation. The new version only creates 2 tables on the main site and NONE on the sub-sites) 

Old tables: 
wp_etm_lang
wp_etm_menu
wp_etm_plugin_index
wp_etm_plugin_string
wp_etm_post
wp_etm_post_meta
wp_etm_post_terms

New tables:
wp_etm_plugin_index
wp_etm_plugin_string

When you instal the new version your data from the old tables will automatically be converted and added to the new tables. We will NOT delete the old tables just in case if something goes wrong, then you don't lose your translations.


Version 1.0.4 rev15985 - January 10, 2011
* Update: Load the latest Options Panel, if there is a plugin installed with a newer Options Panel

Version 1.0.3 rev15712 - January 4, 2011
* Update: Added support for Faroese (Føroyskt) 
* Update: Added support for Greenlandic (Kalaallisut)
* Update: Added support for Azerbaijani (Azeri)

Version 1.0.2 rev15609 - January 3, 2011
* Bug Fixed: Category and Post Tags not showing on public website when translated
* New Feature: Added Cancel button if you make changes and forget to save
* Update: Optimized category and post tags function (uses 35% less resources)
* Update: Added support for Cambodian (Khmer)

Version 1.0.1 rev15431 - December 30, 2011
* Bug Fixed: CSS for all major browsers updated for Select Language Widget when only showing flag.

Version 1.0.0 rev15375 - December 28, 2011
* First release.


======== DESCRIPTION ========
Now you can translate your WordPress powered website easier and faster than ever. Easy Translation Manager lets you translate Pages, Posts,Post Tags, Post Categories, Custom Post Types, Menus, Plugins and Themes.
Your visitors can easily choose their preferred language from a drop down in the sidebar. Or you can automatically set the language based on the web browsers language.
The plugin is compatible with WordPress Multisite and supports Custom Capabilities.


== INSTALLATION ==

1. Upload the 'easy-translation-manager' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the menu you will find Site Tour.

== FREQUENTLY ASKED QUESTIONS ==
If you have any questions or trouble using this plugin you are welcome to contact me through my profile on Codecanyon (http://codecanyon.net/user/RightHere)

Or visit our Support site at http://righthere.com/support/


== SOURCES - CREDITS & LICENSES ==

We have used the following open source projects, graphics, fonts, API's or other files as listed. Thanks to the author for the creative work they made.

1) MSDDropDown - jquery.dd.js - Marghoob Suleman - www.marghoobsuleman.com

2) Flags from Icondrawer.com - Order reference no.: 10320644