
<table>


    <?php
    for ($index = 0; $index < count($codigos); $index++) {
        if ($index % 2 == 0) {
            echo "<tr>";
        }
        echo "<td style='margim-right:5px;heigth:2000px;width:200px'>$codigos[$index]<br>"
                . "<br><br>"
                . "</td>"
                . "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        if ($index % 2 == 2) {
            echo "</tr>";
        }
    }
    ?>
</table>

