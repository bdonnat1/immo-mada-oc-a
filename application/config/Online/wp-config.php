<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

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
define('DB_NAME', 'ctht2_base');

/** MySQL database username */
define('DB_USER', 'ctht2');

/** MySQL database password */
define('DB_PASSWORD', 'KnB54dx98');

/** MySQL hostname */
define('DB_HOST', 'sql5.imingo.net');

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
define('AUTH_KEY',         'k29nczsahqpbx7zjipzoldpq4oxy2znlbqxyhttux2t6s3invlvrhbaem3kbhun6');
define('SECURE_AUTH_KEY',  'behkdj970mqqnp6mxc51ofptgow1zyrwk7tmiahoe97jg6qqhjcc5yxl6cmgdt2c');
define('LOGGED_IN_KEY',    'b1k4ovw2m64krsmvihkbylktt1djfpyxqgjjc6mjmdo7lldfqqbq5djfvacwld7b');
define('NONCE_KEY',        '6td7eraebmob1mgreu3myns05yo245zvhuo3wz0nur1h1cpyh4xw6euvxeckknwq');
define('AUTH_SALT',        'zvfknkyeev7intw1pvc9r4hqxqb0gg40rbsyh1j6xbvfbxa6kfrlzpeom2cjyatx');
define('SECURE_AUTH_SALT', 'dsmqfxphmpjqlelo9yhxaug0phoycakzf6uz9vcqeluqxgkbqaqdwctmxzg86jfg');
define('LOGGED_IN_SALT',   'fj9omy7mxbif3wumrlwci5apxvnnjmmac9ehocgutpvbn9epgjjdhekqne6wfejq');
define('NONCE_SALT',       'egjbljncokgg3ys7b9kivfmma9pdirrfmcuj1p4xq3q1yhpqixxcjdo99qsqlbfh');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpctht_';

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
