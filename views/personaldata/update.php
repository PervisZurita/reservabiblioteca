<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Personaldata $model */

$this->title = 'Actualizar Datos Personales';
$this->params['breadcrumbs'][] = ['label' => 'Personaldatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Ci, 'url' => ['view', 'Ci' => $model->Ci]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="personaldata-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'nivel' => $model->niveles,
    ]) ?>

</div>
