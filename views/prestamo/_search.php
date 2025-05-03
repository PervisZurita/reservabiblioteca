<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PrestamoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prestamo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <div class="row ">
        <div class="col-md-6">
            <?= $form->field($model, 'id')->textInput(['style' => 'width: 100%;', 'placeholder' => 'N° Inicial del Formulario'])->label('Código de Préstamo') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cedula_solicitante')->textInput(['style' => 'width: 100%;', 'placeholder' => 'Ingrese N° de Cédula'])->label('Cédula Solicitante') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fecha_solicitud')->input('date') ?>

        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tipoprestamo_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Tipoprestamo::find()->all(), 'id', 'nombre_tipo'),
                ['prompt' => 'Seleccione Tipo de Solicitud']
            ) ?> </div>


    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'biblioteca_idbiblioteca')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                ['prompt' => 'Seleccione un Campus']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'libro_id')->textInput(['style' => 'width: 100%;', 'placeholder' => 'Código de Barras'])->label('Título Solicitado') ?>

        </div>
    </div>
    <div class="row">

        <div class="col-md-6">

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'pc_idpc')->textInput(['style' => 'width: 100%;', 'placeholder' => 'Nombre del Computador'])->label('Computador Solicitado') ?>
        </div>
    </div>


    <?php // $form->field($model, 'fechaentrega') 
    ?>

    <?php // echo $form->field($model, 'pc_biblioteca_idbiblioteca') 
    ?>
    <?php // echo $form->field($model, 'libro_biblioteca_idbiblioteca') 
    ?>


    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-search"></i> Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Restablecer', ['class' => 'btn btn-outline-secondary', 'id' => 'reset-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<< JS
document.getElementById('reset-button').addEventListener('click', function() {
    var inputs = document.querySelectorAll('input[type="text"], input[type="date"], select'); // Obtener todos los campos de filtro
    inputs.forEach(function(input) {
        input.value = ''; // Limpiar los valores
    });

    // Enviar el formulario después de restablecer los campos
    document.querySelector('form').submit();
});
JS;
$this->registerJs($js);
?>