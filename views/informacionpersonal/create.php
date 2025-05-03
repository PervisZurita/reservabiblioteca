<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Informacionpersonal $model */

$this->title = 'Ingresar Estudiante';
$this->params['breadcrumbs'][] = ['label' => 'Informacionpersonals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacionpersonal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
