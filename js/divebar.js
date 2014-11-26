var $body    = $("body");
var $accueil = $("div#accueil");
var $accueil_menu = $("div#accueil_menu");
var $menu    = $("div#menu");
var $menus   = $("div.menu");
var $li_menu = $("li.menu");
var $hr_menu = $("hr#menu");

var $data, $last_menu, $is_accueil = 1;

// mise en place du slider
$(window).load(function() {

    // on gère la taille de la fenêtre
    var $width = $(window).width();
    var $height = parseInt($width * 0.36);

    $accueil.css({'height': $height,'width': $width});
    $("li.bg").css({'height': $height,'width': $width});
    $("img#logo_banner").css({'height': $height / 3,'width': $width / 3});

    var $slide = $("div.banner").unslider({
        speed: 1000,
        delay: 8000,
        keys: true,
        fluid: false,
    });

	<!-- on démarre le slider -->
	$data = $slide.data("unslider");
    $data.start();
});

var $handle_color = function ($background, $border) {
    $li_menu.animate({'background-color': $background}, 2000);
    $body.animate({'background-color': $background}, 2000);
    $hr_menu.animate({'background-color': $background}, 2000);
}

$(".start").on("click", function() {
    $is_accueil = 1;
    $last_menu = null;
    $handle_color('#3F0000', '#EDC190');
    $accueil.slideDown();
    $menu.hide();
    $menus.hide();
    $data.start();
    $accueil_menu.show();
});

$li_menu.on("click", function() {
    var $name = $(this).attr('id');
    var $new_menu = $("div#" + $name);

    // on ne change pas le menu
    if ($new_menu.is($last_menu) || $name === 'accueil') {
        return;
    }

    // on gère le cas particulier de l'accueil
    if ($is_accueil) {
	$accueil_menu.hide();
	$data.stop();
	$accueil.slideUp();
	$is_accueil = 0;
    }

    //on gère le cas particulier des couleurs
    if ($name === "videos" || $name === "photos") {
	$handle_color("#000000", "#000000");
    } else if ($name === "members" || $name === "lyrics") {
	$handle_color("#080224", "#080224");
    } else {
	$handle_color("#3F0000", "#3F0000");
    }

	<!-- on gère le cas particulier des logos -->
	var $logo = $("img#logo_banner");
    if ($name === "photos") {
	$logo.attr("src", "img/photos/logo.jpg");
    } else if ($name === "contact") {
	$logo.attr("src", "img/contact/logo.png");
    } else {
	$logo.attr("src", "img/accueil/logo.png");
    }
    $menu.show();

    if ($last_menu != null) {
	$last_menu.slideUp(1000);
    }

    $new_menu.slideDown(1000);
    $last_menu = $new_menu;
});

var $is_already_enter;
var $members = $("li.members");
var $members_to_hide = $members.children().not('h2');

$members_to_hide.hide();

$members.on("mouseenter", function() {
    if ($(this).is($is_already_enter)) {
	return;
    }

    if ($is_already_enter != null) {
	$is_already_enter.css({ "background-image": "" });
	$is_already_enter.animate({ "padding-bottom": "0px" }, 500);
    }

    $is_already_enter = $(this);

    var $id = $is_already_enter.attr('id');

    $is_already_enter.css({
	"background-image": "url('img/members/" + $id + ".jpg')"
    });

    $is_already_enter.animate({ "padding-bottom": "415px" }, 500);

    $members_to_hide.hide();
    $is_already_enter.children().fadeIn(500);
});
