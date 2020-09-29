jQuery(document).ready(function($) {
  $(window).scroll(function() {
    var y = $(window).scrollTop();
    var d = y / 24;
    $(".block-hero picture:nth-child(3)").css(
      "transform",
      "translate3d(0," + d + "px, 0)"
    );
  });

  /* show nav after 410px */
  if ($(".header").length) {
    var stickyHeaderTop = $(".header").offset().top + 310;
    $(window).scroll(function() {
      if ($(window).scrollTop() > stickyHeaderTop) {
        $(".header").addClass("is-stuck");
      } else {
        $(".header").removeClass("is-stuck");
      }
    });
  }
});
