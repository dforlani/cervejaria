
<table>


    <?php
    for ($index = 0; $index < count($codigos); $index++) {
        if ($index % 2 == 0) {
            echo "<tr>";
        }
        echo "<td >$codigos[$index]&nbsp;&nbsp;&nbsp;<br><br><br><br></td>"
                . "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                
        if ($index % 2 == 2) {
            echo "</tr>";
        }
    }
    ?>
</table>

