<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Plays - ReadyStats</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/pepper-grinder/jquery-ui.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.readystats.com/img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.readystats.com/img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.readystats.com/img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="http://www.readystats.com/img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="http://www.readystats.com/img/favicon.png">
  -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/movement.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
<style>

    #result, table, td, div{
        font-size:.9em;
        font-family: arial;
    }
    table
    div#move_choice div.animate_speed {
        width:45px;
        font-size:14pt;
        cursor:pointer;
        background-color:#fff;
        margin: 2px;
    }
    div#move_choice div.animate_speed:hover {
        border:1px solid #000;
        background-color:#fff;
    }
    div#move_choice{
        background-color:#fff;
        width:100px;
        font-size:14pt;
        cursor:pointer;
        background-color:#fff;
        margin: 2px;
    }

    div.player {
        text-align:center;
        width:20px;
        border:2px solid;
        border-radius:25px;
        background-color:#CC6600;
        position:absolute;
        left:45px;
        top:100px;
        color:#fff;
        border-color:#888;
        cursor:move;
    }
    div#ball {
        width:10px;
        height:10px;
        font-size:5pt;
        border:2px dotted;
        border-radius:25px;
        background-color:#777;
    }
    .small-button {
        font-size: .8em !important;
    }
    .ui-button-text-only .ui-button-text {
        font-size: .7em !important;
        padding: 0px 0px 0px 0px;
    }
</style>
<script>
    $(document).ready(function () {
        $("#content_nav").hide();
        $("#playList").data("list", ["start"]);
        $("#move_choice").hide();
        $("#rec_overlay").removeClass("overlay",3000);
        $("div.player").draggable();
        $("div#move_choice div.animate_speed").click(function () {
            setBallSpeed($(this).attr('id'))
        });
        $("div.player").on("dragstop", function (event, ui) {
            setPlayer(ui.position.left, ui.position.top, this);
        });

        SetStartingPositions();

        setPlayerCoordTable();

        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
        $("#moveforward_button").button({icons: {primary: "ui-icon-seek-next"},text:true});
        $("#moveback_button").button({icons: {primary: "ui-icon-seek-prev"},text:true});
        $("#set_button").button({icons: {primary: "ui-icon-plusthick"},text:true});


        $(".buttonevent")
            .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
            .click(function (event) {
                event.preventDefault();
                //console.log(this);
                switch ($(this).attr("id")) {
                    case "set_button":
                        setCurrentPos();
                        break;

                    case "move_button":
                        console.log("clicked 'move'");
                        runSet();
                        break;

                    case "log_button":
                        dumpPlayList();
                        break;

                    case "record_button":
                        RecordPlay();
                        break;

                    default:
                        break;
                } //switch
            }); //click

    }); //ready



</script>
</head>

<body>
<div class="container-fluid">
	<div id="content_nav" class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">Home</a>
				</li>
				<li>
					<a href="#">Profile</a>
				</li>
				<li class="disabled">
					<a href="#">Messages</a>
				</li>
				<li class="dropdown pull-right">
					 <a href="#" data-toggle="dropdown" class="dropdown-toggle">Dropdown<strong class="caret"></strong></a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">Action</a>
						</li>
						<li>
							<a href="#">Another action</a>
						</li>
						<li>
							<a href="#">Something else here</a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="#">Separated link</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="row-fluid">
		<div id="gamefield" class="span8" style="position:relative; background-image: url('fields/Soccer3v3.png'); background-repeat: no-repeat; height:500px; width:675px; background-position:center; background-size:95%; ">
            <div class="player" id="box_a" style="position:relative; left:310px; top:110px;">A</div>
            <div class="player" id="box_b" style="position:relative; left:313px; top:214px;">B</div>
            <div class="player" id="box_c" style="position:relative; left:167px; top:158px;">C</div>
            <div class="player" id="box_d" style="position:relative; left:384px; top:116px; background-color: #000; color:#fff;">X</div>
            <div class="player" id="box_e" style="position:relative; left:406px; top:148px; background-color: #000; color:#fff;">X</div>
            <div class="player" id="box_f" style="position:relative; left:379px; top:176px;  background-color: #000; color:#fff;">X</div>
            <div class="player" id="ball" style="left:327px; top:252px;">#</div>
            <div style="bottom:20px; right:240px; position: absolute;">
                <button class="buttonevent" id="moveback_button">Prev</button>
                <button class="buttonevent" id="move_button">Play</button>
                <button class="buttonevent" id="moveforward_button">Next</button>
                <!--<button class="btn-small" id="record_button">Start Rec</button>-->
            </div>
            <div style="position: absolute; left:300px; top:35px;">
                <button class="buttonevent" id="set_button">Set Move</button>
                <!--<button class="btn-small" id="record_button">Start Rec</button>-->
            </div>
            <div id="move_choice" style="position:absolute">
                <div class="animate_speed" id="pass_shot">pass/shot</div>
                <div class="animate_speed" id="dribble">dribble</div>
            </div>
			 <!--<img alt="Soccer3v3" src="fields/Soccer3v3.png" class="img-rounded">-->
		</div>
		<div class="span4">
            <table  border="1" cellpadding="5" cellspacing="0"  width="100%" id="setLog">
                <tr><th>Set Name</th><th>Moves</th></tr>
                <tr><td>Start</td><td>12:(x:0, y:0, s:0)<br/>17:(x:0, y:0, s:0)<br/>24:(x:0, y:0, s:0)<br/>ball:(x:0, y:0, s:0)<br/><hr/></td></tr>
            </table>
			<form>
				<fieldset>
					 <legend>Legend</legend> <label>Label name</label><input type="text"> <span class="help-block">Example block-level help text here.</span> <label class="checkbox"><input type="checkbox"> Check me out</label> <button type="submit" class="btn">Submit</button>
				</fieldset>
			</form>
			<ol>
				<li>
					Lorem ipsum dolor sit amet
				</li>
				<li>
					Consectetur adipiscing elit
				</li>
				<li>
					Integer molestie lorem at massa
				</li>
				<li>
					Facilisis in pretium nisl aliquet
				</li>
				<li>
					Nulla volutpat aliquam velit
				</li>
				<li>
					Faucibus porta lacus fringilla vel
				</li>
				<li>
					Aenean sit amet erat nunc
				</li>
				<li>
					Eget porttitor lorem
				</li>
			</ol>
		</div>
	</div>
</div>

<div id="playList" style="visibility:hidden"></div>
<table style="visibility:hidden" border="1" cellpadding="5" cellspacing="0" id="player_coords">
    <tr>
        <th>Player</th>
        <th>x coord</th>
        <th>y coord</th>
    </tr>
</table>
</body>
</html>
