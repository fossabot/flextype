<?php

declare(strict_types=1);

/**
 * Flextype (https://flextype.org)
 * Founded by Sergey Romanenko and maintained by Flextype Community.
 */

if (flextype('registry')->get('flextype.settings.entries.fields.published_by.enabled')) {
    flextype('emitter')->addListener('onEntryCreate', static function (): void {
        if (flextype('entries')->getStorage('create.data.published_by') == null) {
            flextype('entries')->setStorage('create.data.published_by', '');
        }
    });
}
