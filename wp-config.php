<?php
define('WP_AUTO_UPDATE_CORE', false);// Эта настройка требуется для того, чтобы убедиться, что обновлениями WordPress можно корректно управлять в WordPress Toolkit. Удалите эту строку, если этот экземпляр WordPress больше не управляется WordPress Toolkit.
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'p-12054_wps' );

/** MySQL database username */
define( 'DB_USER', 'p-120_wps' );

/** MySQL database password */
define( 'DB_PASSWORD', 'X$20EsN8wo' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '5dJn5r2v2yI24H#hEG9@4T3Tt)!/n3rzux79%6%e/*r(e@CYOLgB~Z14GJ[f0W1+');
define('SECURE_AUTH_KEY', '@t6#V2Gi1+pSZZ~|xS!(3_n*K6S|9;B_B*Lej29P(p:hJ-(4UMP2*%/I8[4H84HY');
define('LOGGED_IN_KEY', '~f2N83C/4j_jA0G5A/PE5x/PV*36B+%e0z+153f;B&K-@e5U1+~pHz3(TRxKJgE[');
define('NONCE_KEY', '3Ay1G029ocyncCAW]BMo*ZG%N(1q3504q4+5d6a@5-DXT7m29p8/GNd06**1N34P');
define('AUTH_SALT', ']#Ns1ZhSL_5jNdjG6dg#1F4y8_1Tpt6aq)+V|a_l84HnEaj@xLeZ/10k3Sql*o3W');
define('SECURE_AUTH_SALT', 'XKDl918R9/ic4xmaUjb&/j4P8#~x0rw]Q]#Ac&210f;Du|9(8a%3A&r61[Vyzu63');
define('LOGGED_IN_SALT', 'n(6Y7t%Bd3p7XLwt6np|wg);x#V4DXB2FFv~j;5XFVt3PY0fNYPQM;E@HH-dgZbb');
define('NONCE_SALT', '@M~2#o+VB*X7:#:kW67UuEd6_sL05Eb0J!h2c1Y~1%4Od&X/1(fOY-v4~;r&/py9');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
