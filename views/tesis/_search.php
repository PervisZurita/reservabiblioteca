<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TesisSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tesis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'numero_estanteria') ?>

    <?= $form->field($model, 'facultad') ?>

    <?= $form->field($model, 'carrera') ?>

    <?= $form->field($model, 'tema') ?>

    <?php // echo $form->field($model, 'autor') ?>

    <?php // echo $form->field($model, 'tutor') ?>

    <?php // echo $form->field($model, 'anio_publicacion') ?>

    <?php // echo $form->field($model, 'codigo') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
