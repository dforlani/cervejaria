
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
        <div style="width:300px;text-align: center;font-size: 18px">
            CERVEJARIA PARAÍSO<br><br>
            <img id="barcode<?= $model->getComandaComDigitoVerificador() ?>"/>
            <script>JsBarcode("#barcode<?= $model->getComandaComDigitoVerificador() ?>")
                        .EAN13("<?= $model->getComandaComDigitoVerificador() ?>", {fontSize: 30, textMargin: 0})
                        .render();</script>
            <barcode code="<?= $model->getComandaComDigitoVerificador() ?>" type="EAN13" size='1.4' height="0.5" text="0" />
        </div>



    </body>
</html>





