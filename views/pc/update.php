<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pc $model */

$this->title = 'Actualizar Computador: ' . $model->idpc;
$this->params['breadcrumbs'][] = ['label' => 'Pcs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idpc, 'url' => ['view', 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
