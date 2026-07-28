$(document).ready(function() {
  // Show button when scrolled down 100px
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('#scrollToTop').addClass('show');
    } else {
      $('#scrollToTop').removeClass('show');
    }
  });

  // Smooth scroll to top on button click
  $('#scrollToTop').click(function(e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, 800); // 800ms duration for smooth animation
  });
});