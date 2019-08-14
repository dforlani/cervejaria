<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="../js/JsBarcode.all.min.js"></script>
        <script>
            Number.prototype.zeroPadding = function () {
                var ret = "" + this.valueOf();
                return ret.length == 1 ? "0" + ret : ret;
            };
        </script>
    </head>
    <body>



        <div style="width:250px;text-align: center;font-size: 30px">
            <?= $modelPreco->getNomeProdutoPlusDenominacaoSemBarras() ?>
            <img id="barcode<?= $modelPreco->getCodigoBarrasComDigitoVerificador() ?>"/>

            <script>JsBarcode("#barcode<?= $modelPreco->getCodigoBarrasComDigitoVerificador() ?>")                        
                        .EAN13("<?= $modelPreco->getCodigoBarrasComDigitoVerificador() ?>", {fontSize: 30, textMargin: 0})
                        .render();</script>
            <barcode code="<?= $modelPreco->getCodigoBarrasComDigitoVerificador() ?>" type="EAN13" size='2' height="1" text="0" />
        </div>

    </body>
</html>