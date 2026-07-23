<?php
/**
 * Router para el servidor embebido de PHP (php -S) en desarrollo.
 * Sirve archivos/carpetas reales tal cual y enruta el resto a WordPress.
 */
$uri  = urldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
$path = __DIR__ . $uri;

// Deja que el servidor sirva archivos y directorios reales (incluye .php e index.php).
if ( '/' !== $uri && ( is_file( $path ) || is_dir( $path ) ) ) {
	return false;
}

// Todo lo demas (portada y permalinks) lo maneja WordPress.
require __DIR__ . '/index.php';
