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
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("CREATE TABLE `fabrica`.`unidade_medida` ( `unidade_medida` VARCHAR(30) NOT NULL , `pk_unidade_medida` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`pk_unidade_medida`)) ENGINE = InnoDB;")->execute();
            echo('Tabela unidade de medida inserida com sucesso<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` DROP `unidade_medida`;")->execute();
            echo('Campo unidade de medida da tabela produto removido<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD COLUMN `fk_unidade_medida` INT NULL;")->execute();
            echo('Campo chave estrangeira unidade de medida inserido tabela produto removido<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD FOREIGN KEY (`fk_unidade_medida`) 
REFERENCES unidade_medida(pk_unidade_medida)
                          ON UPDATE CASCADE 
                          ON DELETE SET NULL;")->execute();
            echo('Campo chave estrangeira unidade de medida inserido ASSOCIADO A TABELA UNIDADE DE MEDIDA<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand(" ALTER TABLE `preco` ADD `codigo_barras` INT NULL AFTER `quantidade`;")->execute();
            echo('Campo codigo_barras inserido na tabela preco<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }

        try {
            $posts = Yii::$app->db->createCommand(" ALTER TABLE `produto` "
                            . "ADD `dt_vencimento` DATE AFTER `estoque`, "
                            . "ADD `dt_fabricacao` DATE  AFTER `dt_vencimento`, "
                            . "ADD `estoque_minimo` INT  AFTER `dt_fabricacao`, "
                            . "ADD `nr_lote` VARCHAR(20)  AFTER `quantidade_minima`, "
                            . "ADD `estoque_inicial` INT  AFTER `nr_lote`, "
                            . "ADD `is_vendavel` BOOLEAN  AFTER `quantidade_inicial`;")->execute();
            echo('Colunas dt_vencimento, dt_fabricacao, quantidade_minima, nr_lote, quantidade_inicial incluidos na tabela produto<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("  ALTER TABLE `venda` CHANGE `dt_venda` `dt_venda` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;")->execute();
            echo('Data da venda é TIMESTAMP automático agora<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `venda` CHANGE `dt_pagamento` `dt_pagamento` DATETIME NULL DEFAULT NULL;")->execute();
            echo('dt_pagamento agora com hora<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
    }

       public function actionUpdate2() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $posts = Yii::$app->db->createCommand(' ALTER TABLE `produto` CHANGE `estoque_minimo` `estoque_minimo` FLOAT(11) NULL DEFAULT NULL;')->execute();
            ECHO($posts . ' linhas inseridas<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        
         try {
            $posts = Yii::$app->db->createCommand('  ALTER TABLE `produto` CHANGE `estoque_inicial` `estoque_inicial` FLOAT(11) NULL DEFAULT NULL;')->execute();
            ECHO($posts . ' linhas inseridas<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage().'<br>';
        }
        
    }
   
      
        
}
