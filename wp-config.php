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
define('DB_NAME', 'codeanddesign');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'htl&SiRonTv&*^^mUAQp_K&[G6Q $*HumMMgie@VVzh:xq92(4Y7q`}elLrAb-aH');
define('SECURE_AUTH_KEY',  'w9@_,tX1wo!;z=u/r?Qd}lV8C}#2*cSmMRs@z.R9O+7N.XR;v(P7>>E #<W6COAN');
define('LOGGED_IN_KEY',    'pF`?PAaUK8Q8I>b>#%gZ.BUKU+}ZmnBg f (6;p!?/`% H&6hV-fOZ%NMB?}8+pv');
define('NONCE_KEY',        'Y0&/V/o!cQw2n|B+l3j#O$m/qR7Vz-${#>qOgtC#K*=fzZT13}B#w~;875l-#,!j');
define('AUTH_SALT',        '}rmi{w9(CUaX39;JR3WW0kWP|/E1[X[$pIX?tJ<4.;l(iCj.r4 k*f8k`1@6~B?^');
define('SECURE_AUTH_SALT', '$8g`cpq4+@_H10(6Ot/kuh-2Pu}fZ+>X?/F!-o;8hGik,*tR 8)P^@1HW<yy[ZC>');
define('LOGGED_IN_SALT',   '#DYIf16M95Wuzx G4PDcUvxJur:`&a|s 7)F!<_Ar~/{C&*X;vYJL<D,.M%6&/Ks');
define('NONCE_SALT',       'lN3+EG;h5:I5M2RJj+OdBvb?bqW2IN~+Wr/8nXME;3Um]Mu@A6}qTJMK/AeiEw_w');

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
