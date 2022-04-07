<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use modResource;
use TreehillStudio\ContextKeeper\Plugins\Plugin;
use xPDO;

class OnResourceDuplicate extends Plugin
{
    public function process()
    {
        /** @var modResource $newResource */
        $newResource = $this->modx->getOption('newResource', $this->scriptProperties);
        $writableContexts = $this->contextkeeper->getOption('writableContexts');
        if (!in_array($newResource->get('context_key'), $writableContexts)) {
            if (!empty($writableContexts)) {
                $newResource->set('context_key', reset($writableContexts));
                $newResource->set('parent', 0);
                $message = $this->modx->lexicon('contextkeeper.err_move_nv', ['id' => $newResource->get('id'), 'context_key' => $newResource->get('context_key')]);
                if ($this->contextkeeper->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceDuplicate');
                }
            } else {
                if ($this->contextkeeper->getOption('checkEmpty')) {
                    $message = $this->modx->lexicon('contextkeeper.err_move_na');
                    if ($this->contextkeeper->getOption('debug')) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceDuplicate');
                    }
                    $newResource->remove();
                }
            }
        }
    }
}
