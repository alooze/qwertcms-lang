<?php
/////////////////////////////////  Админка //////////////////////////////////////
Route::group(['prefix' => 'admin', 'middleware' => 'auth'],  function () {
    ////////// УПРАВЛЕНИЕ ЯЗЫКАМИ //////////
    Route::get('langs', ['as' => 'a.langs', 'uses' => 'LangController@index']);
    Route::get('langs/ajax', ['as' => 'a.langs.ajax', 'uses' => 'LangController@ajax']);
    Route::post('langs/ajax', ['as' => 'a.langs.save', 'uses' => 'LangController@saveState']);
});