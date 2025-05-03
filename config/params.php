<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',        // ...
        'user.passwordMinLength' => 8, // O cualquier otro valor numérico

        'hail812/yii2-adminlte3' => [
            'pluginMap' => [
                'sweetalert2' => [
                    'css' => 'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
                    'js' => 'sweetalert2/sweetalert2.min.js'
                ],
                'toastr' => [
                    'css' => ['toastr/toastr.min.css'],
                    'js' => ['toastr/toastr.min.js']
                ],
            ]
        ]
];
