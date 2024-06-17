<?php
//Template Name: Contact Us
get_header()?>
<!--CONTACT HEADING SECTION START-->
<div class="container-contact-heading">
    <div class="container-14">
    <section class="contact-us-heading-section-wrapper">
    <div class="contact-us-heading-section-underwrapper">
        <div class="contact-us-heading">
            <h1>Kontakt</h1>
            <p>Use our contact form, write an email or call the store directly:</p>
        </div>
    </div>
</section>
    </div>
</div>

<!--CONTACT HEADING SECTION END-->

<!--INFO AND FORM  SECTION START-->
<div class="container-info-and-form">
    <div class="container-14">
    <section class="info-and-form-section-wrapper">
    <div class="info-and-form-section-underwrapper">
        <div class="info-and-form-divs">
            <div class="contact-info-wrapper">
                <div class="info-card">
                    <h2>Shop - Müllheim</h2>
                    <p><img src="/wp-content/uploads/2024/06/group11.webp"> Werderstraße 45, 79379 Müllheim</p>
                    <p><img src="/wp-content/uploads/2024/06/icon_mail_.webp"><a href="mailTo: muellheim@hoffmann-optik.de"> muellheim@hoffmann-optik.de</a></p>
                    <p><img src="/wp-content/uploads/2024/06/icon_phone_.webp"> <a href="tel:076313375">07631-3375</a></p>
                    <div class="info-button-app button-type-3"><a href="https://www.click2date.eu/hoffmann-Optik-muellheim/appointment/start"target="_blank" >Book an appointment <img src="/wp-content/uploads/2024/05/icon-_clock-outline_-2.png"></a></div>
                </div>
                <div class="info-card">
                    <h2>Shop - Neuchâtel</h2>
                    <p><img src="/wp-content/uploads/2024/06/group11.webp"> Rebstraße 4, 79395 Neuenburg am Rhein</p>
                    <p><img src="/wp-content/uploads/2024/06/icon_mail_.webp"> <a href="mailTo:neuenburg@hoffmann-optik.de">neuenburg@hoffmann-optik.de</a></p>
                    <p><img src="/wp-content/uploads/2024/06/icon_phone_.webp"><a href="tel:0763173606">07631-73606</a> </p>
                    <div class="info-button-app button-type-3"><a href="https://www.click2date.eu/hoffmann-optik-neuenburg/appointment/start"target="_blank">Book an appointment <img src="/wp-content/uploads/2024/05/icon-_clock-outline_-2.png"></a></div>
                </div>
            </div>
            <div class="contact-form-wrapper">
            <div class="fields-wrapper">
            <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 7 ) ); ?>
            </div>
        </div>
    </div>
</section>
    </div>
</div>

<!--MAP  SECTION START-->
<section class="map-section-wrapper">
    <div clas="maps-section-underwrapper">
    <div id ="my-map"></div>
    </div>
</section>
<!--MAP  SECTION END-->

<!--BANNER SECTION START-->
<div class="container-banner-section">
    <div class="container-14">
    <section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/book-appointment-green'); ?>
</section>
    </div>
</div>

<!--BANNER SECTION END-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button') ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<!-- MOBILE SIDE BANNER START -->
<?php get_template_part('template-parts/mobile-side-banner') ?>
<!-- MOBILE SIDE BANNER END -->

<?php get_footer();?>





 <!--SKRIPTA ZA MAPU START-->
<script>
// Kreiranje  opcija
var mapOptions = {
    center: [47.81415188935085, 7.56236392336521],
    zoom: calculateZoomLevel(),
    zoomControl: false
}

var map = new L.map('my-map', mapOptions);

var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

map.addLayer(layer);

var pins = [
    { lat: 47.80862931900462, lng: 7.627458655660443, iconUrl: '/wp-content/uploads/2024/05/Group-4333-e1715854868437.png', title: 'Shop - Müllheim', descriptions: ['Werderstraße 45, 79379 Müllheim', 'muellheim@hoffmann-optik.de', '07631-3375'] },
    { lat: 47.81415188935085, lng: 7.56236392336521, iconUrl: '/wp-content/uploads/2024/05/Group-4333-e1715854868437.png', title: 'Shop - Neuchâtel', descriptions: ['Rebstraße 4, 79395 Neuenburg am Rhein', 'neuenburg@hoffmann-optik.de', '07631-73606'] },
    
];

// Dodavanje pinova na mapu
for (var i = 0; i < pins.length; i++) {
    var pin = pins[i];

    var popupContent = '<b>' + pin.title + '</b><br>'; // Dodajemo naslov u popup
    for (var j = 0; j < pin.descriptions.length; j++) {
        popupContent += '<p>' + pin.descriptions[j] + '</p>' + '<br>'; // Dodajemo svaku deskripciju u novi red
    }
    var pinIcon = L.icon({
        iconUrl: pin.iconUrl,
        iconSize: [40, 50],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    L.marker([pin.lat, pin.lng], { icon: pinIcon }).addTo(map)
        .bindPopup(popupContent);
}

function calculateZoomLevel() {
    if (window.innerWidth < 768) { 
        return 11; 
    } else {
        return 13; 
    }
}

</script>




 <!--SKRIPTA ZA MAPU END-->