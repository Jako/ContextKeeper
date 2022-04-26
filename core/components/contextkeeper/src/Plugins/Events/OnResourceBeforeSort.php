<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use TreehillStudio\ContextKeeper\Plugins\Plugin;
use xPDO;

class OnResourceBeforeSort extends Plugin
{
    public function process()
    {
        $source = explode('_', $this->modx->getOption('source', $_REQUEST, 0));
        $sourceContext = !empty($source[0]) ? $source[0] : '';
        $sourceId = !empty($source[1]) ? (int)$source[1] : 0;
        $target = explode('_', $this->modx->getOption('target', $_REQUEST, 0));
        $targetContext = !empty($target[0]) ? $target[0] : '';
        $writableContexts = $this->contextkeeper->getOption('writableContexts');

        if (empty($sourceContext) || !in_array($sourceContext, $writableContexts)) {
            if (!empty($writableContexts) || $this->contextkeeper->getOption('checkEmpty')) {
                if ($sourceContext !== $targetContext) {
                    $message = $this->modx->lexicon('contextkeeper.err_move_fromto_nv', [
                        'id' => $sourceId,
                        'source_key' => $sourceContext,
                        'source_name' => $this->contextkeeper->getContextName($sourceContext),
                        'target_key' => $targetContext,
                        'target_name' => $this->contextkeeper->getContextName($targetContext)
                    ]);
                } else {
                    $message = $this->modx->lexicon('contextkeeper.err_move_in_nv', [
                        'id' => $sourceId,
                        'context_key' => $sourceContext,
                        'context_name' => $this->contextkeeper->getContextName($sourceContext)
                    ]);
                }
                if ($this->contextkeeper->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceBeforeSort');
                }
                @session_write_close();
                exit (json_encode([
                    'success' => false,
                    'message' => $message,
                    'total' => 0,
                    'data' => [],
                    'object' => []
                ]));
            }
        }
        if (empty($targetContext) || !in_array($targetContext, $writableContexts)) {
            if (!empty($writableContexts) || $this->contextkeeper->getOption('checkEmpty')) {
                if ($sourceContext !== $targetContext) {
                    $message = $this->modx->lexicon('contextkeeper.err_move_fromto_nv', [
                        'id' => $sourceId,
                        'source_key' => $sourceContext,
                        'source_name' => $this->contextkeeper->getContextName($sourceContext),
                        'target_key' => $targetContext,
                        'target_name' => $this->contextkeeper->getContextName($targetContext)
                    ]);
                } else {
                    $message = $this->modx->lexicon('contextkeeper.err_move_in_nv', [
                        'id' => $sourceId,
                        'context_key' => $targetContext,
                        'context_name' => $this->contextkeeper->getContextName($targetContext)
                    ]);
                }
                if ($this->contextkeeper->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnResourceBeforeSort');
                }
                @session_write_close();
                exit (json_encode([
                    'success' => false,
                    'message' => $message,
                    'total' => 0,
                    'data' => [],
                    'object' => []
                ]));
            }
        }
    }
}
