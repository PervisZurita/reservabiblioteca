<?php



use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Libro $model */

// Título de la página con el título del libro
$this->title = Yii::t('app', 'Actualizar Libro: {name}', [
    'name' => $model->titulo,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Libros'), 'url' => ['index']];
// Mostrar el título también en el breadcrumb
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');

?>
<div class="libro-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
