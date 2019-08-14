<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use kartik\widgets\SideNav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
          <?php $this->beginBody() ?>
        <div class="content">
            <div class='row'>
                <div class="col">
                    <?php
                    echo SideNav::widget([
                        'type' => SideNav::TYPE_DEFAULT,
                        'heading' => 'Options',
                        'items' => [
                            [
                                'url' => '#',
                                'label' => 'Home',
                                'icon' => 'home'
                            ],
                            [
                                'label' => 'Help',
                                'icon' => 'question-sign',
                                'items' => [
                                    ['label' => 'About', 'icon' => 'info-sign', 'url' => '#'],
                                    ['label' => 'Contact', 'icon' => 'phone', 'url' => '#'],
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
                <div class="col">
                  

                    <div class="wrap">
                        <?php
//                        NavBar::begin([
//                            'brandLabel' => Yii::$app->name,
//                            'brandUrl' => Yii::$app->homeUrl,
//                            'options' => [
//                                'class' => 'navbar-inverse navbar-fixed-top',
//                            ],
//                        ]);
//                        echo SideNav::widget([
//                            'options' => ['class' => 'navbar-nav navbar-right'],
//                            'items' => [
//                                ['label' => 'Balcão', 'url' => ['/venda/venda'],],
//                                ['label' => 'Vendas', 'url' => ['/venda']],
//                                //['label' => 'Home', 'url' => ['/site/index']],
//                                ['label' => 'Clientes', 'url' => ['/cliente']],
//                                ['label' => 'Comandas', 'url' => ['/comanda']],
//                                //['label' => 'Entrada', 'url' => ['/entrada']],
//                                // ['label' => 'Item Venda', 'url' => ['/item-venda']],
//                                // ['label' => 'Preço', 'url' => ['/preco']],
//                                ['label' => 'Produtos', 'url' => ['/produto']],
////                    [
////                        'label' => 'Suporte',
////                        'items' => [
////                            ['label' => 'Usuários', 'url' => ['/usuario']],                        
////                            ['label' => 'Papéis', 'url' => ['/papel']],
////                        ],
////                    ],
////                    Yii::$app->user->isGuest ? (
////                            ['label' => 'Login', 'url' => ['/site/login']]
////                            ) : (
////                            '<li>'
////                            . Html::beginForm(['/site/logout'], 'post')
////                            . Html::submitButton(
////                                    'Logout (' . Yii::$app->user->identity->username . ')',
////                                    ['class' => 'btn btn-link logout']
////                            )
////                            . Html::endForm()
////                            . '</li>'
////                            )
//                            ],
//                        ]);
//                        NavBar::end();
                        ?>

                        <div class="container">
                            <?=
                            Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ])
                            ?>
                            <?= Alert::widget() ?>
                            <?= $content ?>
                        </div>
                    </div>

                    <footer class="footer">
                        <div class="container">
                            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

                            <p class="pull-right"><?= Yii::powered() ?></p>
                        </div>
                    </footer>

                   
                </div>

            </div>
        </div>
 <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
