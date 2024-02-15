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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'ShJmN/9Ot<F`,P+#.<bY6cKcC>NPE4CUxPOB)Rp_lxV!r.x~bzzg!f7pC ;=|T,>' );
define( 'SECURE_AUTH_KEY',  ',k#9#hkz^X/zy^Q~4r;H[Vb!eK_qWOzvd){;va_V,mJ_vj4?uc(T{M$Lev({;I0S' );
define( 'LOGGED_IN_KEY',    '6eSM#o.VT5[CI`zPW:3[^`bVq(U?0cK:gfe2`jZ0?^Wy6<z.f4<m|N,LtD:q_RI-' );
define( 'NONCE_KEY',        'H}7D}Q$`t&#IkNo%R.HMd23@`/p`FgL{E*UTCr>5D8p3nKFT_8|3f4.sjDx64/-%' );
define( 'AUTH_SALT',        'PVkcV~YhUI7tgyxZ~c3(/~GN7!caa=UJTrhR0k#BUSF=@n:A;a|SbF8`6`LW@zcC' );
define( 'SECURE_AUTH_SALT', 'y>{5lc>(Vr%ddh,9Lms[/D6*e{q.,|&-L?|J6Dw0i^n(;^Lsi.QVC+H*u(5 5*-R' );
define( 'LOGGED_IN_SALT',   'ZJ!rLmao^> G27/H*=9q^gHR&$_B5[d g<F1GV;P,(9}.=n9k9S=PlKSw2@!401i' );
define( 'NONCE_SALT',       'Q`a>?]<SwhONA$x@O>:Hmi_s)P`^7mNKwU(ZY+}OP&1WH_~.3EX]Dj_j%OuD8hGX' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
