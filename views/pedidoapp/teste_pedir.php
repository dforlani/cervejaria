<script>
    $.post("pedir", {id: 14, itens: {55: 2, 42: 4}, '_csrf': '<?= \Yii::$app->request->csrfToken ?>'})
            .done(function (data) {
                alert("Data Loaded: " + data);
            });

</script>


<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

