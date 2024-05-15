<?php
/**
 * TWIG templates implementation.
 * 
 * @package Git Project
 * @subpackage Dev Tools
 * 
 * @author Alexey Chaschin <alexey.chaschin@gmail.com>
 */

namespace Git_Project;

use Atwu_Dev_Tools\Template as Template_Base;

/**
 * Template class
 */
class Template extends Template_Base {

    /**
     * Class instance
     *
     * @var static|null
     */
    protected static $instance = null;

    /**
     * Init template vars
     *
     * @return void
     */
    protected function init() {
        $this->localization_domain = GIT_PROJECT_SLUG;
        $this->template_path       = GIT_PROJECT_PATH . 'templates';
        $this->time_zone           = 'Europe/Amsterdam';
        $this->cache_version       = GIT_PROJECT_VERSION;
    }
}
