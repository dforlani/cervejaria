<?php

namespace app\controllers;

use app\components\Avisos;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * CaixaController implements the CRUD actions for Caixa model.
 */
class AvisosController extends Controller {

//      public function behaviors() {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
////                        'roles' => ['@'],
//                    ],
//
//                ],
//            ],
//        ];
//    }
    
    /**
     * Limpa o cache de avisos
     */
    public function actionLimpar() {
           Yii::$app->cache->set(Avisos::$KEY, "", 60 * 60 * 6);
    }
    
    public function actionIndex(){
         $avisos = Avisos::getAvisos();
                            // store $data in cache so that it can be retrieved next time
                            Yii::$app->cache->set(Avisos::$KEY, $avisos, 60 * 60 * 6);
                            
       echo  $this->render('index', ['avisos'=>$avisos]);
    }


}
