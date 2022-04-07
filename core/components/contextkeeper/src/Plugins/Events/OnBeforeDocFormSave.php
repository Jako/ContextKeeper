<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use modResource;
use TreehillStudio\ContextKeeper\Plugins\Plugin;
use xPDO;

class OnBeforeDocFormSave extends Plugin
{
    public function process()
    {
        /** @var modResource $resource */
        $resource = $this->modx->getOption('resource', $this->scriptProperties);
        $writableContexts = $this->contextkeeper->getOption('writableContexts');
        if (!in_array($resource->get('context_key'), $writableContexts)) {
            if (!empty($writableContexts)) {
                $message = $this->modx->lexicon('contextkeeper.err_save_nv', ['id' => $resource->get('id'), 'context_key' => $resource->get('context_key')]);
                if ($this->contextkeeper->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnBeforeDocFormSave');
                }
            } else {
                if ($this->contextkeeper->getOption('checkEmpty')) {
                    $message = $this->modx->lexicon('contextkeeper.err_save_na', ['context_key' => $resource->get('context_key')]);
                    if ($this->contextkeeper->getOption('debug')) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnBeforeDocFormSave');
                    }
                    $this->modx->event->output($message);
                }
            }
        }
    }
}
