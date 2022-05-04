<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use modResource;
use TreehillStudio\ContextKeeper\Plugins\Plugin;
use xPDO;

class OnDocPublished extends Plugin
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
            $resource->set('published', false);
            $resource->set('publishedby',false);
            $resource->set('publishedon',false);
            $resource->save();

            $message = $this->modx->lexicon('contextkeeper.err_publish_nv', [
                'id' => $resource->get('id'),
                'context_key' => $resource->get('context_key'),
                'context_name' => $this->contextkeeper->getContextName($resource->get('context_key'))
            ]);
            if ($this->contextkeeper->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'ContextKeeper OnDocPublished');
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
