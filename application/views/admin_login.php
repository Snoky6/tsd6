<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Inloggen</h2>

                <p>Gelieve in te loggen om verder te gaan.</p>
                <?php echo form_open_multipart('admin/login'); ?>

                <table border="0">           
                    <tr>                    
                        <td><?php echo form_label('Naam*: ', 'naam'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Naam', 'required' => 'required');
                            echo form_input($data);
                            ?>
                        </td>                                                 
                        <td><?php echo form_label('Wachtwoord*: ', 'wachtwoord'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'wachtwoord', 'id' => 'wachtwoord', 'required' => 'required', 'placeholder' => 'wachtwoord');
                            echo form_password($data);
                            ?>
                        </td>                                               
                    </tr>
                </table>                


                <?php
                echo form_submit('submit', 'Log in!');
                echo form_close();
                ?>
            </article>

        </div>
    </div>
</div>