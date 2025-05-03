<?php

return [
    'class' => 'yii\db\Connection',
    //'dsn' => ''mysql:host=localhost;dbname=reservabiblioteca',
    //'username' => 'pervis',
  //  'password' => '',
   // 'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
 	'dsn' => 'mysql:host=localhost;dbname=biblioteca',

    	'username' => 'root',

	'password' => '',
	//'password' => '1237894560',
    	'charset' => 'utf8',
    	'enableLogging' => true, // Habilitar el registro de consultas SQL
   	'enableProfiling' => true, // Habilitar el registro de perfiles de tiempo de ejecución de las consultas SQL

];
 // TOCA AHORA INSTALAR ESTA BDD Y CONFIGURAR ESTE ARCHIVO 

