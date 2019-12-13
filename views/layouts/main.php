<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use kartik\widgets\AlertBlock;
use kartik\widgets\SideNav;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
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
    <script>
        //timer para informar ao sistema a passagem do tempo. Sempre que passar o tempo pré-definido nas configurações, o sistema irá realizar um backup automático
        var counter = 0;
        //efetua uma chamada na tela de backup para avisá-la de que passou 1 minuto
        function backupAutomatico() {
            var timer = setTimeout(function () {

                var url = "<?= Url::toRoute(['configuracao/backup-automatico']); ?>";
                data = null;
                $.getJSON(url, data,
                        function (dataResposta, textStatus, jqXHR) {

                        }
                );
                //efetua esses aviso por 5 vezes, espara-se que a cada troca de tela isso seja repetido
                if (counter < 3) {
                    backupAutomatico();
                }
            }, 1000 * 60);//1 minuto
        }




        backupAutomatico();



        $(document).ready(function () {
            /**
             * Busca os pedidos esperando a cada 15 segundos
             * @returns {undefined}
             */
            function conferePedidosEsperando() {
                
                var timer = setTimeout(function () {
                    $.post("<?= Url::to(['pedidoapp/pedidos-esperando']); ?>", {'_csrf': '<?= Yii::$app->request->csrfToken ?>'})
                            .done(function (data) {
                                $('#divPedidos').html(data);
                            });
                    conferePedidosEsperando();
                }, 1000 * 15); //15 segundos

            }
            conferePedidosEsperando();
        });



    </script>
    <style>
        .container {width:1400px;margin:0 auto;}
    </style>
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
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="b">{icon}<u>B</u>alcão{label}</a>', 'url' => ['/venda/venda'],],
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="h">{icon}Fol<u>h</u>a de Vendas{label}</a>', 'url' => ['/venda/folha'],],
                                ['label' => 'Caixa',
                                    'items' => [
                                        ['label' => 'Aberto', 'url' => ['/caixa/index']],
                                        ['label' => 'Fechados', 'url' => ['/caixa/fechados']]
                                    ]
                                ],
                                ['label' => 'Tap List', 'url' => ['/produto/tap-list']],
                                ['options' => ['style' => 'background-color:#ddd;margin-top: 0px;']],
                                ['label' => 'Todas as Vendas', 'url' => ['/venda']],
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="t">{icon}Clien<u>t</u>es{label}</a>', 'url' => ['/cliente']],
                                ['label' => 'Comandas', 'url' => ['/comanda']],
                                ['label' => 'Produtos', 'url' => ['/produto']],
                                ['label' => 'Unidades de Medida', 'url' => ['/unidade-medida']],
                                ['options' => ['style' => 'background-color:#ddd;margin-top: 0px;']],
                                ['label' => 'Relatórios', 'items' => [['label' => 'Vendas', 'url' => ['/relatorio/vendas']]]],
                                ['label' => 'Graficos', 'items' => [['label' => 'Vendas', 'url' => ['/grafico/vendas']]]],
                                ['options' => ['style' => 'background-color:#ddd;margin-top: 0px;']],
                                ['label' => 'Configuração', 'url' => ['/configuracao']],
                                ['label' => 'Backup', 'url' => ['/configuracao/backup']],
                                ['options' => ['style' => 'background-color:#ddd;margin-top: 0px;']],
                                ['label' => '1) Atualizar', 'template' => '<a href="{url}"  target="_blank">{icon}{label}</a>', 'url' => ['/update/atualizar']],
                                ['label' => '2) Atualizar Banco', 'template' => '<a href="{url}"  target="_blank">{icon}{label}</a>', 'url' => ['/update/atualizar-banco']],
                                ['label' => 'Versões', 'template' => '<a href="{url}"  target="_blank">{icon}{label}</a>', 'url' => ['/update/versoes']],
                            ],]
                        );
                        ?>

                    </div>
                    <div class="col-md-8">
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
                                'delay' => 10000,
                                    ]
                            );
                            ?>

                            <?= $content ?>

                        </div>
                    </div>
                    <!--Local que serão inseridos os pedidos por AJAX-->
                    <div id='divPedidos'  class="col-md-2">

                    </div>
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

