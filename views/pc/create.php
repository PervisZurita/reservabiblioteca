<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pc $model */

$this->title = 'Agregar Computador';
$this->params['breadcrumbs'][] = ['label' => 'Pcs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
