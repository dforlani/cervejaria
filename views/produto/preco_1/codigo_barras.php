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
        <div style="width:200px;text-align: center"><?= $modelPreco->getNomeProdutoPlusDenominacao() ?></div>
        <div>

            <img id="barcode1"/>
            <script>JsBarcode("#barcode1", <?= $modelPreco->codigo_barras ?>);</script>
        </div>

    </body>
</html>