<?php
/**
 * ContextKeeper
 *
 * Copyright 2022 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package contextkeeper
 * @subpackage classfile
 */

namespace TreehillStudio\ContextKeeper;

use modX;

/**
 * class ContextKeeper
 */
class ContextKeeper
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'contextkeeper';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'ContextKeeper';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.0.0';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * ContextKeeper constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);
        $this->packageName = $this->modx->lexicon('contextkeeper');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        $writableContexts = $this->modx->getOption('contextkeeper.writable_contexts', null, '');
        $writableContexts = ($writableContexts) ? array_map('trim', explode(',', $writableContexts)) : [];

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->getOption('debug', $options, false),
            'checkEmpty' => (bool)$this->getOption('check_empty', $options, false),
            'writableContexts' => $writableContexts,
        ]);


        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * @param $key
     * @return string
     */
    public function getContextName($key)
    {
        $context = $this->modx->getContext($key);
        if ($context) {
            return $context->get('name');
        } else {
            return $this->modx->lexicon('contextkeeper.err_unknown_context');
        }
    }
}
