<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'woo');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'EX[dT*-=jLxyD_:+FqmC;2D#AYh3[}I3!(&p4V=f!@6bJ:YYX!?XU1y_,7g%j~NU');
define('SECURE_AUTH_KEY',  '^X85SYx/5*_O097d?-:|+WS`kH.!+l1]Jch[95X?}^/?.!-?i|W-+ZJk=kHd2Q`T');
define('LOGGED_IN_KEY',    'Fqkl2TL>dSST@QY nU>C%=.*(rH(O_&sEFwX5|+<:J{i97E#pM%|W4t;4||3F8I$');
define('NONCE_KEY',        '5<rRV8_An7O,;Z/7n(&Jwk {Qdb<>+`x09A|4I/FsqI}*}:O^xj~Se)Pf%p)_->W');
define('AUTH_SALT',        '`{8ie=ti[t`PL=1aw!@Zd,GuaeQC`26P<+/Lf,c-OR<+TvZn KA^Zl.|~Zv=-u,M');
define('SECURE_AUTH_SALT', ';+F3HiJ{2T0*V68qy+6L<|t<9(08slLvG_d^@^}6U)x,GVTaFL;-+fBI>BG3)4Co');
define('LOGGED_IN_SALT',   'wLp^6d`vd1bayX]dezP:`P.E5fD3p2FU(|b<|y-C0-Z;)doK5.NP(WEpgYBmjqc+');
define('NONCE_SALT',       'j,R^xY67!8oOf<Q,Hg9H<5Q:sc:wL+sdGi=nYi/}yLL@hY+s#-0 ,eUd2vH<ISx-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
