<?php

namespace app\controllers;

use Yii;
use app\models\Preco;
use app\models\PrecoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrecoController implements the CRUD actions for Preco model.
 */
class UpdateController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionUpdate1() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $posts = Yii::$app->db->createCommand('INSERT INTO `comanda`(`numero`) VALUES (1000000001),(1000000002),(1000000003),(1000000004),(1000000005),(1000000006),(1000000007),(1000000008),(1000000009),(1000000010),(1000000011),(1000000012),(1000000013),(1000000014),(1000000015),(1000000016),(1000000017),(1000000018),(1000000019),(1000000020),(10000000212),(100000022),(1000000023),(1000000024),(1000000025),(1000000026),(1000000027),(1000000028),(1000000029),(1000000030),(1000000031),(1000000032),(1000000033),(1000000034),(1000000035),(1000000036),(1000000037),(1000000038),(1000000039),(1000000040), (1000000041),(10000000),(1000000042),(1000000043),(1000000044),(1000000045),(1000000046),(1000000047),(1000000048),(1000000049),(1000000050)')->execute();
            ECHO($posts . ' linhas inseridas<br>');
            $posts = Yii::$app->db->createCommand("CREATE TABLE `fabrica`.`unidade_medida` ( `unidade_medida` VARCHAR(30) NOT NULL , `pk_unidade_medida` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`pk_unidade_medida`)) ENGINE = InnoDB;")->execute();
            echo('Tabela unidade de medida inserida com sucesso<br>');
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` DROP `unidade_medida`;")->execute();
            echo('Campo unidade de medida da tabela produto removido<br>');
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD COLUMN `fk_unidade_medida` INT NULL;")->execute();
            echo('Campo chave estrangeira unidade de medida inserido tabela produto removido<br>');
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD FOREIGN KEY (`fk_unidade_medida`) 
REFERENCES unidade_medida(pk_unidade_medida)
                          ON UPDATE CASCADE 
                          ON DELETE SET NULL;")->execute();
            echo('Campo chave estrangeira unidade de medida inserido ASSOCIADO A TABELA UNIDADE DE MEDIDA<BR>');

            echo 'FIM';
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}
