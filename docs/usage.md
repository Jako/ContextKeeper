## How it works

This MODX extra checks the context key of the saved or duplicated resource
against a comma separated list in the `contextkeeper.writable_contexts`
system/usergroup/user setting. The resource can only be saved in a writable
context. If the resource is duplicated in a not writable context, it is moved to
the root of the first writable context. The MODX extra moves also resources
duplicated with Babel.

That way non-writable contexts can remain visible for all users, while they are
unable to edit the content.

## System Settings

ContextKeeper uses the following system settings in the namespace `contextkeeper`:
   
Setting | Description | Default
------- | ----------- | -------
contextkeeper.check_empty | Test the write restriction, even if the the system/usergroup/user setting `writable_contexts` is empty. | No
contextkeeper.debug | Log debug information in the MODX error log. | No
contextkeeper.disable_duplicate_button | Disable the duplicate button in resources of not writable contexts. | No
contextkeeper.writable_contexts | Comma separated list of writable context keys. | -

As always, these system settings can be overridden by user group and user
settings. Therefore, user group and user specific restrictions are possible.
