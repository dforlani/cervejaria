<?php

namespace app\controllers;

use app\models\Configuracao;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("CREATE TABLE `fabrica`.`unidade_medida` ( `unidade_medida` VARCHAR(30) NOT NULL , `pk_unidade_medida` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`pk_unidade_medida`)) ENGINE = InnoDB;")->execute();
            echo('Tabela unidade de medida inserida com sucesso<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` DROP `unidade_medida`;")->execute();
            echo('Campo unidade de medida da tabela produto removido<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD COLUMN `fk_unidade_medida` INT NULL;")->execute();
            echo('Campo chave estrangeira unidade de medida inserido tabela produto removido<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `produto` ADD FOREIGN KEY (`fk_unidade_medida`) 
REFERENCES unidade_medida(pk_unidade_medida)
                          ON UPDATE CASCADE 
                          ON DELETE SET NULL;")->execute();
            echo('Campo chave estran4geira unidade de medida inserido ASSOCIADO A TABELA UNIDADE DE MEDIDA<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand(" ALTER TABLE `preco` ADD `codigo_barras` INT NULL AFTER `quantidade`;")->execute();
            echo('Campo codigo_barras inserido na tabela preco<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
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
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("  ALTER TABLE `venda` CHANGE `dt_venda` `dt_venda` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;")->execute();
            echo('Data da venda é TIMESTAMP automático agora<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
        try {
            $posts = Yii::$app->db->createCommand("ALTER TABLE `venda` CHANGE `dt_pagamento` `dt_pagamento` DATETIME NULL DEFAULT NULL;")->execute();
            echo('dt_pagamento agora com hora<BR>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
    }

    public function actionUpdate2() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $posts = Yii::$app->db->createCommand(' ALTER TABLE `produto` CHANGE `estoque_minimo` `estoque_minimo` FLOAT(11) NULL DEFAULT NULL;')->execute();
            ECHO($posts . ' linhas inseridas<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }

        try {
            $posts = Yii::$app->db->createCommand('  ALTER TABLE `produto` CHANGE `estoque_inicial` `estoque_inicial` FLOAT(11) NULL DEFAULT NULL;')->execute();
            ECHO($posts . ' linhas inseridas<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
    }

    public function actionUpdate3() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $posts = Yii::$app->db->createCommand('   ALTER TABLE `produto` CHANGE `estoque` `estoque_vendido` FLOAT NULL DEFAULT "0";')->execute();
            ECHO(' Alterado para ter coluna estoque_vendido<br>');
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage() . '<br>';
        }
    }

    public function actionAtualizar() {
        echo chdir('..') . "<br>"; //retorna para o diretório raiz
        echo "Executando em: " . getcwd() . "<br>";
        echo System('git reset --hard') . "<br>";
        echo "<br>Passou pelo: git reset --hard<br>";
        echo System('git pull') . "<br>";
        echo "<br>Passou pelo: git pull<br>";
        echo System('composer install') . "<br>";
        echo "<br>Passou pelo: composer install<br>";
        echo "<br><br><b>Não se esqueça de utilizar o UPDATE BANCO</b><br>";
    }

    /**
     * Cria a tabela de configurações
     */
    function actionAtualizarBanco() {
        $existe = Yii::$app->db->createCommand('SHOW TABLES LIKE "configuracao";')->execute();
        if (!$existe) {


            try {
                echo '<br>Tabela de configuracao não existe. Criando:';
                $posts = Yii::$app->db->createCommand(""
                                . "CREATE TABLE `fabrica`.`configuracao` ( "
                                . "`pk_configuracao` INT NOT NULL AUTO_INCREMENT , "
                                . "`tipo` VARCHAR(30) NOT NULL , "
                                . "`valor`  VARCHAR(30) NOT NULL , "
                                . "PRIMARY KEY (`pk_configuracao`)) ENGINE = InnoDB;")->execute();
                echo('<br>Tabela de configuração criada<br>');
            } catch (\Exception $e) {

                echo $e->getMessage() . '<br>';
            } catch (Throwable $e) {

                echo $e->getMessage() . '<br>';
            }
        }

        $this->atualizaBanco("ALTER TABLE `preco` CHANGE `codigo_barras` `codigo_barras` CHAR(12) NULL DEFAULT NULL;", "preco_char", "Codigo Barras CHAR 12");
        $this->atualizaBanco("update preco set codigo_barras =  LPAD(codigo_barras,12,'0') where codigo_barras is not null;", "preco_complete", "Completando com 0 no código de barras");

        $this->atualizaBanco("ALTER TABLE `comanda` CHANGE `numero` `numero` CHAR(13) NULL DEFAULT NULL;", "comanda_char_13", "Comanda com CHAR 13");
        $this->atualizaBanco("update comanda set numero =  LPAD(numero,12,'0') where numero is not null;", "comanda_complete", "Completando com 0 nas comandas");

        $this->atualizaBanco("ALTER TABLE `produto` ADD `custo_compra_producao` DECIMAL(10,2) NULL ;", "alter_produto_add_custo_compra_producao", "Inserido coluna custo de produção");

        //04-09-2019
        $this->atualizaBanco("create table caixa(
            pk_caixa int not null AUTO_INCREMENT,
    fk_venda int,
    valor decimal(10,2),
    tipo enum('Abertura de Caixa', 'Pagamento', 'Sangria'),
    dt_movimento TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (pk_caixa),
    FOREIGN KEY(fk_venda)
    REFERENCES Venda(pk_venda)
    ON UPDATE CASCADE 
    ON DELETE CASCADE);", "create_caixa", 'Tabela Caixa criada com sucesso');
        
        //11-09-2019
        $this->atualizaBanco("ALTER TABLE `venda` ADD `valor_pago_credito` DECIMAL(10,2) NOT NULL AFTER `valor_total`, ADD `valor_pago_debito` DECIMAL(10,2) NOT NULL AFTER `valor_pago_credito`, ADD `valor_pago_dinheiro` DECIMAL(10,2) NOT NULL AFTER `valor_pago_debito`;",
                "update_venda_pgtos", "Inserido campos pra diferentes formas de pagamento: credito, debito, dinheiro");
        
        //12-09-2019 Gerar PDF de todas as vendas
        $this->atualizaBanco("INSERT INTO CONFIGURACAO (tipo, valor) VALUES ('pdf_todas_paginas', 1);",
                "log_pdf_todas_paginas", 'Inicialização da configuração para gerar pdf de todas as vendas criada');
        
        //12-09-2019 aumentar tamanho das colunas de configuracao
        $this->atualizaBanco("ALTER TABLE `configuracao` CHANGE `tipo` `tipo` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        ALTER TABLE `configuracao` CHANGE `valor` `valor` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;",
                "aumenta_tamanho_colunas_conf", 'Tamanho das colunas de configuração aumentadas');
        
        //12-09-2019 Gerar PDF de todas as vendas
        $this->atualizaBanco("INSERT INTO CONFIGURACAO (tipo, valor) VALUES ('path_pdf_todas_paginas', '../pdf/vendas/');", "log_path_pdf_todas_paginas", 'Inicialização de local pra gerar os PDFs');
                
        //13-09-2019 Incluir tap list        
        $this->atualizaBanco("ALTER TABLE `preco` ADD `is_tap_list` BOOLEAN NOT NULL DEFAULT FALSE;",
                "alter_add_tap_list", "Inserido campo tap list em preço");


        echo 'Atualização do banco encerrada<br>';
        
            }

    public function atualizaBanco($comando, $nome_configuracao, $mensagem) {
        //busca de a tualização do banco já foi inserida
        $conf = Configuracao::findOne(['tipo' => $nome_configuracao]);
        if (empty($conf)) {
            try {
                $posts = Yii::$app->db->createCommand($comando)->execute();
                echo("<br>$mensagem<br>");
                $conf = new Configuracao();
                $conf->tipo = $nome_configuracao;
                $conf->valor = "Atualização banco";
                $conf->save();
            } catch (\Exception $e) {

                echo $e->getMessage() . '<br>';
            } catch (Throwable $e) {

                echo $e->getMessage() . '<br>';
            }
        } else {
            echo 'Atualização já realizada anteriormente<br>';
        }
    }

}
