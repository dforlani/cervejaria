
<script type='text/javascript' >
    $(function () {
        $(".notasVersao").click(function (event) {
            event.preventDefault();
            var nv = "#n" + $(this).attr("id");
            if ($(nv).is(":visible")) {
                $(nv).hide("fast");
            } else {
                $(nv).show("fast");
            }
        });
    });
</script>

<style>
    .notasVersaoStyle{
        padding: 5px 0; 
    }
    .notasVersaoStyle a{
        color: #0075cf;
        text-decoration: none;
        font-weight: bold;
    }
    .notasVersaoStyle ul li{
        color: #7d7d7d;
        padding-bottom: 3px;
    }

    .mudancas li{
        padding-bottom: 5px;
    }
</style>


<h2>Notas de versão</h2>
<div class="notasVersaoStyle">
    <a href="#" id="v120" class="notasVersao"><img src="images/mais_grid.png" /> Versão 1.2.0 (11/09/2019)</a>
    <ul id="nv120" style="">
        <li><b>Venda:</b>Inclusão da colunas valor_pagamento_dinheiro, valor_pagamento_credito, valor_pagamento_debito na tabela Venda e inclusão de modal em Vendas para utilizá-las;</li>            
        <li>Inclusão de ordenadores padrão em todas as telas de listagem</li>
        <li>Bloqueio de geração de PDF de produtos que não tem código de barras</li>
        <li>Inclusão de Configuração pra gerar PDF automaticamente em cada venda</li>
        <li>Inclusão da opção de colocar produtos na Tap List</li>
    </ul>
</div>

<div class="notasVersaoStyle">
    <a href="#" id="v110" class="notasVersao"><img src="images/mais_grid.png" /> Versão 1.1.0 (04/09/2019)</a>
    <ul id="nv110" style="display:none;">
        <li><b>Caixa:</b>Criação da Tabela Caixa;</li>            
    </ul>
</div>

<div class="notasVersaoStyle">
    <a href="#" id="v101" class="notasVersao"><img src="images/mais_grid.png" /> Versão 1.0.1 (04/09/2019)</a>
    <ul id="nv101" style="display:none;">
        <li><b>Relatórios:</b>Inclusão de opção por cliente;</li>    
        <li><b>Relatórios:</b>Correção na soma de pagamentos;</li>    
        <li><b>Relatórios:</b>Correção na data da venda (inclusão de especialização da classe Formatter);</li>    
    </ul>
</div>





