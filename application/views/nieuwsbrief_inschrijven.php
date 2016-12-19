<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2 id="gegevens"><?php echo global_bedrijfsnaam; ?> nieuwsbrief</h2>
                <!--<?php echo form_open('nieuwsbrief/inschrijven'); ?>

                <table border="0">           
                    <tr>                    
                        <td><?php echo form_label('Naam: ', 'naam'); ?></td>
                        <td>
                <?php
                $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Naam', 'required' => 'required');
                echo form_input($data);
                ?>
                        </td>                         
                    </tr>
                    <tr>                                     
                        <td><?php echo form_label('E-mail*: ', 'email'); ?></td>
                        <td>
                <?php
                $data = array('name' => 'email', 'id' => 'email', 'required' => 'required', 'placeholder' => 'E-mail');
                echo form_input($data);
                ?>
                        </td>
                    </tr>                     
                </table>        

                <?php
                echo form_submit('submit', 'Bevestig!');
                echo form_close();
                ?>
                EINDE OUDE CODE --> 
                <!-- Begin MailChimp Signup Form -->
                <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
                <style type="text/css">
                    #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                    /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                       We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                </style>
                <div id="mc_embed_signup">
                    <form action="//dulani.us11.list-manage.com/subscribe/post?u=5a490811703928644b0da23ce&amp;id=8915d26179" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                            <h2>Schrijf u in voor onze nieuwsbrief</h2>
                            <div class="indicates-required"><span class="asterisk">*</span> duidt een verplicht veld aan</div>
                            <div class="mc-field-group">
                                <label for="mce-EMAIL">Email  <span class="asterisk">*</span>
                                </label>
                                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                            </div>
                            <div class="mc-field-group">
                                <label for="mce-FNAME">Voornaam </label>
                                <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
                            </div>
                            <div class="mc-field-group">
                                <label for="mce-LNAME">Naam </label>
                                <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_5a490811703928644b0da23ce_8915d26179" tabindex="-1" value=""></div>
                            <div class="clear"><input type="submit" value="Inschrijven" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function ($) {
                        window.fnames = new Array();
                        window.ftypes = new Array();
                        fnames[0] = 'EMAIL';
                        ftypes[0] = 'email';
                        fnames[1] = 'FNAME';
                        ftypes[1] = 'text';
                        fnames[2] = 'LNAME';
                        ftypes[2] = 'text'; /*
                         * Translated default messages for the $ validation plugin.
                         * Locale: NL
                         */
                        $.extend($.validator.messages, {
                            required: "Dit is een verplicht veld.",
                            remote: "Controleer dit veld.",
                            email: "Vul hier een geldig e-mailadres in.",
                            url: "Vul hier een geldige URL in.",
                            date: "Vul hier een geldige datum in.",
                            dateISO: "Vul hier een geldige datum in (ISO-formaat).",
                            number: "Vul hier een geldig getal in.",
                            digits: "Vul hier alleen getallen in.",
                            creditcard: "Vul hier een geldig creditcardnummer in.",
                            equalTo: "Vul hier dezelfde waarde in.",
                            accept: "Vul hier een waarde in met een geldige extensie.",
                            maxlength: $.validator.format("Vul hier maximaal {0} tekens in."),
                            minlength: $.validator.format("Vul hier minimaal {0} tekens in."),
                            rangelength: $.validator.format("Vul hier een waarde in van minimaal {0} en maximaal {1} tekens."),
                            range: $.validator.format("Vul hier een waarde in van minimaal {0} en maximaal {1}."),
                            max: $.validator.format("Vul hier een waarde in kleiner dan of gelijk aan {0}."),
                            min: $.validator.format("Vul hier een waarde in groter dan of gelijk aan {0}.")
                        });
                    }(jQuery));
                    var $mcj = jQuery.noConflict(true);</script>
                <!--End mc_embed_signup-->
            </div>            
        </div>
    </div>
</div>

<br/>

