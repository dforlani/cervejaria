<?php

namespace app\controllers;

use app\models\Configuracao;
use DateTime;
use Exception;
use Ifsnop\Mysqldump\Mysqldump;
use PHPMailer\PHPMailer\PHPMailer;
use Yii;
use yii\filters\AccessControl;
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
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//
//                ],
//            ],
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

        //tempo em minutos pra realização do backup
        $configuracoes['tempo_em_minutos_para_backup_automatico'] = Configuracao::getConfiguracaoByTipo("tempo_em_minutos_para_backup_automatico");

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

            //configurações de pdf_todas_paginas
            $model = $configuracoes['tempo_em_minutos_para_backup_automatico'];
            $model->valor = Yii::$app->request->get('tempo_em_minutos_para_backup_automatico', '0');
            $model->save();
        }



        return $this->render('index', ['configuracoes' => $configuracoes
        ]);
    }

    public function actionGerarEmail() {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;

        $mail->IsSMTP();

        //Tell PHPMailer to use SMTP
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        //Set the encryption system to use - ssl (deprecated) or tls

        $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        //Whether to use SMTP authentication
          $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
       // $mail->Username = "dforlani@gmail.com";
           $mail->Username = "cassius.forlani@gmail.com";

        //Password to use for SMTP authentication
        //$mail->Password = "nnrrzobxluivoidp";
           $mail->Password = "eiko431*";
          // $mail->Password = "hakdo7126h*";
//cervejariaparaiso@cervejariaparaiso.com
        //Set who the message is to be sent from
        $mail->SetFrom('dforlani@gmail.com', 'Curso PHP 7');

        //Set an alternative reply-to address
        // $mail->addReplyTo('', 'First Last');
        //Set who the message is to be sent to
        $mail->addAddress('dforlani@gmail.com', 'Suporte oi');

        //Set the subject line
        $mail->Subject = 'Testando a classe PHPMailer com Gmail';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        // $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->msgHTML("ksndksndknskned");
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';

        //Attach an image file
        // $mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }

    public function actionBackup() {


        $msg = "";
        if (@$_GET['gerar'] == 1) {
            //  use Ifsnop\Mysqldump as IMysqldump;
            ConfiguracaoController::backup();
        } elseif (@$_GET['abrir_pasta'] == 1) {
            exec("explorer " . realpath('../backup/'));
        }


        return $this->render('backup', ['msg' => $msg]);
    }

    public static function backup($file_name = "") {
        $data = date('Y-m-d-H-i-s');
        $pasta = "../backup/dump_{$data}_{$file_name}.sql";
        try {
            $dump = new Mysqldump('mysql:host=localhost;dbname=fabrica', 'root', '');
            $dump->start($pasta);
            $msg = "Backup realizado com sucesso na pasta do sistema em $pasta!";
        } catch (Exception $e) {
            $msg = 'mysqldump-php error: ' . $e->getMessage();
        }
    }

    public function actionBackupAutomatico() {
        $conf_aux = Configuracao::getConfiguracaoByTipo("tempo_em_minutos_para_backup_automatico");
        if ($conf_aux->valor != 0) {//se o valor é igual a zero, não gera o backup
            $minutos_minimo_pro_backup = $conf_aux->valor;
            $conf_aux = Configuracao::getConfiguracaoByTipo("dia_e_hora_desde_ultimo_backup_automatico");
            $ultimo_backup = new DateTime($conf_aux->valor);
            $agora = new DateTime("now");

            $interval = $agora->diff($ultimo_backup);
            $minutos_passado = $interval->format('%i');
            if ($minutos_passado >= $minutos_minimo_pro_backup) {
                $this->backup("automatico");
                $conf_aux->valor = $agora->format("Y-m-d H:i:s");
                $conf_aux->save();
            }
            echo $minutos_passado;
        }
    }

}

