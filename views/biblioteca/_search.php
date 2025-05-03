<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BibliotecaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biblioteca-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idbiblioteca') ?>

    <?= $form->field($model, 'Campus') ?>

    <?= $form->field($model, 'Apertura') ?>

    <?= $form->field($model, 'Cierre') ?>

    <?= $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
