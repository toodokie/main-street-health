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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost:/Users/anastasiavolkova/Library/Application Support/Local/run/67xTAxe5E/mysql/mysqld.sock' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'v!P` r>r397tjH:73z%Yn1[%UM+$P*#mtio=ynOR&)sVPYdO.pCq9H+-=3q2n^rc' );
define( 'SECURE_AUTH_KEY',   'n]P:1Nfi~(/[%Z1EC7o:No_46`W/#<;Er.a,jX}tz<mR 0wJ2I9zju:OYGrk~H~i' );
define( 'LOGGED_IN_KEY',     'U)myL![xV`x/$6kgNdYtErqFm<t]-]~ap0wz#,m,^AtpkZKN1tn<fq.R)vETGLw[' );
define( 'NONCE_KEY',         'pE^?o)8rU.!t]krDKe$crsOp9jT[(8rFo{`9#AarA,TJoR5k*nT:Fj*;iOjm|e,q' );
define( 'AUTH_SALT',         'X/Q=P=i_$Bv3{7?b(P(VC0lcY`Rz-hV+THGSiq8<%a1SLu2W-k`pI4#!|3$]<vP3' );
define( 'SECURE_AUTH_SALT',  '^al+_jnE62b?(x6-00k+V$<;2N?VSnRE)~:oZjf5(WjS JI*PrK:u,.rg+e.4N~J' );
define( 'LOGGED_IN_SALT',    '#!xRdEkhF_D]2C.-eqfDM]V{H;@5Xk0TwBCKP;O7-<rw)5&N&UwHvmAcJU~kqnry' );
define( 'NONCE_SALT',        'SpFBEW+fi*(cr0lawz?sZ>sr(1,EjQLeF]i]bz?7J|iVc7K/;:vp@|smF-E-b_J#' );
define( 'WP_CACHE_KEY_SALT', '}ER)ll%dr!]A2^c5. ?OQZdd%ND*1jH..vWb^sIPmQq.&el{(n_lf@3j85S2Dh`L' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

if (!defined('MSH_INDEX_PROFILING')) {
    define('MSH_INDEX_PROFILING', true);
}

if (!defined('MSH_INDEX_USE_SET_SCAN')) {
    define('MSH_INDEX_USE_SET_SCAN', true);
}



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
	define( 'WP_DEBUG', true );
}

if ( ! defined( 'WP_DEBUG_LOG' ) ) {
	define( 'WP_DEBUG_LOG', true );
}

if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	define( 'WP_DEBUG_DISPLAY', false );
}

@ini_set( 'display_errors', 0 );

define( 'WP_ENVIRONMENT_TYPE', 'local' );

/* OpenAI API Configuration */
if (!defined('OPENAI_API_KEY')) {
    $env_openai_key = getenv('OPENAI_API_KEY');
    if ($env_openai_key === false) {
        $env_openai_key = '';
    }
    define('OPENAI_API_KEY', $env_openai_key);
}

// Dynamic URL detection for deployment flexibility
if (isset($_SERVER['HTTP_HOST'])) {
    define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
    define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
} else {
    // Fallback for CLI/local environment
    define('WP_HOME', 'https://main-street-health.local');
    define('WP_SITEURL', 'https://main-street-health.local');
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
