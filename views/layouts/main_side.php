<?php
/* @var $this View */
/* @var $content string */

use kartik\widgets\SideNav;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?php
echo Yii::$app->language;
?>
      " xmlns="http://www.w3.org/1999/html">

    <head>
        <meta charset="<?php
        echo Yii::$app->charset;
        ?>
              ">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        echo Html::csrfMetaTags();
        ?>
        <title>Новый формат Интерне-магазина!!!</title>
        <?php
        $this->head();
        ?>
    </head>
    <body>
        <?php
        $this->beginBody();
        ?>




        <div class="content">
            <div class="container">                
                <div class="row">
                    <div class="col-md-2">
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
                    <div class="col-md-10">
                        <div class="panel panel-default">
                          
                            <?=
                            Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ])
                            ?>
                            <?= Alert::widget() ?>
                            <?= $content ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <?php
        $this->endBody();
        ?>
    </body>
</html>
<?php
$this->endPage();
