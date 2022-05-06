<?php
/**
 * @package contextkeeper
 * @subpackage plugin
 */

namespace TreehillStudio\ContextKeeper\Plugins\Events;

use TreehillStudio\ContextKeeper\Plugins\Plugin;
use modSystemEvent;

class OnDocFormPrerender extends Plugin
{
    public function process()
    {
        $writableContexts = $this->contextkeeper->getOption('writableContexts');
        $disableSave = false;

        if ($this->modx->getOption('mode', $this->scriptProperties) === modSystemEvent::MODE_NEW) {
            $disableSave = true;
        } else {
            /** @var \modResource $resource */
            $resource = $this->modx->getOption('resource', $this->scriptProperties);
            if (!$resource || !in_array($resource->get('context_key'), $writableContexts)) {
                $disableSave = true;
            }
        }
        if ($disableSave) {
            $this->modx->controller->addHtml('
<script type="text/javascript">
    Ext.onReady(function () {
        var saveBtn = Ext.getCmp("modx-abtn-save");
        if (saveBtn) {
            saveBtn.setDisabled(true);
        }
        var deleteBtn = Ext.getCmp("modx-abtn-delete");
        if (deleteBtn) {
            deleteBtn.setDisabled(true);
        }
    });
</script>');
            if ($this->contextkeeper->getOption('disableDuplicateButton')) {
                $this->modx->controller->addHtml('
<script type="text/javascript">
    Ext.onReady(function () {
        var duplicateBtn = Ext.getCmp("modx-abtn-duplicate");
        if (duplicateBtn) {
            duplicateBtn.setDisabled(true);
        }
    });
</script>');
            }
        }
    }
}
