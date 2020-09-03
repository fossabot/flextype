<?php

declare(strict_types=1);

/**
 * Flextype (https://flextype.org)
 * Founded by Sergey Romanenko and maintained by Flextype Community.
 */

if (flextype('registry')->get('flextype.settings.entries.fields.published_by.enabled')) {
    flextype('emitter')->addListener('onEntryCreate', static function () : void {
        if (isset(flextype('entries')->storage['create']['data']['published_by'])) {
            flextype('entries')->storage['create']['data']['published_by'] = flextype('entries')->storage['create']['data']['published_by'];
        } else {
            flextype('entries')->storage['create']['data']['published_by'] = '';
        }
    });
}
