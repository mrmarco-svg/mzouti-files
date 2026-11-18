<?php
use Monolog\Handler\StreamHandler;
return [
'default'=>env('LOG_CHANNEL','stderr'),
'channels'=>[
'stderr'=>['driver'=>'monolog','handler'=>StreamHandler::class,'with'=>['stream'=>'php://stderr'],'level'=>env('LOG_LEVEL','warning')],
'single'=>['driver'=>'single','path'=>storage_path('logs/laravel.log'),'level'=>env('LOG_LEVEL','debug')],
'null'=>['driver'=>'monolog','handler'=>Monolog\Handler\NullHandler::class]
]
];