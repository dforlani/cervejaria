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

    public function actionVersoes() {

        if (isset($_GET['versao'])) {
            shell_exec('git checkout ' . $_GET['versao']);
        }


        //recebe as informações do GIT e inverte a ordem, para mostrar primeiro os mais atuais
        $output = nl2br(shell_exec('git tag'));
        $versoes = array_reverse(explode('<br />', $output));

        //remove o primeiro elemento que costuma estar em branco
        if (trim($versoes[0]) == "")
            array_shift($versoes);

        //remove versões que ainda não tinham o sistema de troca de versões
        foreach ($versoes as $index => $versao) {
            if (strpos($versao, 'v1.2.3.1')) {
                unset($versoes[$index]);
            } elseif ($versao == 'v1.2.3') {
                unset($versoes[$index]);
            }
        }

        return $this->render('versoes', ['versoes' => $versoes]);
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
        $this->atualizaBanco("ALTER TABLE `venda` ADD `valor_pago_credito` DECIMAL(10,2) NOT NULL AFTER `valor_total`, ADD `valor_pago_debito` DECIMAL(10,2) NOT NULL AFTER `valor_pago_credito`, ADD `valor_pago_dinheiro` DECIMAL(10,2) NOT NULL AFTER `valor_pago_debito`;", "update_venda_pgtos", "Inserido campos pra diferentes formas de pagamento: credito, debito, dinheiro");

        //12-09-2019 Gerar PDF de todas as vendas
        $this->atualizaBanco("INSERT INTO CONFIGURACAO (tipo, valor) VALUES ('pdf_todas_paginas', 1);", "log_pdf_todas_paginas", 'Inicialização da configuração para gerar pdf de todas as vendas criada');

        //12-09-2019 aumentar tamanho das colunas de configuracao
        $this->atualizaBanco("ALTER TABLE `configuracao` CHANGE `tipo` `tipo` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        ALTER TABLE `configuracao` CHANGE `valor` `valor` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;", "aumenta_tamanho_colunas_conf", 'Tamanho das colunas de configuração aumentadas');

        //12-09-2019 Gerar PDF de todas as vendas
        $this->atualizaBanco("INSERT INTO CONFIGURACAO (tipo, valor) VALUES ('path_pdf_todas_paginas', '../pdf/vendas/');", "log_path_pdf_todas_paginas", 'Inicialização de local pra gerar os PDFs');

        //13-09-2019 Incluir tap list        
        $this->atualizaBanco("ALTER TABLE `preco` ADD `is_tap_list` BOOLEAN NOT NULL DEFAULT FALSE;", "alter_add_tap_list", "Inserido campo tap list em preço");

        //14-09-2019 Incluir pos tap list        
        $this->atualizaBanco("ALTER TABLE `preco` ADD `pos_tap_list` INTEGER NOT NULL DEFAULT FALSE;", "alter_add_pos_tap_list", "Inserido campo pos tap list em preço");

        //17-09-2019 Aumentar tamanho do campo telefone
        $this->atualizaBanco("ALTER TABLE `cliente` CHANGE `telefone` `telefone` CHAR(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;", "alter_size_telefone_cliente", "Aumentado tamanho do campo telefone pra 14");

        //17-09-2019 deixar pos_tap_list ser NULL   
        $this->atualizaBanco("ALTER TABLE `preco` CHANGE `pos_tap_list` `pos_tap_list` INT(11) NULL DEFAULT NULL;", "alter_pos_tap_list_to_null", "Alterado campo tap list para poder ser NULL");


        //02-10-2019 deixar pos_tap_list ser NULL   
        $this->atualizaBanco(" ALTER TABLE `preco` CHANGE `is_tap_list` `is_tap_list` TINYINT(1) NULL DEFAULT '0';", "alter_is_tap_list_to_null", "Alterado campo is tap list para poder ser NULL");


        //02-10-2019 Gerar PDF de todas as vendas
        $this->atualizaBanco("INSERT INTO CONFIGURACAO (tipo, valor) VALUES ('is_mostrar_botao_fiado', '0');", "log_is_mostrar_botao_fiado", 'Inicialização flag para mostrar ou não o botão de fiado');




        //04-10-2019 Reestruturação da Tabela Caixa
        $this->atualizaBanco("DROP TABLE CAIXA", "drop_table_caixa_reestruturacao", 'Drop Reestruturação da tabela caixa');

        $this->atualizaBanco("CREATE TABLE `caixa` (
  `pk_caixa` int(11) NOT NULL,
  `fk_venda` int(11) DEFAULT NULL,
  `valor_dinheiro` decimal(10,2) DEFAULT NULL,
  `valor_debito` decimal(10,2) DEFAULT NULL,
  `valor_credito` decimal(10,2) DEFAULT NULL,
  `tipo` enum('Abertura de Caixa','Entrada - Recebimento de Pagamento','Saída - Pagamento de Despesa','Sangria') DEFAULT NULL,
  `observacao` varchar(900) NOT NULL,
  `dt_movimento` timestamp NOT NULL DEFAULT current_timestamp(),
  `categoria` enum('Água','Luz','Telefone','Insumos da Fábrica','Produtos pra Venda','Outra') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `caixa`
  ADD PRIMARY KEY (`pk_caixa`),
  ADD KEY `fk_venda` (`fk_venda`);


ALTER TABLE `caixa`
  MODIFY `pk_caixa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;


ALTER TABLE `caixa`
  ADD CONSTRAINT `caixa_ibfk_1` FOREIGN KEY (`fk_venda`) REFERENCES `venda` (`pk_venda`) ON DELETE CASCADE ON UPDATE CASCADE;
", "create_table_caixa_reestruturacao", 'Create Reestruturação da tabela caixa');


        //10-10-2019 Rename da Tabela Caixa Original para item_caixa
        $this->atualizaBanco("RENAME TABLE `fabrica`.`caixa` TO `fabrica`.`item_caixa`;", "rename_table_caixa_to_item_caixa", 'Renomeada tabela caixa para item caixa');


        //10-10-2019 Rename da pk de item_caixa Original para pk_item_caixa
        $this->atualizaBanco("ALTER TABLE `item_caixa` CHANGE `pk_caixa` `pk_item_caixa` INT(11) NOT NULL AUTO_INCREMENT;", "rename_pk_caixa_to_pk_item_caixa", 'Renomeada atributo pk_caixa to pk_item_caixa');

        //10-10-2019 Add fk_caixa to table caixa
        $this->atualizaBanco("ALTER TABLE `item_caixa` ADD `fk_caixa` INT NOT NULL AFTER `fk_venda`;", "add_fk_caixa_to_table_caixa", 'Adicionado fk_caixa to tabela item_caixa');

        //10-10-2019 create da tabela caixa
        $this->atualizaBanco("CREATE TABLE `caixa` (
  `pk_caixa` int(11) PRIMARY KEY AUTO_INCREMENT,  
  `dt_abertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `dt_fechamento` timestamp NULL,
  `estado` ENUM('aberto','fechado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;", "create_table_caixa_mae", 'Create tabela caixa mãe');


        //10-10-2019 Add restrição de chave estrangeira e fk_caixa to table item_caixa
        $this->atualizaBanco(" ALTER TABLE `item_caixa`
            ADD CONSTRAINT `item_caixa_ibfk_18p` FOREIGN KEY (`fk_caixa`) REFERENCES `caixa` (`pk_caixa`) ON DELETE CASCADE ON UPDATE CASCADE;", "add_retrição_fk_caixa_to_table_caixa", 'Adicionado restrição fk_caixa to tabela item_caixa');

        //11-10-2019 Add troco na tela de vendas
        $this->atualizaBanco("ALTER TABLE `venda` ADD `troco` DECIMAL(10,2) NULL DEFAULT NULL AFTER `valor_pago_dinheiro`;", "add_troco_table_vendas", 'Adicionado coluna de troco na tabela de vendas');

        //11-10-2019 Update tabela venda pra gravar o valor de pagamento, para as vendas que foram criadas antes de poder adicionar valor de pagamento
        //pra facilitar, vamos gravar tudo como se tivesse sido pago em dinheiro
        $this->atualizaBanco("UPDATE `venda` SET valor_pago_dinheiro = valor_final where valor_pago_credito = 0 AND valor_pago_debito = 0 AND valor_pago_credito = 0;", "update_default_valor_pago_dinheiro", 'Update coluna de valor de pagamento, para que o valor em dinheiro seja preenchido nas vendas que estão sem valor de pagamento preenchido');
        
        //11-10-2019 Update tabela venda pra gravar o valor dos trocos
        $this->atualizaBanco("UPDATE `venda` SET troco = valor_total - (valor_pago_credito + valor_pago_dinheiro + valor_pago_debito + desconto);", "update_troco_table_vendas", 'Update coluna de troco na tabela de vendas');
       
        //11-10-2019 Update tabela venda pra gravar o valor dos trocos
        $this->atualizaBanco("ALTER TABLE `item_caixa` CHANGE `categoria` `categoria` ENUM('Água','Luz','Telefone','Insumos da Fábrica','Produtos pra Venda','Outra') CHARACTER SET utf8 COLLATE utf8_general_ci NULL;", "update_categoria_table_caixa_", 'Update coluna de categoria em item_caixa');

        //26-10-2019 Inclusão de novo tipo de item caixa
               $this->atualizaBanco("ALTER TABLE `item_caixa` CHANGE `tipo` `tipo` ENUM('Abertura de Caixa','Entrada - Recebimento de Pagamento','Saída - Pagamento de Despesa','Sangria','Complementação de Caixa') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;", "update_novo_item_caixa", 'Update tabela item_caixa, novo item caixa');

        
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
                if ($conf->getErrors())
                    print_r($conf->getErrors());
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
