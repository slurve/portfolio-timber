jQuery(document).ready(function($) {
  /* dropdown menu */
  $(".nav-main")
    .find(".menu-item-has-children")
    .each(function() {
      $(this).mouseenter(function() {
        $(this)
          .addClass("active")
          .find(".nav-drop")
          .stop(true, true)
          .delay(100)
          .fadeIn(300);
      });
      $(this).mouseleave(function() {
        $(this)
          .removeClass("active")
          .find(".nav-drop")
          .stop(true, true)
          .hide();
      });
    });

  /* tooltip */
  $(".tip").hover(
    function() {
      $(this)
        .children(".tip-text")
        .css("visibility", "visible")
        .attr("aria-expanded", "true");
    },
    function() {
      $(this)
        .children(".tip-text")
        .css("visibility", "hidden")
        .attr("aria-expanded", "false");
    }
  );

  /* open menu */
  $(".nav-open").on("click", function(e) {
    if ($(".nav-main").hasClass("active")) {
      $(".nav-main").removeClass("active");
    } else {
      $(".nav-main").addClass("active");
    }
    e.preventDefault();
  });

  /* slider */
  $(".slider").slick({
    mobileFirst: true,
    arrows: false,
    dots: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: "unslick"
      }
    ]
  });

  /* matrix nav */
  $(".matrix__nav a").on("click", function(e) {
    var current = $(this).attr("class");
    $(this)
      .addClass("current")
      .siblings("a")
      .removeClass("current");
    $(
      ".matrix__data .matrix__indicative, .matrix__data .matrix__amplitude, .matrix__data .matrix__mixpanel, .matrix__data .matrix__kissmetrics"
    ).hide();
    $(".matrix__data ." + current).show();
    e.preventDefault();
  });

  /* smooth scroll anchor links https://css-tricks.com/snippets/jquery/smooth-scrolling/ */
  $("a.smoothscroll").on("click", function(e) {
    e.preventDefault();
    if (
      location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") &&
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
