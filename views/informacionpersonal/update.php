<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Informacionpersonal $model */

$this->title = 'Actualizar Estudiante: ' . $model->CIInfPer;
$this->params['breadcrumbs'][] = ['label' => 'Informacionpersonals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CIInfPer, 'url' => ['view', 'CIInfPer' => $model->CIInfPer]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="informacionpersonal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
