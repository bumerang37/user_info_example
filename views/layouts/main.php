<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

//    TODO:: Сделать более дружелюбным профиль, добавить лого
    $user_profile = <<<HTML
<li>
<div class="dropdown text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle show" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="true">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small show" aria-labelledby="dropdownUser1" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 34px);" data-popper-placement="bottom-start">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
</li>
HTML;

    if (!Yii::$app->user->isGuest) {
        $items[]=['label' => 'Настройки', 'url' => ['/settings']];
}
    if (Yii::$app->user->isGuest) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Пользователи', 'url' => ['user/index']],
                ['label' => 'Изменить профиль ', 'url' => ['user/edit-profile']],
                ['label' => 'Войти', 'url' => ['/site/login']],
                ['label' => 'Зарегистрироваться', 'url' => ['/user/register']],

            ]
        ]);
    } else {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Пользователи', 'url' => ['user/index']],
                ['label' => 'Изменить профиль ', 'url' => ['user/edit-profile']],


                (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                ),

            ],
        ]);
    }
    NavBar::end();
    ?>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; My Company <?= date('Y') ?></p>
            <p class="float-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
