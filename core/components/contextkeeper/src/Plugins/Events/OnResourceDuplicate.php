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
        /** @var modResource $duplicateResource */
        $duplicateResource = $this->modx->getOption('newResource', $this->scriptProperties);
        $writableContexts = $this->contextkeeper->getOption('writableContexts');

        if (!in_array($duplicateResource->get('context_key'), $writableContexts)) {
            if (!empty($writableContexts)) {
                $duplicateResource->set('context_key', reset($writableContexts));
                $duplicateResource->set('parent', 0);
                $duplicateResource->save();
                $message = $this->modx->lexicon('contextkeeper.err_move_nv', [
                    'id' => $duplicateResource->get('id'),
                    'context_key' => $duplicateResource->get('context_key'),
                    'context_name' => $this->contextkeeper->getContextName($duplicateResource->get('context_key'))
                ]);
                if ($this->contextkeeper->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceDuplicate');
                }
            } else {
                if ($this->contextkeeper->getOption('checkEmpty')) {
                    $message = $this->modx->lexicon('contextkeeper.err_move_na');
                    if ($this->contextkeeper->getOption('debug')) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceDuplicate');
                    }
                    $duplicateResource->remove();
                }
            }
        }
    }
}
