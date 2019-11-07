<?php

namespace tests\unit\models;

use app\models\Cliente;
use Codeception\Test\Unit;
use UnitTester;

class ClienteTest extends Unit {

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testCriacao() {

        //teste de salvar
        $nome = "Diogo Teste";
        $user = new Cliente();
        $user->nome = $nome;
        $this->assertTrue($user->save());
        $this->assertEquals($nome, $user->nome);

        //teste de buscar e ver se está igual
        $user = Cliente::findOne(['nome' => $nome]);
        $this->assertNotNull($user);
        $this->assertEquals($nome, $user->nome);


        //teste diretamente no Banco
        $this->tester->seeRecord('app\models\Cliente', ['nome' => 'Diogo Teste']);
        $this->tester->dontSeeRecord('app\models\Cliente', ['nome' => 'Diogo Teste2']);
    }

    public function testAlteracao() {

        //teste de salvar
        $nome = "Diogo Teste";
        $user = new Cliente();
        $user->nome = $nome;
        $this->assertTrue($user->save());
        $this->assertEquals($nome, $user->nome);

        //teste de buscar e ver se está igual
        $user = Cliente::findOne(['nome' => $nome]);
        $this->assertNotNull($user);
        $this->assertEquals($nome, $user->nome);

        //teste de alteração        
        $user->nome = 'Diogo Teste2';
        $this->assertTrue($user->save());
        $this->assertEquals('Diogo Teste2', $user->nome);

      
        $this->tester->seeRecord('app\models\Cliente', ['nome' => 'Diogo Teste2']);
        $this->tester->dontSeeRecord('app\models\Cliente', ['nome' => $nome]);
    }

}
