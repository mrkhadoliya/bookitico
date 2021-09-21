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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ramlogic_bookit' );

/** MySQL database username */
define( 'DB_USER', 'ramlogic_bookit' );

/** MySQL database password */
define( 'DB_PASSWORD', 'E}bPM,&wQK!H' );

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
define( 'AUTH_KEY',         'FL7gtz/?<&wUFU59*uC:`6DH$^!JtvWTHWxt@MfUWZpQpGglCs :yL.m{7^ox_d`' );
define( 'SECURE_AUTH_KEY',  'axtt{9xiM-4+-(xq),J:c~Oi)7?8vM- 90d{1_*&mtPske-HSDFN~XwH5%tvxOIO' );
define( 'LOGGED_IN_KEY',    's%)Z5@1>klt5[i{Y.2n2D+RhY!tyRAw;PlRX!bV`]D}B:q7=1iC3AK74lj*Bk-=B' );
define( 'NONCE_KEY',        'rM=q@N=SQ=n+TWQktC4[+zTyND+*] ;qb7W6s<ZNl#PrCFvIhXK^Bwd5|8lb4<w]' );
define( 'AUTH_SALT',        'lb#qbCbRhPh!W:?>Ef2Zbxc/^8=+Pc#o3e$L(Zo&^wvjDmxFv84KdBTt`oixdo1F' );
define( 'SECURE_AUTH_SALT', '[4y:V<UHfEY7y$zpS}?@#s)^m5)+c+5+FlD^C(Ux!azfbEl7Y>!L7&C+ssxUAKHu' );
define( 'LOGGED_IN_SALT',   '<A[C3S2?]bS;;f%d)M+V8DNH^LM lrMh%G@Eb/Mh_ES2Uj0,B-:g`wH+&)$ ogEU' );
define( 'NONCE_SALT',       '*;V0Eoqv|@k|X0OLD #Wkm7jE=G}@%J]L1og]3]+*v#]n}W*_(Mwpb&B[P=6]~Lg' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
