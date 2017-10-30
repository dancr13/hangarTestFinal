<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'testhangar');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123456');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'mE}cle*~f&9[{O >N]dsLfc;%0h*Yl~zxXPbG[iiMr%)(j;+Pvb[05K8T,8>uN{j');
define('SECURE_AUTH_KEY',  '66oU$n/F;*]B|PJWRm>tC{p]cDR7qxpV#`Z)BaF#lzulqTlh,Jar+C_vgJ5UsGSC');
define('LOGGED_IN_KEY',    '[-`~26 W*f^Uv _Q-hHUm{P=4Rz!`p@Z{LDGC`=]B^6Yw{Mvsfg[D(kBA/=zkFeg');
define('NONCE_KEY',        '9rpSAH2w}X-SA|ENjdN0 J)^My0gt3OfeJ?yh&T,u1(beScvlR_4#9Ij:g@-.%qY');
define('AUTH_SALT',        'Mbtm>e|+zS5Plg:>k:_&g65fD$9MRs{YL.WQOO9N/S<-3tE&kt9p5u8,A~v<uvQ%');
define('SECURE_AUTH_SALT', '5+YTl^N>68CC*,9pwS<j3jb<5Kr!#4<6MO+@WNB@h}0rzXwjR+rPZT[?j}d_{aoo');
define('LOGGED_IN_SALT',   '78G#}8_OUgbB{A ni89mNu+CeO_0?R= GGcM~SHPvH6|pn|YPlLKs/4;Z^<vUpgx');
define('NONCE_SALT',       '0%#lNt$*_9{4.fbT28V4+?l.-s&SV(0IBj(LxZxK A34!`;SG}kw4O?VQ;^?AuuN');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
