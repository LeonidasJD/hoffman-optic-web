<?php get_header(); ?>
<!--HERO SECTION START-->
<div class="container-hero-about-us">
    <div class="container-14">
    <section class="about-us-hero-wrapper">
    <div class="about-us-hero-underwrapper">
        <h1>Lorem ipsum dolor sit amet consectetur  consequat vel vivamus tellus.</h1>
    </div>
</section>
    </div>
</div>

<!--HERO SECTION END-->

<!--OUR MISSION SECTION START-->
<div class="container-our-mission-section">
    <div class="container-14">
    <section class="our-mission-section-wrapper">
    <div class="our-mission-section-underwrapper">
        <div class="data-section-info">
            <h2>Our mission is your satisfaction.</h2>
            <p>The Hoffmannoptik team is available to answer any questions you may have about vision, your eye health and the latest eyewear fashions.<br><br>All employees are trained opticians or master opticians who practice their profession with the utmost precision and passion. Each pair of glasses is manufactured with skilled craftsmanship and the highest level of quality awareness, making them unique.</p>
        </div>
        <div class="data-section-info">
            <h2>Holistic eye optics.</h2>
            <p>In our optical competence center, highly qualified optometrists are available for a holistic examination of your eyes. We offer you a careful eyeglass determination from a holistic perspective. Our focus is on analyzing visual perception and improving it through glasses, contact lenses or magnifying visual aids (magnifying glasses).Our opticians will be happy to advise you on current eyewear fashions, the right lenses, sunglasses and contact lenses for you.<br><br> Our eye care program expands the eyeglass assessment to include comprehensive optometric screening. The aim is to identify possible eye defects that are relevant to the prescription, as well as any abnormalities in the eye that require an additional specialist medical examination. Our interdisciplinary collaboration enables us to contribute to the holistic eye health of our customers.</p>
        </div>
        
        
    </div>
    <div class="data-section-img"><img src="/wp-content/uploads/2024/06/slika.webp" ></div>
</section>
    </div>
</div>

<!--OUR MISSION SECTION END-->

<!--CORE OF OUR WORK  SECTION START-->
<div class="container-core-of-work-section">
    <div class="container-14">
    <section class="our-work-section-wrapper">
    <div class="our-work-section-underwrapper">
        <div class="our-work-section-heading-wrapper"><h2>The core of our work:<br>The 4 values ​​of holistic eye optics</h2></div>
        <div class="our-work-section-data-wrapper">
        <div class="our-work-data" >
            <img src="/wp-content/uploads/2024/05/ion_eye-outline.png">
            <h3>Good vision from a single source</h3>
            <p>From the eye exam to the finished pair of glasses –the focus of our philosophy is the closely coordinated collaboration between our optometrists and opticians.</p>
        </div>
        <div class="our-work-data-divider"></div>
        <div class="our-work-data" >
            <img src="/wp-content/uploads/2024/05/solar_medal-star-outline.png">
            <h3>Qualifications, experience & quality awareness</h3>
            <p>Due to their in-depth training and many years of experience, our optometrists can help with a variety of visual defects and vision problems and provide you with the best possible care with contact lenses and glasses.</p>
        </div>
        <div class="our-work-data-divider"></div>
        <div class="our-work-data" >
            <img src="/wp-content/uploads/2024/05/ri_heart-2-line.png">
            <h3>Service is our passion</h3>
            <p>Our heart beats for the health trade of optics, your satisfaction is our top priority. Our offers also include numerous free services for your glasses and contact lenses.</p>
        </div>
        <div class="our-work-data-divider"></div>
        <div class="our-work-data" >
            <img src="/wp-content/uploads/2024/05/mdi_partnership-outline.png">
            <h3>Successful with strong partners</h3>
            <p>With lenses from Essilor and Hoya, as well as contact lenses from Hecht, Wöhlk, Bausch and Lomb, we rely on the highest quality and innovation. In our range of glasses you will always find a large selection from the current collections of well-known eyewear manufacturers.Tradition - Innovation - Partnership</p>
        </div>
        </div>
        
        
    </div>
</section>
    </div>
</div>

<!--CORE OF OUR WORK  SECTION END-->

<!--OUR TEAM  SECTION START-->
<div class="section-divider"><img src="/wp-content/uploads/2024/06/ellipse_11-1.webp" alt=""></div>
<div class="container-our-team-section">
    <div class="container-14">
    <section class="our-team-section-wrapper">
    <div class="our-team-section-under-wrapper">
        <div class="our-team-heading-wrapper">
            <h1>Our Team</h1>
        </div>
        <div class="our-team-members-wrapper">

        <?php 
        $our_team_args = array(
            'post_type' => 'our-team',
            'post_per-page' => '-1',
            'order' => 'ASC',
        );
        $team_members = new WP_query($our_team_args);
        $index = 1; 
        
        if($team_members->have_posts()):
            echo '<div class="team-members-cards">';
        while($team_members->have_posts()):
            $team_members->the_post();

            $member_image = get_field('member_image');
            $member_name = get_field('member_name');
            $member_occupation = get_field('occupation');
            $member_title = get_field('title');
            $extra_class = $index > 3 ? 'hidden' : '';

            ?> 
            <div class="single-team-member-card <?php echo $extra_class; ?>" data-index="<?php echo $index; ?>" >
                
                <div class="team-member-image">
                    <img src="<?php echo $member_image ?>" alt="">
            </div>
            <div class="single-team-member-info">
            <div class="team-member-name"><h3><?php echo $member_name ?></h3></div>
            <div class="team-member-occupation"><p><?php echo $member_occupation ?></p></div>
            <div class="team-member-title"><p><?php echo $member_title ?></p></div>
            </div>
            
            </div>
            <?php
        $index ++;
        endwhile;
        echo '</div>';
        endif;
        ?>
        </div>
        <div  class="more-team-button-wrapper">
            <button id="more-team-btn">
            <span id="more-team-text">Show more</span>
            <svg id="more-team-svg" width="13" height="9" viewBox="0 0 13 9" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path id="more-team-img" fill="black" d="M6.5 8.5L0 0.5L13 0.500001L6.5 8.5Z" />
            </svg>
            </button>
        </div>
    </div>
</section>
    </div>
</div>

<!--OUR TEAM  SECTION END-->

<!--CONTACT US GREEN BANNER SECTION START-->
<div class="container-banner-section">
    <div class="container-14">
    <section class="contact-us-banner-wrapper">
<?php  get_template_part('template-parts/contact-us-green'); ?>
</section>
    </div>
</div>

<!--CONTACT US GREEN BANNER SECTION START-->

<!-- STICKY BACK TO TOP BUTTON START -->
<?php get_template_part('template-parts/sticky-button'); ?>
<!-- STICKY BACK TO TOP BUTTON END -->

<!-- MOBILE SIDE BANNER START -->
<?php get_template_part('template-parts/mobile-side-banner'); ?>
<!-- MOBILE SIDE BANNER END -->

<?php get_footer(); ?>

<script>
    
var moreTeamBtn = document.getElementById('more-team-btn');
var moreTeamSvg =document.getElementById('more-team-svg');
var isExpanded = false;



moreTeamBtn.addEventListener('click', function() {
    var hiddenMembers =document.querySelectorAll('.single-team-member-card[data-index]');
   hiddenMembers.forEach(function(member){
    if (member.dataset.index > 3) {
                if (isExpanded) {
                    member.classList.add('hidden');
                } else {
                    member.classList.remove('hidden');
                }
            }

   });
   
   
   isExpanded = !isExpanded;
   moreTeamBtn.querySelector('#more-team-text').textContent = isExpanded ? 'Show less' : 'Show more';

   if(isExpanded){
    moreTeamSvg.style.transform = 'rotate(180deg)';
   }else{
    moreTeamSvg.style.transform = 'rotate(0deg)';
   }
});
</script>