=== Flywheel Automated Migration ===
Contributors: blogvault, akshatc
Tags: flywheel, migration
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Migrating your site(s) to the Flywheel platform has never been so easy. 

== Description ==

The Flywheel Automated Migration plugin makes it very easy for you to migrate your site(s) to the flywheel platform. The plugin takes care of everything, from copying all the data to transforming config files and importing this to the flywheel server. Just start the migration and the plugin will do all the heavy work!

= How to use the plugin: =

Once you have [installed](https://wordpress.org/plugins/flywheel-automated-migration/installation/) and activated the plugin on your WordPress website, you can start migrating your site to the flywheel platform. You only have to fill in the following fields and press the ‘Migrate’ button.

**Email:** This email address will receive status updates of your migration.

**Destination site URL:** The primairy domain URL that you want to use at flywheel.

**Flywheel URL** This is the URL you see when you chick at your site in Dashboard.

**sFTP User and sFTP Password:** These fields have been sent to your registered email address.

**User(for this site) and Password(for this site)** These fields are for you to fill if you site is made password protected by Flywheel

== Installation ==

= There are two ways to install the flywheel Automated Migration plugin: =

1. Download the plugin through the ‘Plugins’ menu in your WordPress admin panel.
2. Upload the flywheel-automated-migration folder to the /wp-content/plugins/ directory through sFTP.

After installing you need to activate the plugin.

== Changelog ==
= 1.20 =
* Adding DB Signature and Server Signature to uniquely identify a site
* Adding the stats api to the WordPress Backup plugin.
* Sending tablename/rcount as part of the callback

= 1.17 =
* First release of flywheel Migration Plugin
