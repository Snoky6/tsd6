<?php
/*
  $CI =& get_instance();
  $CI->load->library('email');
  $config = array(
  'mailtype' => 'html',
  'charset' => 'utf-8',
  'priority' => '1'
  );
  $CI->email->initialize($config);
  $CI->email->from('webshop@dulani.be', 'Dulani');
  $CI->email->subject('Foutmelding www.dulani.be');
  $CI->email->message($severity . "<br/>" . $message . "<br/>" . $filepath . "<br/>" . $line);
  $CI->email->to("jeroen_vinken@hotmail.com");
  $CI->email->send();

  $CI->email->from('webshop@dulani.be', 'Dulani');
  $CI->email->subject('Foutmelding www.dulani.be');
  $CI->email->message($severity . "<br/>" . $message . "<br/>" . $filepath . "<br/>" . $line);
  $CI->email->to("webshop@dulani.be");
  $CI->email->send();
 * 
 */
?>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

    <h2>Er liep iets mis!</h2>
    <p>Onze techneut bij <?php echo global_bedrijfsnaam; ?> is op de hoogte gebracht van het probleem probeert zo snel mogelijk een oplossing te vinden.<br/>Probeer het later opnieuw.</p>

    <p>Severity: <?php echo $severity; ?></p>
    <p>Message:  <?php echo $message; ?></p>
    <p>Filename: <?php echo $filepath; ?></p>
    <p>Line Number: <?php echo $line; ?></p>


</div>