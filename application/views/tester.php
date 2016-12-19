<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>TESTPAGINA</h2>

                <p><?php                  
                        
                        
                        $session_data = $this->session->all_userdata();

echo '<pre>';
print_r($session_data);
                   
                    ?>
                </p>                
                
            </article>

        </div>
    </div>
</div>