<?php

use kartik\editable\Editable;
use kartik\number\NumberControl;
use kartik\widgets\SwitchInput;
?>

<table class="table table-bordered table-condensed table-hover small kv-table">
    <tbody><tr class="danger">
            <th colspan="4" class="text-left text-danger">Formas de Venda</th>
        </tr>
        <tr class="active">
            <th >Denominação</th>
            <th>Preço</th>
            <th >Quantidade</th>
            <th >Promoção Ativa?</th>
        </tr>
        <?php foreach ($model->precos as $preco) { ?>
            <tr>
                <td><?= $preco->denominacao ?></td>
                <td class="text-right">
                    <?php
                    $editable = Editable::begin([
                                'inputType' => Editable::INPUT_HIDDEN,
                                'model' => $preco,
                                'value' => Yii::$app->formatter->asCurrency($preco->preco),
                                'asPopover' => true,
                                'size' => 'md',
                                'name' => 'aux',
                                'formOptions' => ['action' => ['altera-preco', 'id' => $preco->pk_preco]],
                    ]);
                    $form = $editable->getForm();
                    $editable->beforeInput = '' . $form->field($preco, 'preco')->widget(NumberControl::classname(), [
                                'options' => ['id' => 'preco-dip-' . $preco->pk_preco],
                                'maskedInputOptions' => [
                                    'prefix' => '',
                                    'suffix' => '',
                                    'allowMinus' => false,
                                    'digits' => 3,
                                ],
                    ]);
                    ;
                    Editable::end();
                    ?> 
                </td>

                <td class="text-right"><?= $preco->quantidade ?> </td> 
                <td class="text-right">
                    <?php
                    $editable = Editable::begin([
                                'inputType' => Editable::INPUT_HIDDEN,
                                'model' => $preco,
                                'value' => $preco->is_promocao_ativa,
                                'asPopover' => true,
                                'size' => 'md',
                                'name' => 'aux',
                                'formOptions' => ['action' => ['altera-promocao-ativa', 'id' => $preco->pk_preco]],
                    ]);
                    $form = $editable->getForm();
                    $editable->beforeInput = '' . $form->field($preco, 'is_promocao_ativa')->widget(SwitchInput::classname(), [ ]);
                    ;
                    Editable::end();
                    ?> 
                </td>

            </tr>
            <?php
        }
        ?>
    </tbody>
</table>