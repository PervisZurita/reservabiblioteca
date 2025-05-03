<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Personaldata $model */

$this->title = 'Agregar Datos Personales';
$this->params['breadcrumbs'][] = ['label' => 'Personaldatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personaldata-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'nivel' => $model->nivel,
    ]) ?>

</div>
