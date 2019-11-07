<?php

use yii\helpers\Url;

class VendasCest
{
       public function submitPagarForm(FunctionalTester $I) {
         $venda = Venda::find()->one(); //busca uma venda pra servir de teste
        $output = new Output([]);
        $output->writeln('Testando a venda id=' . $venda->pk_venda);
        $I->amOnRoute('venda/venda', ['id' => $venda->pk_venda]);
        
        $I->click('h3 > button'); //botão pagar
        $I->wait(2); // wait for button to be clicked
        $I->see('Valor');
        
        
    }
    
//    public function _before(\AcceptanceTester $I)
//    {
//        $I->amOnPage(Url::toRoute('/site/contact'));
//    }
//    
//    public function contactPageWorks(AcceptanceTester $I)
//    {
//        $I->wantTo('ensure that contact page works');
//        $I->see('Contact', 'h1');
//    }
//
//    public function contactFormCanBeSubmitted(AcceptanceTester $I)
//    {
//        $I->amGoingTo('submit contact form with correct data');
//        $I->fillField('#contactform-name', 'tester');
//        $I->fillField('#contactform-email', 'tester@example.com');
//        $I->fillField('#contactform-subject', 'test subject');
//        $I->fillField('#contactform-body', 'test content');
//        $I->fillField('#contactform-verifycode', 'testme');
//
//        $I->click('contact-button');
//        
//        $I->wait(2); // wait for button to be clicked
//
//        $I->dontSeeElement('#contact-form');
//        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');
//    }
}