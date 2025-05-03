<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;

// Verificar si el usuario está autenticado
if (Yii::$app->user->isGuest) {
    // Si el usuario es un invitado (no ha iniciado sesión), muestra solo el contenido del login
?>
    <div class="container-fluid">
        <!-- Espacio en blanco antes del login (puede ajustar el tamaño aquí) -->
        <div class="row mt-5">
            <div class="col-md-8 mx-auto ">
                <?= $content ?>
            </div>
        </div>
    </div>
<?php
} else {
    // Si el usuario está autenticado, muestra el menú y el contenido principal
?>
    <div class="container-fluid">
        <div class="row">
            <!-- Menú lateral -->
            <div class="col-md-3">
                <!-- Aquí coloca tu código del menú -->
            </div>

            <!-- Contenido principal -->
            <div class="col-md-12">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <!-- Aquí coloca tu código de encabezado si es necesario -->
                    </div>
                    <!-- /.content-header -->

                    <!-- Main content -->
                    <div class="content">
                        <?= $content ?>
                    </div>
                    <!-- /.content -->
                </div>
            </div>
        </div>
    </div>
<?php
}
?>