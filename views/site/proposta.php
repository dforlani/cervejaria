<?php
/* @var $this yii\web\View */
?>
<style>

    .bd-example-container {
        min-width: 16rem;
        max-width: 25rem;
        margin-right: auto;
        margin-left: auto;
    }

    .bd-example-container-header {
        height: 3rem;
        margin-bottom: .5rem;  

    }

    .bd-example-container-sidebar_right {
        float: right;
        width: 4rem;
        height: 8rem;
        background-color: purple;

    }

    .bd-example-container-sidebar_left {
        float: left;
        width: 4rem;
        height: 8rem;
        background-color: blue;

    }

    .bd-example-container-body {
        height: 8rem;
        margin-right: 4.5rem;
        background-color: green;

    }

    .bd-example-container-fluid {
        max-width: none;
    }

    .calendario_projeto{
        max-height: 5%;

    }

    .arquivos_projeto{
        height: 700px;
    }
</style>



<div class='row arquivos_projeto'>
    <div class='col-xs-2 menu'> Menu</div>
    <div class='col-xs-8 content'> <?php echo $this->render('_arquivos',['model' => $model, 'arquivosProvider'=>$arquivosProvider]); ?></div>
    <div class='col-xs-2 content'> chat</div>
</div>
<footer >
    <div class='col-xs-12 col calendario_projeto'><?php echo $this->render('_calendario_colunas_dia') ?></div>
</footer>

