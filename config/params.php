<?php
Yii::setAlias('@base_folder', realpath(dirname(__FILE__) . '/../'));
return [
    'adminEmail' => 'robot@samaragips.adm',
    'supportEmail' => 'robot@samaragips.adm',
    'user.passwordResetTokenExpire' => 3600,
    'user.emailConfirmTokenExpire' => 259200,
    'txt.files.path' => '/files/txt/',
    'xml.files.path' => '/files/xml/',
    'zip.files.path' => '/files/zip/',
    'htm.files.path' => '/files/htm/',
    'open_url' => 'open',
    'close_url' => 'close',
];
