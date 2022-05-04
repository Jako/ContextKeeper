<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use modResource;
use TreehillStudio\ContextKeeper\Plugins\Plugin;
use xPDO;

class OnDocUnPublished extends Plugin
{
    public function process()
    {
        /** @var modResource $resource */
        $resource = $this->modx->getOption('resource', $this->scriptProperties);
        $writableContexts = $this->contextkeeper->getOption('writableContexts');
        $resetPublished = false;

        if (!in_array($resource->get('context_key'), $writableContexts)) {
            if (!empty($writableContexts)) {
                $resetPublished = true;
            } else {
                if ($this->contextkeeper->getOption('checkEmpty')) {
                    $resetPublished = true;
                }
            }
        }
        if ($resetPublished) {
            $resource->set('published', true);
            $resource->set('publishedby', 0);
            $resource->set('publishedon', time());
            $resource->save();

            $message = $this->modx->lexicon('contextkeeper.err_unpublish_nv', [
                'id' => $resource->get('id'),
                'context_key' => $resource->get('context_key'),
                'context_name' => $this->contextkeeper->getContextName($resource->get('context_key'))
            ]);
            if ($this->contextkeeper->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnDocUnPublished');
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
