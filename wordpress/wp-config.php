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
define( 'DB_NAME', 'tourism' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '*joTsRH#r^l8[c`^{Rw<)V_/b2nYH/e/mGv!HZt24K{LSQuZ;J0vd-0hIe76[]!n' );
define( 'SECURE_AUTH_KEY',  'nlAA%L(DczI:SkBls|zQd7cI g3>.]Ro;kJR:@Q&;*A3GBfTj81-ro=Pz]uJG3|/' );
define( 'LOGGED_IN_KEY',    'BEP +1$7cX=r[`pZJ=rcD/U/UWB+1Q`qSzHO>37?Wt^D2x9`j#`L-R[!cp`eTWRx' );
define( 'NONCE_KEY',        'e4-0l0?AEz6sWg:%uq=cL8@nBmbr5PIS}150?*p@fQM6`%TkZLqQ,5H|A??,Q$;Q' );
define( 'AUTH_SALT',        'iE*V{]3X61bwkwq[K,1|ij#?CA*5&0N^`9-efuRO2OfK>Kwj3M{,s=.Ghd^!Qar ' );
define( 'SECURE_AUTH_SALT', ';?NiLE6i(qU)T0,Hv$$_,O;f6ymg$2wpd&@tL0SL|TY(~wQw.lGrV)(F0n=Mbiq1' );
define( 'LOGGED_IN_SALT',   'hh3(-cNakA^%}0jj;gCtl=lowQp{S:Imt:P9&B?rpN(UVC:WeM3EB.#wK*REJs]:' );
define( 'NONCE_SALT',       '!)Z3{A^rcVq7~Rm>~2tuG^ybHYBLz%eBwT3g=[L.*#4bQL&rrb,GQ:AY[%}{c;W=' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );
// define( 'WP_DEBUG_DISPLAY', false );
// define( 'WP_DEBUG_LOG', true );
// @ini_set ( 'display_errors',0 );
// define( 'SCRIPT_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
