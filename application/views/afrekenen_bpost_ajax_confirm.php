<script> 
    var element = window.parent.document.getElementById("betalendiv");
    element.style.display = 'block'; 
    
    /*var element3 = window.parent.document.getElementById("bpost-ok");
    element3.style.display = 'block'; */
    
    var element4 = window.parent.document.getElementById("bpost-overzicht");
    element4.innerHTML = "<h3>2. Bpost gegevens invullen <img src='<?php echo base_url() . APPPATH; ?>images/icons/check.png'/> </h3> <?php echo $overzicht; ?>";
    element4.style.display = 'block'; 
    
    var element2 = window.parent.document.getElementById("bpost-wrapper");
    element2.innerHTML = "<h3 style='color:#E61D80;'>3. Klik op onderstaande knop om uw bestelling af te ronden <img src='<?php echo base_url() . APPPATH; ?>images/icons/arrow_pink.png'/></h3>";
    
    /* scroll up to be sure the user sees all the details on mobile */
    document.getElementById('gegevens').scrollIntoView();
</script>