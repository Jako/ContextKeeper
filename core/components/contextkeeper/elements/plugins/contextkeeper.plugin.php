<?php
/**
 * ContextKeeper Plugin
 *
 * @package contextkeeper
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\ContextKeeper\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('contextkeeper.core_path', null, $modx->getOption('core_path') . 'components/contextkeeper/');
/** @var ContextKeeper $contextkeeper */
$contextkeeper = $modx->getService('contextkeeper', 'ContextKeeper', $corePath . 'model/contextkeeper/', [
    'core_path' => $corePath
]);

if ($contextkeeper) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'ContextKeeper Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'ContextKeeper Plugin');
    }
}

return;