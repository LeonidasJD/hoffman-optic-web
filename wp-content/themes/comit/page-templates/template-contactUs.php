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
                    <div class="info-button-app button-type-3"><a href="https://www.click2date.eu/hoffmann-Optik-muellheim/appointment/start"target="_blank" >Book an appointment <svg class="clock-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.5332 6.47534V14.2112H19.2691" stroke="white" stroke-width="1.80097" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.7559 23.6084C18.8309 23.6084 23.7559 18.6835 23.7559 12.6084C23.7559 6.53326 18.8309 1.6084 12.7559 1.6084C6.68072 1.6084 1.75586 6.53326 1.75586 12.6084C1.75586 18.6835 6.68072 23.6084 12.7559 23.6084Z" stroke="white" stroke-width="1.80097" stroke-linecap="round" stroke-linejoin="round"/>
</svg></a></div>
                </div>
                <div class="info-card">
                    <h2>Shop - Neuchâtel</h2>
                    <p><img src="/wp-content/uploads/2024/06/group11.webp"> Rebstraße 4, 79395 Neuenburg am Rhein</p>
                    <p><img src="/wp-content/uploads/2024/06/icon_mail_.webp"> <a href="mailTo:neuenburg@hoffmann-optik.de">neuenburg@hoffmann-optik.de</a></p>
                    <p><img src="/wp-content/uploads/2024/06/icon_phone_.webp"><a href="tel:0763173606">07631-73606</a> </p>
                    <div class="info-button-app button-type-3"><a href="https://www.click2date.eu/hoffmann-optik-neuenburg/appointment/start"target="_blank">Book an appointment <svg class="clock-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.5332 6.47534V14.2112H19.2691" stroke="white" stroke-width="1.80097" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.7559 23.6084C18.8309 23.6084 23.7559 18.6835 23.7559 12.6084C23.7559 6.53326 18.8309 1.6084 12.7559 1.6084C6.68072 1.6084 1.75586 6.53326 1.75586 12.6084C1.75586 18.6835 6.68072 23.6084 12.7559 23.6084Z" stroke="white" stroke-width="1.80097" stroke-linecap="round" stroke-linejoin="round"/>
</svg></a></div>
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
       
    <div id ="my-map">
    <div id="scroll-overlay">Press Ctrl + scroll to zoom</div>
    </div>
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
            { lat: 47.80862931900462, lng: 7.627458655660443, iconUrl: '/wp-content/uploads/2024/06/group_43.webp', title: 'Shop - Müllheim', descriptions: ['Werderstraße 45, 79379 Müllheim', 'muellheim@hoffmann-optik.de', '07631-3375','<a href="https://www.click2date.eu/hoffmann-Optik-muellheim/appointment/start" target="_blank">Termin buchen</a>'] },
            { lat: 47.81415188935085, lng: 7.56236392336521, iconUrl: '/wp-content/uploads/2024/06/group_43.webp', title: 'Shop - Neuchâtel', descriptions: ['Rebstraße 4, 79395 Neuenburg am Rhein', 'neuenburg@hoffmann-optik.de', '07631-73606','<a href="https://www.click2date.eu/hoffmann-optik-neuenburg/appointment/start" target="_blank">Termin buchen</a>'] },
        ];

        // Dodavanje pinova na mapu
        for (var i = 0; i < pins.length; i++) {
            var pin = pins[i];

            var popupContent = '<b>' + pin.title + '</b><br>';
            for (var j = 0; j < pin.descriptions.length; j++) {
                popupContent += '<p>' + pin.descriptions[j] + '</p>' + '<br>';
            }
            var pinIcon = L.icon({
                iconUrl: pin.iconUrl,
                iconSize: [70, 90],
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

        // Funkcija za detekciju da li je uređaj mobilni
        function isMobileDevice() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }

        // Onemogućavamo podrazumevano zumiranje točkićem miša
        map.scrollWheelZoom.disable();

        // Flag za praćenje da li je Ctrl pritisnut
        var ctrlPressed = false;
        var overlay = document.getElementById('scroll-overlay');

        if (!isMobileDevice()) {
            // Event listener za detekciju pritiska Ctrl tastera
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Control') {
                    ctrlPressed = true;
                    map.scrollWheelZoom.enable();
                    overlay.style.display = 'none';
                }
            });

            // Event listener za detekciju puštanja Ctrl tastera
            document.addEventListener('keyup', function(e) {
                if (e.key === 'Control') {
                    ctrlPressed = false;
                    map.scrollWheelZoom.disable();
                }
            });

            // Event listener za omogućavanje zumiranja kada miš uđe u mapu ako je Ctrl pritisnut
            map.on('mouseenter', function() {
                if (ctrlPressed) {
                    map.scrollWheelZoom.enable();
                }
            });

            // Event listener za onemogućavanje zumiranja kada miš napusti mapu
            map.on('mouseleave', function() {
                map.scrollWheelZoom.disable();
                overlay.style.display = 'none';
            });

            // Event listener za pokušaj skrolovanja bez Ctrl tastera
            map.on('wheel', function(e) {
                if (!ctrlPressed) {
                    overlay.style.display = 'flex';
                }
            });

            // Event listener za detekciju klika na mapu
            map.on('click', function() {
                if (ctrlPressed) {
                    overlay.style.display = 'none';
                }
            });

            // Dodajemo event listener za skrolovanje pomoću drugih događaja radi kompatibilnosti
            map.getContainer().addEventListener('DOMMouseScroll', function(e) {
                if (!ctrlPressed) {
                    overlay.style.display = 'flex';
                }
            });

            map.getContainer().addEventListener('mousewheel', function(e) {
                if (!ctrlPressed) {
                    overlay.style.display = 'flex';
                }
            });
        } else {
            // Ako je uređaj mobilni, omogućavamo zumiranje dodirom
            map.touchZoom.enable();
            map.scrollWheelZoom.enable();
        }
// SKRIPTA ZA MAPU END


// KADA ODABEREMO OPCIJU IZ DROPDOWNA ONDA OPCIJA BUDE BOLD START
const dropdownStandortContact = document.getElementById('field_18euj');

dropdownStandortContact.addEventListener('change', function() {
  if (dropdownStandortContact.value === "") {
    dropdownStandortContact.classList.remove('change-select');
  } else {
    dropdownStandortContact.classList.add('change-select');
  }
});

const dropdownServiceContact = document.getElementById('field_6tvru');

dropdownServiceContact.addEventListener('change', function() {
  if (dropdownServiceContact.value === "") {
    dropdownServiceContact.classList.remove('change-select');
  } else {
    dropdownServiceContact.classList.add('change-select');
  }
});

// KADA ODABEREMO OPCIJU IZ DROPDOWNA ONDA OPCIJA BUDE BOLD END

</script>




