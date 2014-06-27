/**
 * Created by Robert on 6/26/14.
 */
//$("#box_a").css({left:'-200px'});

function setPlayer(x, y, item) {
    var id = $(item).attr("id");
    $("#" + id + "_xcoord").html(x);
    $("#" + id + "_ycoord").html(y);
    $(item).data("current_pos", {
        x: x,
        y: y
    });
    $(item).data("current_speed", 1000);

    if (id == "ball") {
        $("#move_choice").css({
            "left": x + 8,
            "top": y + 5
        });
        $("#move_choice").show();
    } //if ball

} //fn setPlayer

function setBallSpeed(choice) {
    switch (choice) {
        case "pass_shot":
            //console.log("pass/shot chosen");
            $("#ball").data("current_speed", 500);
            break; //pass/shot

        default:
        case "dribble":
            //console.log("dribble chosen");
            $("#ball").data("current_speed", 1000);
            break; //default, dribble
    } //switch
    $("#move_choice").hide();
} //fn setBallSpeed

function setPlayerCoordTable() {
    $("div.player").each(function (index) {
        var name = $(this).html();
        var id = $(this).attr("id");
        name = name == "#" ? "ball" : name;
        $(this).data("current_pos", {
            x: 0,
            y: 0
        });
        $(this).data("current_speed", 1000);
        $(this).data("playSet", {
            start: {
                x: 0,
                y: 0,
                s: 0
            }
        });
        $("#player_coords").append("<tr><td>" + name + "</td><td><span id='" + id + "_xcoord'></span></td><td><span id='" + id + "_ycoord'></span></td></tr>");
    }); //each fn
} //setPlayerCoordTable

function setCurrentPos() {
    uniquePlaySetName = String((new Date()).getTime());
    var strLog = "<tr><td>"+String(uniquePlaySetName)+"</td><td>";
    //console.log(strLog);
    //nsole.log("outside" + uniquePlaySetName);
    $("div.player").each(function (index) {
        var player_pos = $(this).data("current_pos");
        var player_speed = $(this).data("current_speed");
        var name = $(this).html();
        var playSetObj = $(this).data("playSet");
        playSetObj[uniquePlaySetName] = {
            x: player_pos.x,
            y: player_pos.y,
            s: player_speed
        };
        strLog = strLog + ""+String(name)+":(x:"+String(player_pos.x)+", y:"+String(player_pos.y)+", s:"+String(player_speed)+")<br/>";
        //console.log(strLog);
        //nsole.log("inside" + uniquePlaySetName);
        $(this).data("playSet", playSetObj);
        //console.log(String(name) + " =  x:" + player_pos.x + " y:" + player_pos.y + " s:" + player_speed);
    }); //each fn
    var playListArr = $("#playList").data("list");
    playListArr.push(uniquePlaySetName);
    $("#playList").data("list", playListArr);
    strLog = strLog + "<hr/></td></tr>";
    $("#setLog").append(strLog);
    //console.log(strLog);
    $("#gamefield").fadeOut("fast").fadeIn("fast");

    //console.log(playListArr);
} //fn setCurrentPos



function move(item, x, y, speed) {
    currentx = $(item).css("left");
    currenty = $(item).css("top");
    left_val = parseInt(x, 10);
    top_val  = parseInt(y, 10);
    console.log("moving:"+String($(item).html()),left_val, top_val,speed);
    $(item).animate({
        'left': left_val,
        'top': top_val
    }, {
        duration: speed,
        complete: function () {
            //alert("done");
        }
    });
} //move


function positionPlayer(item, x, y, speed) {
    var left_val = parseInt(x, 10);
    var top_val = parseInt(y, 10);
    $(item).css({"left":left_val,"top":top_val});
} //positionPlayer

function runSet(idx) {
    var playListArr = $("#playList").data("list");
    var arrayOfPlayers = new Array();
    $("div.player").each(function (index) {
        arrayOfPlayers.push($(this).attr('id'))
    });

    idx = isNaN(idx) ? 0 : idx;
    idx = idx < 0 ? 0 : idx;
    console.log("executing runSet("+idx+")");
    if(idx < playListArr.length)
    {
        for (var j = 0; j < arrayOfPlayers.length; j++) {
            var playerId = "#" + arrayOfPlayers[j];
            //console.log("i="+i+"  j="+j+"  playerId="+playerId);
            var currPlayIndex = playListArr[idx];
            var playSetObj = $(playerId).data("playSet");
            var coordsObj = playSetObj[currPlayIndex];
            //console.log(coordsObj);

            if(currPlayIndex == "start")
            {
                positionPlayer(playerId, coordsObj.x, coordsObj.y, coordsObj.s);
            }
            move(playerId,coordsObj.x,coordsObj.y,coordsObj.s);

        } // j loop players
        var delay = 1000; //(idx)*250;
        idx++;
        setTimeout(function(){runSet(idx)},delay);
    }else{
        console.log("end of runSet");
    }

} //fn runSet


function dumpPlayList() {
    var playListArr = $("#playList").data("list");
    console.log(playListArr);
    $("div.player").each(function (index) {
        var id = $(this).attr('id');
        var name = $(this).html();
        var playSetObj = $(this).data("playSet");
        console.log(id + " : " + name + "=== >>");
        console.log(playSetObj);
    }); //each

} //fn dumpPlayList


function RecordPlay(){
    if($('#record_button').html() == 'Start Rec')
    {
        setInterval("if($('#record_button').html() != 'Start Rec'){setCurrentPos();}", 10000);
        setTimeout("if($('#record_button').html() != 'Start Rec'){RecButtonAnim(9);}", 1000);
        $('#record_button').html('Rec ON (10 sec)');
    }
    else
    {
        $('#record_button').html('Start Rec');
    }
}//Rec

function RecButtonAnim(num){
    if($("#record_button").html() != 'Start Rec'){
        num--;
        if(num < 0){num=10;}
        $('#record_button').html('Rec ON ('+num+' sec)');
        setTimeout("RecButtonAnim("+num+")", 1000);
    }
}//RecButtonAmin

