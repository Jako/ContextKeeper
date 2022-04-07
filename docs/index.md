# ContextKeeper

Avoid saving resources in the wrong context.

### Requirements

* MODX Revolution 2.8+
* PHP 7.2+

### Features

This MODX extra is useful when a manager user can see more contexts and their
resources than the user is allowed to edit.

* Check for writable contexts in system/usergroup/user settings.
* Disallow saving ressources in not writable contexts.
* Move duplicated resources to the first writable context.
* Move duplicated Babel resources to the first writable context.
