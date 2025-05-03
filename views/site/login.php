<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;


$this->title = 'Iniciar Sesión | Biblioteca';
$this->registerCss("

@import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');
body::before {
    content: '';
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}
body {
    background-image: url('/img/5.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    margin: 0;
}

.registro-title {
    font-family: 'Raleway', sans-serif;
    font-size: 2em;
    font-weight: 700;
    color: #191970;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 20px 0;
}

.registro-form {
    text-align: center;
}
.registro-remember {
    text-align: left;
}

.registro-link {
    text-align: center;
    margin-top: 20px;
}

.registro-link a {
    color: #5EB400;
}

.registro-link a:hover {
    text-decoration: underline;
}
");


$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container container-md">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mx-auto">
                <div class="card-body">
                    <div class="registro-form">
                        <h1 class="registro-title">Iniciar Sesión</h1>
                        <p class="text-blue">Ingresa tus credenciales</p>
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                        ]); ?>

                        <div class="form-group">
                            <?= $form->field($model, 'username')->textInput([
                                'autofocus' => true,
                                'class' => 'form-control',
                                'placeholder' => 'Usuario',
                            ])->label(false) ?>
                        </div>

                        <div class="form-group">
                            <?= $form->field($model, 'password')->passwordInput([
                                'class' => 'form-control',
                                'placeholder' => 'Contraseña',
                            ])->label(false) ?>
                        </div>

                        <div class="registro-remember">
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                            ])->label('Recuérdame') ?>
                        </div>

                        <div class="form-group text-center">
                            <?= Html::submitButton('Iniciar Sesión', [
                                'class' => 'btn btn-primary btn-block btn-lg',
                            ]) ?>
                        </div>

                        <div class="form-group text-center">
                            <?= Html::a('¿Quieres formar parte de esta comunidad? ¡Únete!', ['site/signup'], [
                                'class' => 'btn-link', // Clase para dar estilo de enlace
                            ]) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <?php if (Yii::$app->session->hasFlash('success')) : ?>
                <?= \hail812\adminlte\widgets\Alert::widget([
                    'type' => 'success',
                    'icon' => 'fas fa-check', // Cambia el icono a un ícono de check (opcional)
                    'closeButton' => false, // Oculta el botón de cierre (opcional)
                    'options' => [
                        'class' => 'alert alert-success', // Agrega una clase CSS personalizada (opcional)
                    ],
                    'title' => '¡Éxito!',
                    'body' => Yii::$app->session->getFlash('success'), // Mostrar mensaje de éxito
                ]) ?>
            <?php endif; ?>

        </div>
    </div>
</div>