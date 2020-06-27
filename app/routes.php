<?php

return [
    '/'                     => 'HomeController@index',
    'works'                 => 'WorkController@index',
    'works/new'             => 'WorkController@new',
    'works/{id:\d+}'        => 'WorkController@show',
    'works/{id:\d+}/update' => 'WorkController@update',
    'works/{id:\d+}/delete' => 'WorkController@delete',
];