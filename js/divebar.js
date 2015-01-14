var $body    = $("body");
var $accueil = $("div#accueil");
var $accueil_menu = $("div#accueil_menu");
var $div_lyrics = $("div.lyrics");
var $message = $("div#message");
var $menu    = $("div#menu");
var $menus   = $("div.menu");
var $li_menu = $("li.menu");
var $hr_menu = $("hr#menu");
var $p_random = $("p.random");
var $random_msg = function() {
    var $random = Math.floor(Math.random() * $p_random.length);
    $p_random.hide()
	     .eq($random).show();
    $("div#message").show();
};

var $data, $last_menu, $is_accueil = 1;

// mise en place du slider
$(window).load(function() {
    // on gère la taille de la fenêtre
    var $width = $(window).width();
    var $height = parseInt($width * 0.36);

    $accueil.css({'height': $height,'width': $width});
    $("li.bg").css({'height': $height,'width': $width});
    var $logos = $("img.logo");
    var $logo_generic = $("img#generic");

    $logos.css({'height': $height / 3,'width': $width / 3});

    var $slide = $("div.banner").unslider({
        speed: 1000,
        delay: 8000,
        keys: true,
        fluid: false,
    });

    // on démarre le slider
    $data = $slide.data("unslider");
    $data.start();

    // on affiche le message aléatoire
    $random_msg();

    var $handle_color = function ($background, $border) {
	$li_menu.animate({'background-color': $background}, 2000);
	$body.animate({'background-color': $background}, 2000);
	$hr_menu.animate({'background-color': $background}, 2000);
    }

    $(".start").on("click", function() {
	$is_accueil = 1;
	$last_menu = null;
	$handle_color('#3F0000', '#EDC190');
	$logos.hide();
	$logo_generic.show();
	$accueil.slideDown();
	$menu.hide();
	$menus.hide();
	$data.start();
	$accueil_menu.show();
	$message.show();
    });

    var $load_photos = function($div_photos) {
	$div_photos.nanoGallery({
	    thumbnailWidth: 'auto',
	    thumbnailHeight: 300,
	    kind: 'picasa',
	    userID: '112854609172007422557',
	    thumbnailAlignment: 'justified',
	    thumbnailHoverEffect: 'borderLighter,labelAppear75',
	    thumbnailGutterWidth: 0,
	    thumbnailGutterHeight: 0,
	    thumbnailLabel: {
		display: true,
		position: 'overImageOnMiddle',
		hideIcons: true,
		align: 'center',
	    },
	    i18n: {
		thumbnailLabelItemsCountPart1: '',
		thumbnailImageDescription: 'click to open'
            },
	    colorScheme: 'none',
	    galleryToolbarWidthAligned: true,
	    galleryToolbarHideIcons: true,
	    galleryFullpageButton: true,
	});
    }

    $li_menu.on("click", function() {
	var $name = $(this).attr('id');
	var $new_menu = $("div#" + $name);

	$logos.hide();
	var $logo_actual = $("img#" + $name);
	if ($logo_actual.length) {
	    $logo_actual.show();
	} else {
	    $logo_generic.show();
	}

	// on ne change pas le menu
	if ($new_menu.is($last_menu) || $name === 'accueil') {
            return;
	}

	// on cache les paroles
	$div_lyrics.hide();

	// on gère le cas particulier de l'accueil
	if ($is_accueil) {
	    $random_msg();
	    $message.hide();
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

	// on gère le cas particulier des photos
	if ($load_photos != null && $name === "photos") {
	    $load_photos($new_menu);
	    $load_photos = null;
	}

	$menu.show();

	if ($last_menu != null) {
	    $last_menu.slideUp(1000);
	}

	$new_menu.slideDown(1000);
	$last_menu = $new_menu;
    });

    // dans l'onglet members : on diminue la taille du titre pour
    // qu'il n'empiète pas sur l'image
    if ($width < 500) {
	$("h2.name").css({
	    "font-size": "15px",
	});

	$("div.biography").css({
	    "position": "relative",
	    "width": "80%",
	});
    }

    // on s'occupe de l'animation des membres
    var $is_already_enter;
    var $members = $("li.members");
    var $members_to_hide = $members.children().not('h2');
    var $height_members = parseInt($width * 0.5);
    console.log($height_members);

    // par défault tous les membres sont cachés, sauf le nom du membre
    $members_to_hide.hide();

    $members.on("click", function() {
	if ($(this).is($is_already_enter)) {
	    return;
	}

	if ($is_already_enter != null) {
	    $is_already_enter.css({ "background-image": "" });
	    $is_already_enter.animate({ "padding-bottom": "0" }, 500);
	}

	$is_already_enter = $(this);

	var $id = $is_already_enter.attr('id');

	$is_already_enter.css({
	    "background-image": "url('img/members/" + $id + ".jpg')",
	    "background-size": $width + "px " + $height_members + "px",
	});

	$is_already_enter.animate({ "padding-bottom": "150px" }, 500);

	$members_to_hide.hide();
	$is_already_enter.children().fadeIn(500);
    });

    var $h2_lyrics = $("h2.lyrics");
    var $div_lyrics = $("div.lyrics");

    $h2_lyrics.on("click", function() {
	$div_lyrics.hide();
	console.log("CLICK " + $(this).next("div").attr('class'));
	$(this).next("div").fadeIn(1000);
    });

    var $contact_result = $("div#contact_result");
    var $contact = $("input#contact");

    $contact_result.hide();

    $contact.on("click", function() {

	console.log("HERE??" + $("form#contact").serialize());

	$.ajax({
	    type: "POST",
	    url: "php/contact.php",
	    data: $("form#contact").serialize(),
	    dataType: "json",
	    success: function($result) {
		console.log("HEY!" + $result);
		/* $contact_result.empy();
		   $contact_result.append($result);
		   $contact_result.show(); */
	    },
	    error: function($obj, $msg, $error) {
		console.log("ERROR! " + $msg + " " + $error);
	    },
	});
    });
});
