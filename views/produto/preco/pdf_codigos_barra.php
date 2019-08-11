
<table>


    <?php
    for ($index = 0; $index < count($codigos); $index++) {
        if ($index % 3 == 0) {
            echo "<tr>";
        }
        echo "<td>$codigos[$index]&nbsp;&nbsp;&nbsp;<br></td>";
        if ($index % 3 == 2) {
            echo "</tr>";
        }
    }
    ?>
</table>

