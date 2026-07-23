<?php
/**
 * Configuracion de WordPress - Entorno LOCAL (XAMPP)
 * Proyecto: SPIRUP (ecommerce)
 */

// ** Ajustes de la base de datos ** //
define( 'DB_NAME', 'spirup' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// ** Claves unicas de autentificacion (salts) ** //
define('AUTH_KEY',         'uWN=89-J|65H%/W#(l(IaH@PgRs<!9%Yw5>KE^#x*XbS%@;in>y%EbP1cf^kv8bW');
define('SECURE_AUTH_KEY',  '7MytE<^.cb~iG|uCg_qBpfe9|X?CS:N|QHUX^Z*PVN1(r_TXvUK<s4DoGa/IcAuP');
define('LOGGED_IN_KEY',    '~X&v4A|2d&2oT`:2vnN*|83#Pi3m iCF|umr4QXv|* tAA)jCtrlMZOs mP%-j94');
define('NONCE_KEY',        'dH!7-OGc#C+Bjme$6[9<5A!?%_;*Z8E5dVFAN_fk|#SlHZ-&$8. +r/X`r2#z|9A');
define('AUTH_SALT',        '3-7-&$Z]&{Xhm(D.;qm0@/o!LL(5%{_%*sued*m%f+)vd^J8&p?/v%YtPMe)lNyX');
define('SECURE_AUTH_SALT', '0Qy.xTl^YxR(Rj?p-d_:A~_O&M(DhLVVB:)rgRI-~*L{mEKTdI6pV9@LS+cR-?k`');
define('LOGGED_IN_SALT',   '$+o2,zG=O2V6KC-*5It]#.88Bef0b7_+bE +OYxKoPJ)ssk1W%T;KzCIA4uqlpK^');
define('NONCE_SALT',       '/~Ti~s688;+T>5-`mjK ^+7f_Tc[tyQ;aV)tQ4$-FA#yTZ]bj%^[0W9z+~ZeI}%.');

// ** Prefijo de tablas ** //
$table_prefix = 'wp_';

// ** URLs fijas para el entorno local ** //
define( 'WP_HOME',    'http://localhost:8000' );
define( 'WP_SITEURL', 'http://localhost:8000' );

// ** Desarrollo: depuracion activada ** //
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// ** Permitir escritura directa de archivos (no pedir FTP en admin) ** //
define( 'FS_METHOD', 'direct' );

// ** Aumentar limite de memoria ** //
define( 'WP_MEMORY_LIMIT', '256M' );

/* Eso es todo, deja de editar aqui. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
