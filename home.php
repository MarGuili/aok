<?php
$page_title = "Home";
include ("includes/header.php");
?>
<div id="slideshow">
    <div class="slideshowWindow">
        <div class="slide"> <img src="http://placekitten.com/1401/230" alt="slide1">
            <div class="slideText">
                <h1 class="slideHeading">Slide One</h1>
            </div>
        </div>
        <div class="slide"> <img src="http://placekitten.com/1400/230" alt="slide2">
            <div class="slideText">
                <h1 class="slideHeading">Slide Two</h1>
            </div>
        </div>
        <div class="slide"> <img src="http://placekitten.com/1399/230" alt="slide3">
            <div class="slideText">
                <h1 class="slideHeading">Slide Three</h1>
            </div>
        </div>
    </div>
</div>
<div id = "main">
<!-- Page unique content -->
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3096.7087358005474!2d-94.58358968464394!3d39.09033167954138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87c0f06a26bc8d31%3A0x96f80beb72e35fe2!2sDowntown+Kansas+City%2C+Kansas+City%2C+MO+64108!5e0!3m2!1sen!2sus!4v1489203391289" width = "100%" height = "250px" 1frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<script src="slider.js"></script>
<?php
include ("includes/footer.php");
?>