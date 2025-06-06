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
            <div class="col-md-8 mx-auto">
                <?= $content ?>
            </div>
        </div>
    </div>
<?php
} else {
    // Si el usuario está autenticado, muestra el menú y el contenido principal
?>
    <div class="container-fluid">
        <!-- Contenido principal -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Puedes agregar breadcrumbs u otro encabezado aquí si es necesario -->

            <!-- Main content -->
            <div class="content">
                <?= $content ?>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
<?php
}
?>
