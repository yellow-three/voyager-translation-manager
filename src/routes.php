<?php

declare(strict_types=1);

$multilingual = config('voyager.multilingual.enabled');

if ($multilingual) {

    $config = config('translation-manager.route');

    Route::group($config, function () {
        Route::group(['as' => 'voyager.translations.'], function () {

            $namespacePrefix = '\\' . config('voyager.controllers.namespace') . '\\';

            Route::get('view/{groupKey?}', $namespacePrefix . 'Controller@getView')->where('groupKey', '.*')->name('view');
            Route::get('/{groupKey?}', $namespacePrefix . 'Controller@getIndex')->where('groupKey', '.*')->name('index');
            Route::post('/add/{groupKey}', $namespacePrefix . 'Controller@postAdd')->where('groupKey', '.*')->name('add');
            Route::post('/edit/{groupKey}', $namespacePrefix . 'Controller@postEdit')->where('groupKey', '.*')->name('edit');
            Route::post('/groups/add', $namespacePrefix . 'Controller@postAddGroup');
            Route::post('/delete/{groupKey}/{translationKey}', $namespacePrefix . 'Controller@postDelete')->where('groupKey', '.*')->name('delete');
            Route::post('/import', $namespacePrefix . 'Controller@postImport')->name('import');
            Route::post('/find', $namespacePrefix . 'Controller@postFind')->name('find');
            Route::post('/locales/add', $namespacePrefix . 'Controller@postAddLocale')->name('locales.add');
            Route::post('/locales/remove', $namespacePrefix . 'Controller@postRemoveLocale')->name('locales.remove');
            Route::post('/publish/{groupKey}', $namespacePrefix . 'Controller@postPublish')->where('groupKey', '.*')->name('publish');
            Route::post('/translate-missing', $namespacePrefix . 'Controller@postTranslateMissing')->name('translate-missing');
        });
    });
}
