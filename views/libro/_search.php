<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LibroSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="libro-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Ingrese ID']) ?>

    <?= $form->field($model, 'ubicacion')->textInput(['placeholder' => 'Ingrese ubicación']) ?>

    <?= $form->field($model, 'numer')->textInput(['placeholder' => 'Ingrese número']) ?>

    <?= $form->field($model, 'biblioteca_idbiblioteca')->textInput(['placeholder' => 'Seleccione biblioteca']) ?>

    <?= $form->field($model, 'clasificacion')->textInput(['placeholder' => 'Ingrese clasificación']) ?>

    <?php echo $form->field($model, 'asignatura_id')->textInput(['placeholder' => 'Ingrese asignatura ID']) ?>

    <?php echo $form->field($model, 'titulo')->textInput(['placeholder' => 'Ingrese título del libro']) ?>

    <?php echo $form->field($model, 'autor')->textInput(['placeholder' => 'Ingrese autor']) ?>

    <?php echo $form->field($model, 'editorial')->textInput(['placeholder' => 'Ingrese editorial']) ?>

    <?php echo $form->field($model, 'pais_codigopais')->textInput(['placeholder' => 'Ingrese código del país']) ?>

    <?php echo $form->field($model, 'anio_publicacion')->textInput(['placeholder' => 'Ingrese año de publicación']) ?>

    <?php echo $form->field($model, 'codigo_barras')->textInput(['placeholder' => 'Ingrese código de barras']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
