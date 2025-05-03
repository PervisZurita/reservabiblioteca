<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Biblioteca $model */

$this->title = 'Actualizar Biblioteca: ' . $model->idbiblioteca . '-' . $model->Campus ;
$this->params['breadcrumbs'][] = ['label' => 'Bibliotecas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idbiblioteca, 'url' => ['view', 'idbiblioteca' => $model->idbiblioteca]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="biblioteca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
