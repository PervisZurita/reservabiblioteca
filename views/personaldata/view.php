<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Personaldata $model */

$this->title = $model->Nombres .' '. $model->Apellidos;
$this->params['breadcrumbs'][] = ['label' => 'Personaldatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="personaldata-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a('Actualizar', ['update', 'Ci' => $model->Ci], ['class' => 'btn btn-primary']) ?>
    <?php
    $tipoUsuario = null; // Inicializamos la variable

    if (!Yii::$app->user->isGuest) {
        // El usuario ha iniciado sesión, podemos acceder a 'tipo_usuario'
        $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

        if ($tipoUsuario === 8) {
            echo Html::a('Eliminar', ['delete', 'Ci' => $model->Ci], [
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
            if ($user->username === $model->Ci) {
            echo Html::a('Cambiar Contraseña', ['user/change-password'], ['class' => 'btn btn-warning'], ['confirm' => '¿Estas seguro de cambiar tu contraseña?',]);

            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Ci',
            'Apellidos',
            'Nombres',
            'FechaNacimiento',
            'Email:email',
            'Genero',
            'Institucion',
            'Nivel',
        ],
    ]) ?>

</div>
