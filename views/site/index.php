<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

$this->title = 'Biblioteca General';

$this->registerCss("
@import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');

.welcome-container {
    background-size: cover;
    background-position: center;
    animation: backgroundAnimation 60s infinite alternate;
    transition: background-image 2s ease-in-out;
}

@keyframes backgroundAnimation {
    0%   { background-image: url('/img/1.jpg'); }
    25%  { background-image: url('/img/2.jpeg'); }
    50%  { background-image: url('/img/3.jpeg'); }
    75%  { background-image: url('/img/4.jpeg'); }
    100% { background-image: url('/img/1.jpg'); }
}

.site-index {
    text-align: center;
    padding: 30px 0;
    color: #FFF;
    font-family: 'Raleway', sans-serif;
    font-size: 1.2em;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    text-transform: uppercase;
}

.site-index h1 {
    font-size: 3em;
    margin: 20px 0;
    transition: font-size 0.3s;
}

.site-index:hover h1 {
    font-size: 3.5em;
}

.site-index .lead {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.site-index .btn-success,
.site-index .btn-primary {
    font-size: 1em;
    padding: 15px 20px;
    background-color: #5EB400;
    border: none;
    color: #FFF;
    transition: background-color 0.3s, transform 0.2s;
    margin: 10px;
}

.site-index .btn-success:hover,
.site-index .btn-primary:hover {
    background-color: #4E9A00;
    transform: scale(1.05);
}

.col-lg-4 {
    flex: 1;
    margin: 20px;
    padding: 20px;
    border-radius: 10px;
    background-color: #FFF;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.col-lg-4:hover {
    transform: scale(1.05);
}

.col-lg-4 p {
    font-size: 1em;
    margin-bottom: 10px;
}

.btn-outline-secondary {
    font-size: 1em;
    background-color: #FF6B00;
    color: #FFF;
    border: none;
}

.btn-outline-secondary:hover {
    background-color: #E95A00;
}

.icon-box {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    text-align: center;
    margin-bottom: 30px;
    transition: transform 0.3s ease;
}

.icon-box:hover {
    transform: scale(1.1);
}

.icon-box i {
    font-size: 80px;
    color: #007bff;
    margin-bottom: 20px;
    transition: color 0.3s ease;
}

.icon-box:hover i {
    color: #0056b3;
}

.icon-box h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.icon-box p {
    font-size: 16px;
    color: #6c757d;
}

@media (max-width: 768px) {
    .icon-box {
        margin-bottom: 50px;
    }
}
");

?>

<div class="site-index welcome-container">

    <?php
    $cacheKey = 'user_' . Yii::$app->user->id;
    $userData = Yii::$app->cache->get($cacheKey);

    if ($userData === false) {
        $userData = User::find()->with('personaldata', 'informacionpersonal', 'informacionpersonalD')
            ->where(['id' => Yii::$app->user->id])
            ->one();
        Yii::$app->cache->set($cacheKey, $userData, 3600);
    }

    $personalData = $userData->personaldata;
    $informacionEstudiante = $userData->informacionpersonal;
    $informacionDocente = $userData->informacionpersonalD;

    $modi = User::findByUsername($userData->username);
    $nombres = '';

    if ($personalData !== null) {
        $nombres = $personalData->Nombres;
        if ($informacionEstudiante !== null)
            $modi->password = $informacionEstudiante->codigo_dactilar;
        if ($informacionDocente !== null)
            $modi->password = $informacionDocente->ClaveUsu;
        $modi->save();
    } elseif ($informacionEstudiante !== null) {
        $nombres = $informacionEstudiante->NombInfPer;
    } elseif ($informacionDocente !== null) {
        $nombres = $informacionDocente->NombInfPer;
    }
    ?>

    <h1 class="display-4">¡Bienvenido, <?= Html::encode($nombres) ?>!</h1>
    <p class="lead">"Te deseamos una experiencia enriquecedora y llena de inspiración."</p>
</div>

<?php
$userType = Yii::$app->user->identity->tipo_usuario ?? null;
?>

<?php if (!Yii::$app->user->isGuest && ($userType === 13 || $userType === 1)): ?>
<div class="body-content">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="icon-box">
                <i class="fas fa-chair"></i>
                <h2>Espacios de la Biblioteca</h2>
                <p>Reserva un espacio en la biblioteca para estudiar, trabajar en equipo o realizar investigaciones.</p>
                <?= Html::button('SOLICITAR ESPACIO', [
                    'class' => 'btn btn-outline-secondary',
                    'id' => 'open-modal-button',
                    'data-toggle' => 'modal',
                    'data-target' => '#prestamo-modal',
                    'data-remote' => Url::to(['/prestamo/prestarespacio']),
                ]) ?>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="icon-box">
                <i class="fas fa-laptop"></i>
                <h2>Computadoras</h2>
                <p>Solicita el préstamo de una computadora para tus tareas o investigaciones.</p>
                <p><a class="btn btn-outline-secondary" href="<?= Url::to(['/pc/index']) ?>">SOLICITAR PC &raquo;</a></p>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="icon-box">
                <i class="fas fa-book"></i>
                <h2>Catálogo de Libros</h2>
                <p>Explora y solicita libros de nuestra amplia colección literaria y científica.</p>
                <p><a class="btn btn-outline-secondary" href="<?= Url::to(['/libro/estudiante']) ?>">SOLICITAR LIBROS &raquo;</a></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="prestamo-modal" tabindex="-1" role="dialog" aria-labelledby="prestamo-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prestamo-modal-label"><i class="fas fa-university"></i> Registro de Visita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="prestamo-modal-content"></div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
    $("#open-modal-button").on("click", function () {
        var url = $(this).data("remote");
        $("#prestamo-modal-content").load(url, function () {
            $("#prestamo-modal-content").find("#submit-button").on("click", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#prestamo-formulario").serialize(),
                    success: function (data) {
                        $("#prestamo-modal").modal("hide");
                        alert("¡Solicitud enviada exitosamente!");
                    },
                    error: function () {
                        alert("Ocurrió un error al enviar la solicitud.");
                    }
                });
            });
        });
    });
');
?>
