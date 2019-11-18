<?php

use app\models\Venda;
use Codeception\Lib\Console\Output;

class ContactFormCest {

//    public function _before(FunctionalTester $I)
//    {
//        
//    }

    public function openVendasPage(FunctionalTester $I) {
        $I->amOnPage(['venda/venda']);
        $I->see('Balcão', 'span');
    }

    public function openAbrirVendasAbertas(FunctionalTester $I) {
        $venda = Venda::find()->one(); //busca uma venda pra servir de teste
        $output = new Output([]);
        $output->writeln('Testando a venda id=' . $venda->pk_venda);
        $I->amOnRoute('venda/venda', ['id' => $venda->pk_venda]);
        $I->see('Itens', 'h3');
    }

//    public function submitPagarForm(FunctionalTester $I) {
//        $venda = Venda::find()->one(); //busca uma venda pra servir de teste
//       // $output = new Output([]);
//      //  $output->writeln('Testando a venda id=' . $venda->pk_venda);
//        $I->amOnRoute('venda/venda', ['id' => $venda->pk_venda]);
//
//        $I->click('h3 > button'); //botão pagar
//
//        $I->see('Valor');
//    }

//    public function submitPagarForm(FunctionalTester $I)
//    {
//        $I->submitForm('#contact-form', []);
//        $I->expectTo('see validations errors');
//        $I->see('Contact', 'h1');
//        $I->see('Name cannot be blank');
//        $I->see('Email cannot be blank');
//        $I->see('Subject cannot be blank');
//        $I->see('Body cannot be blank');
//        $I->see('The verification code is incorrect');
//    }
//
//    public function submitFormWithIncorrectEmail(FunctionalTester $I)
//    {
//        $I->submitForm('#contact-form', [
//            'ContactForm[name]' => 'tester',
//            'ContactForm[email]' => 'tester.email',
//            'ContactForm[subject]' => 'test subject',
//            'ContactForm[body]' => 'test content',
//            'ContactForm[verifyCode]' => 'testme',
//        ]);
//        $I->expectTo('see that email address is wrong');
//        $I->dontSee('Name cannot be blank', '.help-inline');
//        $I->see('Email is not a valid email address.');
//        $I->dontSee('Subject cannot be blank', '.help-inline');
//        $I->dontSee('Body cannot be blank', '.help-inline');
//        $I->dontSee('The verification code is incorrect', '.help-inline');        
//    }
//
//    public function submitFormSuccessfully(FunctionalTester $I)
//    {
//        $I->submitForm('#contact-form', [
//            'ContactForm[name]' => 'tester',
//            'ContactForm[email]' => 'tester@example.com',
//            'ContactForm[subject]' => 'test subject',
//            'ContactForm[body]' => 'test content',
//            'ContactForm[verifyCode]' => 'testme',
//        ]);
//        $I->seeEmailIsSent();
//        $I->dontSeeElement('#contact-form');
//        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');        
//    }
}
