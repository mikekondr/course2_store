<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);

    $classifiers = [
        [
            'label' => Yii::t('app/users', 'Users'),
            'url' => ['/users/index'],
            'visible' => Yii::$app->user->can('manageUsers')
        ],
        [
            'label' => Yii::t('app/goods', 'Goods'),
            'url' => ['/goods/index'],
            'visible' => Yii::$app->user->can('viewClassifiers'),
        ],
        [
            'label' => Yii::t('app/categories', 'Categories'),
            'url' => ['/categories/index'],
            'visible' => Yii::$app->user->can('viewClassifiers'),
        ]
    ];

    $goods_moves = [
        [
            'label' => Yii::t('app/docs', 'Documents'),
            'url' => ['/documents/index'],
            'visible' => Yii::$app->user->can('viewOperations'),
        ],
        [
            'label' => Yii::t('app', 'Goods circulation'),
            'url' => ['/remains/circulation'],
            'visible' => Yii::$app->user->can('viewRemains'),
        ]
    ];

    $reports = [
        [
            'label' => Yii::t('app/goods', 'Remains'),
            'url' => ['/remains/remains'],
            'visible' => Yii::$app->user->can('viewRemains'),
        ]
    ];

    $menuItems = [
        //['label' => 'Home', 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'Classifiers'), 'items' => $classifiers, 'visible' => in_array(true, array_column($classifiers, 'visible'))],
        ['label' => Yii::t('app', 'Goods circulation'), 'items' => $goods_moves, 'visible' => in_array(true, array_column($goods_moves, 'visible'))],
        ['label' => Yii::t('app', 'Reports'), 'items' => $reports, 'visible' => in_array(true, array_column($reports, 'visible'))],
        ['label' => 'About', 'url' => ['/site/about'], 'visible' => Yii::$app->user->can('editOrders')],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];

    $menuItems[] = Yii::$app->user->isGuest
        ? ['label' => Yii::t('app','Login'), 'url' => ['/site/login']]
        : '<li class="nav-item ms-auto">'
        . Html::beginForm(['/site/logout'])
        . Html::submitButton(
            Yii::t('app','Logout') . ' (' . Yii::$app->user->identity->username . ')',
            ['class' => 'nav-link btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Mike <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
