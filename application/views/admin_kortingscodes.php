<?php
if (isset($codes)) {
    
}
?>

<h3>Alle kortingscodes</h3>
<form>
    <table>
        <th>Code</th>
        <th>Korting</th>
        <th>Gebruikt</th>
        <th>Veelvoudig gebruik</th>
        <?php
        foreach ($codes as $code) {
            echo "<tr id='kortingcoderow$code->id'>";
            echo "<td>" . $code->code . "</td>";
            if ($code->kortingBedrag == null) {
                echo "<td>-" . $code->kortingProcent . "%</td>";
            } else {
                echo "<td>-" . $code->kortingBedrag . "EURO</td>";
            }

            echo "<td>" . $code->gebruikt . "</td>";
            echo "<td><input type='checkbox' name='multiuse'";
            if ($code->multiUse > 0) {
                echo "checked";
            }
            echo "></td>";
            
            echo "<td><img src='" . base_url() . APPPATH . "images/delete.png' title='verwijderen' alt='delete' onclick='deletekortingcode($code->id)' style='cursor:pointer;'><td>";

            echo "</tr>";
        }
        ?>    
    </table>
    <h3>Nieuwe code aanmaken</h3>
    <table>
        <th>Code</th>
        <th>Korting</th>
        <th>Procent</th>
        <th>Veelvoudig gebruik</th>
        <tr>
            <td>
                <?php
                $data = array('name' => 'kortingCodeCode', 'id' => 'kortingCodeCode', 'placeholder' => 'Code', 'class' => 'form-fieldinput');
                echo form_input($data);
                ?>
            </td>
            <td>
                <?php
                $data = array('name' => 'kortingCodeAmount', 'id' => 'kortingCodeAmount', 'placeholder' => 'Kortinghoeveelheid', 'class' => 'form-fieldinput');
                echo form_input($data);
                ?>
            </td>        
            <td>
                <!--<label for="kortingCodeProcent">Procent:</label>-->
                <?php
                $data = array('name' => 'kortingCodeProcent', 'id' => 'kortingCodeProcent', 'class' => 'form-fieldinput');
                echo form_checkbox($data);
                ?>
            </td>
            <td>
                <?php
                $data = array('name' => 'kortingCodeMultiUse', 'id' => 'kortingCodeMultiUse', 'class' => 'form-fieldinput');
                echo form_checkbox($data);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="button" name="savekortingscode" id="savekortingscode" value="Opslaan" onclick="savenewkortingscode()"/></td>
        </tr>
    </table>
</form>

<?php
//nog ene kunne toevoegen en verwijderen ?>