<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'group_thrpy_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '.qW#*jmUJ+T6?OC>q>H01%qC1FeSrvjMoUNY-)q.LzY.Ubj(LppL^4YH<t20:VJ}' );
define( 'SECURE_AUTH_KEY',  '{R}vJ|ksKnE}SCrRr?+x_|9saqr~V6wtuhf?t i:q_7uLXp+=>2d1?Bnse~}0z!G' );
define( 'LOGGED_IN_KEY',    '5VOvFyP*q[FwoL6s7+sr8SQd}J@%$pTOd4@pkQ*EoW0YM%e6.aLz[D$O;i_LtO*}' );
define( 'NONCE_KEY',        '=%/m}1V^5]HMmJ 1{xLQ/!bK/4.Os`XyCKI7%yZc!kSPw0u&zn*j$?j<9[L{keFI' );
define( 'AUTH_SALT',        'rLwammGDF1F^:L=f%+Em1y.%YCD7U~e4;5a!=JQ0p(?@`|TZ%4Nr G^;zH!?;}/_' );
define( 'SECURE_AUTH_SALT', 'hyVhK6NU~8Ag0cA[Vg{;_wUTDk5dpQ>`=Cc;){wYu*S-8;9h.(FcRrV~58^)4INS' );
define( 'LOGGED_IN_SALT',   'rJTU+QbUq^t=Jew<1/_}8S.p0gC#N#(+REyYT9mHw_I)GGV(19+BYju0@.rLL {o' );
define( 'NONCE_SALT',       '|;#}r$O<.hU=+M8PEtp6_c3Ccqt1I%qU27z.ww8|W96D]-lzdW/5FA> X|CwY94>' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'gt_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
