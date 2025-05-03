<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Biblioteca $model */

$this->title = $model->Campus;
$this->params['breadcrumbs'][] = ['label' => 'Bibliotecas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="biblioteca-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $tipoUsuario = null; // Inicializamos la variable

    if (!Yii::$app->user->isGuest) {
        // El usuario ha iniciado sesión, podemos acceder a 'tipo_usuario'
        $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

        if ($tipoUsuario === 8 || $tipoUsuario === 7) {
            echo '<div style="margin-bottom: 10px;">'; // Aplicar un margen inferior
            echo '<a href="' . Yii::$app->urlManager->createUrl(['update', 'idbiblioteca' => $model->idbiblioteca]) . '" class="btn btn-primary" style="margin-right: 10px;">Actualizar</a>';
            echo '<a href="' . Yii::$app->urlManager->createUrl(['delete', 'idbiblioteca' => $model->idbiblioteca]) . '" class="btn btn-danger" style="margin-right: 10px;" data-confirm="¿Estás seguro de eliminar este elemento?" data-method="post">Eliminar</a>';
            echo '</div>';
        }
    }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'idbiblioteca',
            'Campus',
            'Apertura',
            'Cierre',
            'Email:email',
            'Telefono',
        ],
    ]) ?>

</div>