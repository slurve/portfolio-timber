jQuery(document).ready(function($) {
  /* show nav after 410px */
  var stickyHeaderTop = $(".header").offset().top + 410;
  $(window).scroll(function() {
    if ($(window).scrollTop() > stickyHeaderTop) {
      $(".header").addClass("is-stuck");
    } else {
      $(".header").removeClass("is-stuck");
    }
  });

  /* smooth scroll anchor links https://css-tricks.com/snippets/jquery/smooth-scrolling/ */
  $("a.smoothscroll").on("click", function(e) {
    e.preventDefault();
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      if (target.length) {
        $("html, body").animate(
          {
            scrollTop: target.offset().top - 30
          },
          1000
        );
        return false;
      }
    }
  });
});
