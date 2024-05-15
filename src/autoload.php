<?php
/**
 * Anonymous function that registers a custom autoloader.
 *
 * @package Git Project
 * @subpackage Dev Tools
 * @author Alexey Chaschin <alexey.chaschin@gmail.com>
 */

spl_autoload_register(
    function ( $class ) {
        $base_dir = GIT_PROJECT_PATH . '/src/';
        $file     = str_replace( '\\', '/', $class );
        $parts    = explode( '/', $file );
        $sufix   = 'class';
        if ( in_array( 'Traits', $parts ) ) {
            $sufix = 'trait';
        }
        $parts[ count( $parts ) - 1 ] = $parts[ count( $parts ) - 1 ] . '.' . $sufix . '.php';
        $file                         = implode( '/', $parts );
        $file                         = str_replace( '_', '-', strtolower( $file ) );
        if ( file_exists( $base_dir . $file ) ) {
            include_once $base_dir . $file;
        }
    }
);
