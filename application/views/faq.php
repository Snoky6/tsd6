<style>
    p {
        margin-bottom: 0px !important;
    }
</style>

<!-- Main -->
<div id="banner-wrapper">
    <div id="" class="box container">
        <div class="row">

            <h2>Frequently Asked Questions</h2>

            <p><?php
                foreach ($faqs as $faq) {
                    echo "<div style='margin-bottom:10px; padding-top:0.5em;'>";
                    echo "<h4>" . $faq->vraag . "</h4>";
                    echo "<p>" . $faq->antwoord . "</p>";
                    echo "</div>";
                }
                ?>
            </p>


        </div>
    </div>