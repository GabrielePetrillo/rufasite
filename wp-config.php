<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'rufa' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'gBhb)E,?=55_buLSLa6](6o0W->KPA!u=z<shci.c=S_nGiD[zKHmR^8D[qPOnS.' );
define( 'SECURE_AUTH_KEY',  'T.x=GW[2g[!Pld<N)~(iV`^T.s@lm%Q%)THNb GD4p)CZi4=9R#iY@`:X(-[*Fel' );
define( 'LOGGED_IN_KEY',    'V~qc+jwcq_U2(^])cVyF`yLfEld}{bV>1&wrUG=ZUBCZhwhZ&[ysGVD4Al9zuiM-' );
define( 'NONCE_KEY',        '~X[dHqjX}s*|6Yeqa|8j6&y}Db/wtA[O9&SHuXkQnd(*vX.eY)Z~7On6U, f0IBi' );
define( 'AUTH_SALT',        'lMYn:EzypHpq^~qTVNO5w@N*.,Ja%q.Z+%&P>1+*BjM6LdzSHNUW4R>K}uWLO/3C' );
define( 'SECURE_AUTH_SALT', '%|IDu/nk:Rn?3Q`RC (iFtF[I?iPmeCq@Q#.q~`tfr|fDJ4 f}f}R+67U %,|u+j' );
define( 'LOGGED_IN_SALT',   '$Kp4@pG-1K1fS-m}89d:tk.L6A{vfpWXU%9_V3d>/.^gA2$^9_C|j(gMp|n?:uB=' );
define( 'NONCE_SALT',       'h?U-hqmCT +/.;ZS/<z/))g#:VaH=C4i}Wn]3p)R/r(Ov;sVkblus{9~<,3R;G(u' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
