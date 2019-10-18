<?php

namespace app\controllers;

use app\models\Configuracao;
use Ifsnop\Mysqldump\Mysqldump;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * CaixaController implements the CRUD actions for Caixa model.
 */
class ConfiguracaoController extends Controller {

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

    /**
     * Lists all Caixa models.
     * @return mixed
     */
    public function actionIndex() {

        $configuracoes = [];
        //configuracao pra gerar PDF sobre todas as vendas
        $configuracoes['pdf_todas_paginas'] = Configuracao::getConfiguracaoByTipo("pdf_todas_paginas");

        //configuracao pra gerar PDF sobre todas as vendas
        $configuracoes['path_pdf_todas_paginas'] = Configuracao::getConfiguracaoByTipo("path_pdf_todas_paginas");

        //configuracao para mostrar ou não o botão de fiado
        $configuracoes['is_mostrar_botao_fiado'] = Configuracao::getConfiguracaoByTipo("is_mostrar_botao_fiado");


        //solicitação de salvar
        if (!empty(Yii::$app->request->get())) {
            //configurações de pdf_todas_paginas
            $model = $configuracoes['pdf_todas_paginas'];
            $model->valor = Yii::$app->request->get('conf_pdf_todas_paginas', '0');
            $model->save();

            //configurações de pdf_todas_paginas
            $model = $configuracoes['is_mostrar_botao_fiado'];
            $model->valor = Yii::$app->request->get('is_mostrar_botao_fiado', '0');
            $model->save();
        }



        return $this->render('index', ['configuracoes' => $configuracoes
        ]);
    }

    


    
    public function actionBackup() {
        $data = date('Y-m-d-H-i-s');
        $pasta = "../backup/dump_$data.sql";
   
        $msg = "";
        if (@$_GET['gerar'] == 1) {
          //  use Ifsnop\Mysqldump as IMysqldump;
          
            try {
                $dump = new Mysqldump('mysql:host=localhost;dbname=fabrica', 'root', '');
                $dump->start($pasta);
                 $msg =  "Backup realizado com sucesso na pasta do sistema em $pasta!";
            } catch (\Exception $e) {
                $msg = 'mysqldump-php error: ' . $e->getMessage();
            }
           
        }elseif (@$_GET['abrir_pasta'] == 1) {
            exec ("explorer ". realpath('../backup/'));
           
        }
        

        return $this->render('backup', ['msg'=>$msg]);
    }

}
