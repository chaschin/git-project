<?php
/**
 * The main class witch implemented needed interfaces and core functionality.
 * 
 * @api https://github.com/czproject/git-php
 * @package Git Project
 * @subpackage Dev Tools
 * 
 * @author Alexey Chaschin <alexey.chaschin@gmail.com>
 */

use Exception;
use CzProject\GitPhp\GitRepository;
use Git_Project\Template;

/**
 * Git_Project class.
 */
class Git_Project {

    /**
     * Class instance.
     *
     * @var static|null
     */
    protected static $instance = null;

    /**
     * Repository path.
     *
     * @var string
     */
    private $repo_path = '';

    /**
     * GitRepository instance.
     *
     * @var object CzProject\GitPhp\GitRepository
     */
    private $repo = null;

    /**
     * Current branch name.
     *
     * @var string
     */
    private $current_branch = '';

    /**
     * Get Instance.
     *
     * @param string $repo_path Repository path.
     * @return static
     */
    public static function get_instance( string $repo_path ) {
        self::$instance = is_null( self::$instance ) ? new self( $repo_path ) : self::$instance;
        return self::$instance;
    }

    /**
     * Clone.
     *
     * @return void
     */
    public function __clone() {
    }

    /**
     * Wake Up.
     */
    public function __wakeup() {
    }

    /**
     * Class constructor.
     *
     * @param string $repo_path Repository path.
     * @return void
     * @throws Exception On error.
     */
    protected function __construct( string $repo_path ) {
        try {
            $this->repo = new GitRepository( $repo_path );
        } catch ( Exception $e ) {
            wp_die( $e->getMessage() );
        }
    }

    /**
     * Init 
     *
     * @param string $repo_path Repository path.
     * @return void
     */
    public static function init( string $repo_path ) {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            if ( wc_user_has_role( $user_id, 'administrator' ) !== true ) {
                wp_redirect( '/' );
                exit;
            }
        } else {
            wp_redirect( '/' );
            exit;
        }

        $git = self::get_instance( $repo_path );

        $git->current_branch = $git->repo->getCurrentBranchName();
        
        if ( isset( $_GET['action'] ) && isset( $_GET['branch'] ) ) {
            $action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
            $branch = sanitize_text_field( wp_unslash( $_GET['branch'] ) );

            switch ( $action ) {
                case 'checkout':
                    $git->switch_branch( $branch );
            }
        }

        $git->get_local_branches();
    }

    public function get_local_branches() {
        // $this->repo->fetch( [ 'origin', 'master' ] );
        
        $status = $this->repo->execute( 'status' );
        $data   = [
            'git_project_url' => WP_HOME . '/git-project',
            'current_branch'  => $this->current_branch,
            'branches'        => $this->repo->getLocalBranches(),
            'status'          => implode( '<br>', $status ),
        ];

        $template = Template::get_instance();
        $content  = $template->render( 'list-branches', $data );

        echo $content;
    }
    
    public function switch_branch( string $branch_name ) {
        // $this->repo->fetch();
        // $this->repo->pull('origin');
        if ( $this->current_branch !== $branch_name ) {
            $this->repo->checkout( $branch_name );
            $this->current_branch = $this->repo->getCurrentBranchName();
        }
    }
}
