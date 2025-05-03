<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\InformacionpersonalD $model */

$this->title = /*$model->CIInfPer*/  $model->ApellInfPer . ' ' .  $model->NombInfPer;
$this->params['breadcrumbs'][] = ['label' => 'Informacionpersonal Ds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="informacionpersonal-d-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'CIInfPer' => $model->CIInfPer], ['class' => 'btn btn-primary']) ?>


    <?php
    $tipoUsuario = null; // Inicializamos la variable

    if (!Yii::$app->user->isGuest) {
        // El usuario ha iniciado sesión, podemos acceder a 'tipo_usuario'
        $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

        if ($tipoUsuario === 8) {
            echo Html::a('Eliminar', ['delete', 'CIInfPer' => $model->CIInfPer], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de eliminar este elemento?',
                    'method' => 'post',
                ],
            ]);
        }
    }
    ?>

    <?php
    $user = Yii::$app->user->identity;
    if ($user->username === $model->CIInfPer) {
        echo Html::a('Cambiar Contraseña', ['user/change-password'], ['class' => 'btn btn-warning'], ['confirm' => '¿Estas seguro de cambiar tu contraseña?',]);
    }
    ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'CIInfPer',
            'ApellInfPer',
            'ApellMatInfPer',
            'NombInfPer',
        ],
    ]) ?>

</div>