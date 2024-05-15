<?php

$script_path = $_SERVER[ 'SCRIPT_FILENAME' ];

define( 'GIT_PROJECT_VERSION', '1.0.0' );
define( 'GIT_PROJECT_SLUG', 'git-project' );
define( 'GIT_PROJECT_REAL_PATH', __DIR__ . '/' );
define( 'GIT_PROJECT_PATH', dirname( $script_path ) . '/' );

$git_project_path = str_replace( 'git-project/', '', GIT_PROJECT_PATH );
$wp_config_path   = realpath( $git_project_path . 'wp-config.php' );
$wp_config_path_2 = realpath( $git_project_path . '../wp-config.php' );
$repo_path        = realpath( GIT_PROJECT_REAL_PATH . '../' );

if ( $wp_config_path !== false ) {
    require_once $wp_config_path;
} else if ( $wp_config_path_2 !== false ) {
    require_once $wp_config_path_2;
} else {
    die( 'The WP configuration file does not exist.' );
}

define( 'WP_USE_THEMES', false );

require_once ABSPATH . 'wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$required_plugins = [
    'atwu-dev-tools/atwu-dev-tools.php',
];

add_filter(
    'option_active_plugins',
    function ( $plugins ) use ( $required_plugins ) {
        $new_plugins = [];
        foreach ( $plugins as $plugin ) {
            if ( in_array( $plugin, $required_plugins ) ) {
                $new_plugins[] = $plugin;
            }
        }
        return $new_plugins;
    }
);

require_once 'vendor/autoload.php';
require_once 'src/autoload.php';

Git_Project::init( $repo_path );
