<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */


// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'group3' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_HOME','http://localhost/public_html');
define('WP_SITEURL','http://localhost/public_html');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'vw<Ny]I3MM7@e*QXz_-GDS0pW9cT=qIvbP@6^;]-_VWp)`~[G8.:$!K^4G(eiVa5' );
define( 'SECURE_AUTH_KEY',   ')F?}6s*_q0K_[10^c>(q~l=+qNm<po*|6(D?XNIJqm6,:ef87%{3EqKO)^xNn(&S' );
define( 'LOGGED_IN_KEY',     ';TC|Jb}Rb3EUez`a34o3kg 0+!xlI|cr[WbdKqs^It1]o</ez>W:gnC, bUwg+m-' );
define( 'NONCE_KEY',         'G4+P.p b`V-4d:  <qz?&S-X8@yU`IsE&HS%Fu;xm!uBU_{jMZsB$yxQskWFT?}d' );
define( 'AUTH_SALT',         'ehhQ_v&DP9C[qc GqEALKLs@9cHpm2pxy8W>pT_oBgA_+i@BZAJNB7qfU7q(=[TR' );
define( 'SECURE_AUTH_SALT',  '+cU>:yTM[$HKL71Wv9Xy&<,]X+JKZ!+IndxhgJvqL!/aNg|9O7^ #UA(L&]on{Qn' );
define( 'LOGGED_IN_SALT',    'wbh,hm2G)K{?02(%>qs=b*P~X5gRGf1ml^i-BUYcu~|$JrAbO7}k<k:(Tidsq@l5' );
define( 'NONCE_SALT',        'DI*+!US2i`:*U%$9v<Xhxl2`x_%MvWbO(OSI%_DHXhL9? {[877DbIj/L_K)DJdD' );
define( 'WP_CACHE_KEY_SALT', 'WV|/1%Q[:*)wgj%qKFC?7.SEsR$pwi.o5$iWwg,hh9ZU -zg&M,1v4p7-0[rQ[1`' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'b9e0095537e9774a779d41143348802f' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';



