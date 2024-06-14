<!-- Contact us green template part-->

<div class="contact-us-banner-underwrapper">
    <h2>If you have questions, feel free to contact us anytime</h2>
    <!-- <span><a href="/contact-us/">Contact us</a> <img src="/wp-content/uploads/2024/05/bi_send-fill.png" alt=""></span> -->
    <button id="contact-button" class="contact-btn">
  <div class="svg-wrapper-1">
    <div class="svg-wrapper">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        width="24"
        height="24"
      >
        <path fill="none" d="M0 0h24v24H0z"></path>
        <path
          fill="currentColor"
          d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"
        ></path>
      </svg>
    </div>
  </div>
  <span>Contact us</span>
</button>
      
</div>

<script>
var contactButton =document.getElementById('contact-button');
contactButton.addEventListener('click', function(){
    window.location.href = '/kontakt/';
});
</script>