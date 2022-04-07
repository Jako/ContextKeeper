<?php
/**
 * Abstract plugin
 *
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins;

use modX;
use ContextKeeper;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var ContextKeeper $contextkeeper */
    protected $contextkeeper;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('contextkeeper.core_path', null, $this->modx->getOption('core_path') . 'components/contextkeeper/');
        $this->contextkeeper = $this->modx->getService('contextkeeper', 'ContextKeeper', $corePath . 'model/contextkeeper/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}