
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
        <div>
            <img id="barcode<?= $model->getComandaComDigitoVerificador() ?>"/>
            <script>JsBarcode("#barcode<?= $model->getComandaComDigitoVerificador() ?>")
                        .EAN13("<?= $model->getComandaComDigitoVerificador() ?>", {fontSize: 30, textMargin: 0})
                        .render();</script>
        </div>

    </body>
</html>


