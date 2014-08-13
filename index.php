<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dive Bar</title>
    <link rel="stylesheet" type="text/css" href="css/dive_bar.css"></link>
  </head>

  <body>
      <ul id="main_menu">
        <li><a class="left">left</a></li>
        <li><a class="glow">Accueil</a></li>
        <li><a class="glow">Concerts</a></li>
        <li><a class="glow">Albums</a></li>
        <li><a class="glow">Vidéos</a></li>
        <li><a class="glow">Contact</a></li>
      </ul>

      <hr>
      <div class="container" width="1080" height="450"></div>
      <hr>

      <audio class="music" preload loop controls>autoplay
      <source src="sound/intro.mp3"></source>
      </audio>

</body>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/pixi.js"></script>

    <script>
    $('a.glow').on(
	'click', function()
	{
	    console.log('clicked');
	});

var $renderer = PIXI.autoDetectRenderer(1080, 450);
$('div.container').replaceWith($renderer.view);


var $stage = new PIXI.Stage(0x66FF99);



$renderer.render($stage);

requestAnimFrame($animate);

function animate() {

    requestAnimFrame($animate);

    // render the stage
    $renderer.render($stage);
}

</script>

</html>
