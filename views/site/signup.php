<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Registro';

$this->registerCss("
@import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');

body {
    background-image: url('/img/3.jpeg'); /* Reemplaza con la ruta real de tu imagen de fondo */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.registro-form {
    max-width: 600px;
    margin: 10px auto; /* Reducimos el margen superior */
    padding: 20px;
    background-color: #f8f8ff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.registro-form label {
    font-weight: 700;
}

.registro-form .form-group {
    margin-bottom: 20px;
}

.registro-form .btn-primary {
    background-color: #5EB400;
    color: #FFF;
    padding: 15px 20px;
    border: none;
}

.registro-form .btn-primary:hover {
    background-color: #4E9A00;
}

/* Estilo adicional para el título */
.registro-title {
    font-size: 2em;
    margin: 10px 0; /* Reducimos el margen superior */
    color: #191970;
    font-family: 'Raleway', sans-serif;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-align: center;
}
");

?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
<?= \hail812\adminlte\widgets\Alert::widget([
    'type' => 'danger',
    'title' => 'Error', 
    'body' => Yii::$app->session->getFlash('error'),
]) ?>
<?php endif; ?>

<div class="registro-form">
    <h1 class="registro-title"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'Ci')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Apellidos')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Nombres')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'FechaNacimiento')->input('date') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Genero')->dropDownList(['M' => 'Masculino', 'F' => 'Femenino'], ['prompt' => 'Seleccione su género']) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Institucion')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'Nivel')->dropDownList($niveles, ['prompt' => 'Seleccione su Nivel Académico']) ?>
    </div>

    <div class="form-group">
        <button id="submitButton" class="btn btn-primary btn-block">Iniciar Registro</button>
    </div>

    <?php ActiveForm::end(); ?>

    <script>
        document.getElementById("submitButton").addEventListener("click", function(event) {
            if (!confirm("¿Estás seguro de que deseas unirte a esta comunidad?")) {
                event.preventDefault();
            }
        });
    </script>
</div>