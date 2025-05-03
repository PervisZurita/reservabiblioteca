<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cambiar Contraseña';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-change-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Cambiar Contraseña', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>



    <?php if (Yii::$app->session->hasFlash('success')): ?>
    
    <?= \hail812\adminlte\widgets\Alert::widget([
        'type' => 'success',
        'title' => '¡Éxito!', 
        'body' => Yii::$app->session->getFlash('success'), // Mostrar mensaje de éxito
    ]) ?>
    <?php endif; ?>
    
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <?= \hail812\adminlte\widgets\Alert::widget([
        'type' => 'danger',
        'title' => 'Error', 
        'body' => Yii::$app->session->getFlash('error'), // Mostrar mensaje de error
    ]) ?>
     <?php endif; ?>
    
