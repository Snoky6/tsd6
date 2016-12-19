<?php ?>
<!-- FB -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<style>
  .fb-share-button
{
transform: scale(2.5);
-ms-transform: scale(2.5);
-webkit-transform: scale(2.5);
-o-transform: scale(2.5);
-moz-transform: scale(2.5);
transform-origin: top left;
-ms-transform-origin: top left;
-webkit-transform-origin: top left;
-moz-transform-origin: top left;
-webkit-transform-origin: top left;
}
  </style>
<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Bedankt!</h2>
                <p>Uw bestelling is nu compleet. Er wordt een mail naar <b><?php echo $email;?></b> gestuurd met een overzicht van de gegevens die je meegaf.</p><br/>        
                <p>Indien u na 5 minuten nog steeds geen mail bevestigingsmail ontvangen hebt kijk dan eens na of de mail niet bij de ongewenste <label style="color:red;">spam mail</label> terecht gekomen is. Als u ook daar de mail niet aantreft gelieve dit dan te melden via <?php echo global_webshopemail; ?>. Dank u!</p>
                <div style="padding: 0px 10px;" class="fb-share-button" data-href="<?php echo global_websiteURL; ?>" data-layout="icon_link"></div>
            </article>

        </div>
    </div>
</div>