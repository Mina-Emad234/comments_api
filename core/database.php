<?php
return [//return connection data inside array {database connection config}
    'database'=>[
        'name'=>'comments_api',
        'username'=>'root',
        'password'=>'',
        'connection'=>'mysql:host=localhost',
        'options'=>[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]
    ]
];
