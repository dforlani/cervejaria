<?php

namespace tests\unit\models;

use app\models\Cliente;
use app\models\Comanda;
use app\models\ItemVenda;
use app\models\Preco;
use app\models\Produto;
use app\models\UnidadeMedida;
use app\models\Venda;
use Codeception\Lib\Console\Output;
use Codeception\Test\Unit;
use UnitTester;

class VendaTest extends Unit {

    /**
     * @var UnitTester
     */
    protected $tester;

    // tests
    public function testValidacoesVenda() {

        $model = new Venda();
        $this->assertTrue($model->save());

        $model->fk_cliente = -1;
        $this->assertNotTrue($model->save());

        $model->fk_cliente = null;
        $model->fk_comanda = -1;
        $this->assertNotTrue($model->save());

        $model->fk_comanda = null;
        $model->fk_usuario_iniciou_venda = -1;
        $this->assertNotTrue($model->save());

        $model->fk_usuario_iniciou_venda = null;
        $model->fk_usuario_recebeu_pagamento = -1;
        $this->assertNotTrue($model->save());

        $model->fk_usuario_recebeu_pagamento = null;
        $this->assertTrue($model->save());
       
        //essa última deve dar certo, pq o troco é atualizado a cada novo save, nisso esse valor vai ser zerado
        $model->troco = 10;
        $this->assertTrue($model->save());
        //aqui 
        
    }

    public function testCriacaoItemVenda() {
        //vai criar tudo pra poder testar uma venda
        $unidade = new UnidadeMedida();
        $unidade->unidade_medida = 'unidade_teste';
        $this->assertTrue($unidade->save());

        $produto = new Produto();
        $produto->fk_unidade_medida = $unidade->pk_unidade_medida;
        $produto->is_vendavel = true;
        $produto->nome = "Produto1_Teste";
        $this->assertTrue($produto->save());

        $preco = new Preco();
        $preco->fk_produto = $produto->pk_produto;
        $preco->preco = 1.11;
        $preco->denominacao = "Preco_Teste";
        $preco->quantidade = 0.3;
        $this->assertTrue($preco->save());

        $cliente = new Cliente();
        $cliente->nome = "Cliente_teste";
        $this->assertTrue($cliente->save());

        $comanda = new Comanda();
        $comanda->numero = 9247483653401;
        $this->assertTrue($comanda->save());

        $venda = new Venda();
        $this->assertTrue($venda->save());

        //Itens Venda
        $item = new ItemVenda();
        $item->quantidade = 2;
        $this->assertNotTrue($item->save());
        
        $item->preco_unitario = 1.1;        
        $this->assertNotTrue($item->save());
        
        $this->assertEquals($item->preco_final, $item->preco_unitario * $item->quantidade);
        $this->assertNotTrue($item->save());
        
        $item->fk_preco = $preco->pk_preco;
        $this->assertNotTrue($item->save());
        
        $item->fk_venda = $venda->pk_venda;
        $this->assertTrue($item->save());
        
        //salva a venda para ver se atualizou os valores da compra
        $this->assertTrue($venda->save());
        $this->assertEquals($venda->valor_final, $item->preco_final);
        
        //testar com desconto
        $venda->desconto = 1;
        $this->assertTrue($venda->save());
        $this->assertEquals($venda->valor_final, $item->preco_final -  $venda->desconto); 
        
         //Novo Item de Venda, pra testar os valores da venda pra dois itens
        $item2 = new ItemVenda();
        $item2->quantidade = 4;
        $this->assertNotTrue($item2->save());
        
        $item2->preco_unitario = 5.1;        
        $this->assertNotTrue($item2->save());
        
        $this->assertEquals($item2->preco_final, $item2->preco_unitario * $item2->quantidade);
        $this->assertNotTrue($item2->save());
        
        $item2->fk_preco = $preco->pk_preco;
        $this->assertNotTrue($item2->save());
        
        $item2->fk_venda = $venda->pk_venda;
        $this->assertTrue($item2->save());
        
        $venda = Venda::findOne($venda->pk_venda); //faz isso pra atualizar os relacionamentos
        //confere se estão os dois itens ligados a venda
        $this->assertEquals( 2, count($venda->itensVenda));
        
        //salva a venda para ver se atualizou os valores da compra
        $venda->desconto = 0;
        $this->assertTrue($venda->save());
        $this->assertEquals($venda->valor_final, $item2->preco_final + $item->preco_final);
        
        //testar com desconto
        $venda->desconto = 5;
        $this->assertTrue($venda->save());
        $this->assertEquals($venda->valor_final, $item2->preco_final + $item->preco_final -  $venda->desconto); 
        
        //Testar valores pagos e troco
        $venda->valor_pago_credito = 20;
        $venda->valor_pago_debito = 5;
        $venda->valor_pago_dinheiro = 2;
        $this->assertTrue($venda->save());
        $this->assertEquals($venda->troco, ($item2->preco_final + $item->preco_final -  $venda->desconto) - ($venda->valor_pago_credito + $venda->valor_pago_debito + $venda->valor_pago_dinheiro )); 
        
    }

}
