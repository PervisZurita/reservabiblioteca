<!-- Navbar -->
<?php

use yii\helpers\Html;
use yii\helpers\Url;

$userType = Yii::$app->user->identity->tipo_usuario ?? null;
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="/site/index" class="nav-link d-flex align-items-center">
                <img src="<?= Yii::$app->urlManager->baseUrl ?>/img/ESCUDETO_UTE-LVT.png" 
                     alt="Universidad Luis Vargas Torres" 
                     class="img-fluid rounded-circle mr-2" 
                     style="height: 35px;">
                <span class="font-weight-bold text-dark">UTELVT | Biblioteca</span>
            </a>
        </li>

        <?php if ($userType === 21 || $userType === 7 || $userType === 8): ?>
            <li class="nav-item">
                <a href="<?= Url::to(['/user/index']) ?>" class="nav-link">
                    <i class="fas fa-users"></i>&nbsp;Usuarios
                </a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="circulacionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book"></i>&nbsp;Circulación
                </a>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="circulacionDropdown">
                    <li><a href="<?= Url::to(['/libro/index']) ?>" class="dropdown-item">Catálogo de Libros</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="<?= Url::to(['/pc/index']) ?>" class="dropdown-item">Computadoras</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="<?= Url::to(['/tesis/index']) ?>" class="dropdown-item">Catálogo de Tesis</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="repositorioDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-archive"></i>&nbsp;Repositorio
                </a>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="repositorioDropdown">
                    <li><a href="<?= Url::to(['/tesis/index']) ?>" class="dropdown-item">Catálogo de Tesis</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="prestamoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-credit-card"></i>&nbsp;Préstamo
                </a>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="prestamoDropdown">
                    <li><a href="<?= Url::to(['/prestamo/create']) ?>" class="dropdown-item">Préstamo</a></li>
                    <li><a href="<?= Url::to(['/prestamo/estadisticalibro']) ?>" class="dropdown-item">Generador de Estadística</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="horariosDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-clock"></i>&nbsp;Horarios
            </a>
            <ul class="dropdown-menu border-0 shadow" aria-labelledby="horariosDropdown">
                <li><a href="<?= Url::to(['/biblioteca/view?idbiblioteca=1']) ?>" class="dropdown-item">Esmeraldas</a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?= Url::to(['/biblioteca/view?idbiblioteca=3']) ?>" class="dropdown-item">Mútile</a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?= Url::to(['/biblioteca/view?idbiblioteca=2']) ?>" class="dropdown-item">La Concordia</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="<?= Url::to(['/site/about']) ?>" class="nav-link">Acerca de Nosotros</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php
                $cacheKey = 'user_' . Yii::$app->user->id;
                $userData = Yii::$app->cache->get($cacheKey);
                if ($userData === false) {
                    $userData = Yii::$app->user->identity;
                    Yii::$app->cache->set($cacheKey, $userData, 3600);
                }

                $nombre = '';
                $apellido = '';
                $url = ['#'];
                $personalData = $userData->personaldata;
                if ($personalData !== null) {
                    $nombre = $personalData->Nombres;
                    $apellido = $personalData->Apellidos;
                    $url = ['/personaldata/view', 'Ci' => $personalData->Ci];
                }
                $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1));
                ?>
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-initials bg-light text-dark rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                        <?= $iniciales ?>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-right shadow rounded" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="<?= Url::to($url) ?>">Editar Datos</a></li>
                    <li class="dropdown-divider"></li>
                    <li><?= Html::a('Cerrar Sesión', ['/site/logout'], ['data-method' => 'post', 'class' => 'dropdown-item']) ?></li>
                </ul>
            <?php else: ?>
                <div class="user-initials bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                    ¿?
                </div>
            <?php endif; ?>
        </li>
    </ul>
</nav>
