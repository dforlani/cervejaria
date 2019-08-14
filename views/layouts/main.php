<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use kartik\widgets\AlertBlock;
use kartik\widgets\SideNav;
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
            <div class="container">                
                <div class="row">
                    <div class="col-md-2">
                        <?php
                        echo SideNav::widget([
                            'type' => SideNav::TYPE_DEFAULT,
                            'heading' => 'Menu',
                            'items' => [
                                ['label' => 'Balcão', 'url' => ['/venda/venda'],],
                                ['label' => 'Todas as Vendas', 'url' => ['/venda']],
                                //['label' => 'Home', 'url' => ['/site/index']],
                                ['label' => 'Clientes', 'url' => ['/cliente']],
                                ['label' => 'Comandas', 'url' => ['/comanda']],
                                //['label' => 'Entrada', 'url' => ['/entrada']],
                                // ['label' => 'Item Venda', 'url' => ['/item-venda']],
                                // ['label' => 'Preço', 'url' => ['/preco']],
                                ['label' => 'Produtos', 'url' => ['/produto']],
                                ['label' => 'Unidades de Medida', 'url' => ['/unidade-medida']],
                                ['label' => '1) Atualizar', 'url' => ['/update/atualizar']],
                                 ['label' => '2) Atualizar Banco3', 'url' => ['/update/atualizar-banco']],
                            ],]
                        );
                        ?>

                    </div>
                    <div class="col-md-10">
                        <div class="panel panel-default">

                            <?=
                            Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ])
                            ?>
                            <?=
                            AlertBlock::widget([
                                'useSessionFlash' => true,
                                'type' => AlertBlock::TYPE_ALERT,
                                'delay' => false,
                            ]);
                            ?>
                            <?php // Alert::widget() ?>
                            <?= $content ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
