<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db562583113');

/** MySQL database username */
define('DB_USER', 'dbo562583113');

/** MySQL database password */
define('DB_PASSWORD', 'i*WGsE2o7|ca4|r');

/** MySQL hostname */
define('DB_HOST', 'db562583113.db.1and1.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Vlup^nx7QAQFc`hXSsM+wg 8+;84QFE1wp#[><5=bde,<4YauOAZ|k|3!5_NMZrj');
define('SECURE_AUTH_KEY',  '/)W!`YOOcmJ3+-k7?eq8s;m,^{4pujxu(t9Xm/pFq-GGeFPt>9L!<Fdf]|?eA46D');
define('LOGGED_IN_KEY',    '?J{*Yj1qpT6^i8JBbk2F.a;,:6-~U[&9r^C2?7anet&+!BhL?t$C@^xm5q^+x]G]');
define('NONCE_KEY',        '~iQB}c1yQg&DP=vm |VW+JyOj e8R:`*,nBrru>zm Eo8Fr#WLxPbRNfP4WbE>>5');
define('AUTH_SALT',        'sI,|s0Zi=yH,1U4vDy5 )[K]!`2KXa1-Go>gH^ {l^s)~qsY=@@+0cGeI9O?ooN^');
define('SECURE_AUTH_SALT', '-y:|j-+7F+ltp*N:M#GCc{!*2L/i<2F6vg>G6sUcnq?WBVrOd<`ITgM^8c++}?8S');
define('LOGGED_IN_SALT',   '-4D~Uf?g[n CR,99@Hu_N,p7Y6#ufqQ/S[q(*n~M2w5Ak0dlT1+gkoPp*Dk4$._[');
define('NONCE_SALT',       'LulVuiLt1,a-0F|&t/Va`X>MAeR%|?@oKW>o8v?KCsv3N w>m11;*|iHq;N3@W#l');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
