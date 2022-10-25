jQuery(document).ready(function ($) {
    $(
        ".postLists.lists .card h2,.pf_hotposts h4"
    ).hover(function () {
        $(this).stop().animate({
            "marginLeft": "15px"
        }, 300)
    }, function () {
        $(this).stop().animate({
            "marginLeft": "0px"
        }, 300)
    });
    $(
        ".postLists.lists .card h2,.pf_hotposts h4"
    ).click(function () {
        myloadoriginal = this.text;
        $(this).text("正在努力加载中 …");
        var myload = this;
        setTimeout(function () {
            $(myload).text(myloadoriginal)
        }, 2011)
    }) 
});
function get_let_time() {
    //当前日期
	var curDate = new Date();
	
	//当前时间戳
	var curTime = curDate.getTime();
	
	//当日凌晨的时间戳, 减去一毫秒是为了防止后续得到的时间不会达到00:00:00的状态
	var curStartHours = new Date(curDate.toLocaleDateString()).getTime() - 1;
	
	//当日已经过去的时间（毫秒）
	var passedTime = curTime - curStartHours;
	
	//当日剩余时间
	var leftTamp = 24 * 60 * 60 * 1000 - passedTime;
	var leftTime = new Date();
	leftTime.setTime(leftTamp + curTime);
	return leftTime.toGMTString();
    
}

$(document).ready(function() {
    // $("#canvas").remove();  
    if (document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") === "") {
        if (new Date().getHours() > 18 || new Date().getHours() < 7) {
            document.body.classList.add("night");
            $(".colorSwitch").attr("class", "colorSwitch fa fa-sun");
            // document.cookie = "night=1;path=/;expires=" + get_let_time();
        } else {
            document.body.classList.remove("night");
            $(".colorSwitch").attr("class", "colorSwitch fas fa-moon");
            // document.cookie = "night=0;path=/;expires=" + get_let_time();
        }
    } else {
        var night = document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") || "0";
        if (night == "0") {
            document.body.classList.remove("night");
            $(".colorSwitch").attr("class", "colorSwitch fas fa-moon");
        } else {
            if (night == "1") {
                document.body.classList.add("night");
                $(".colorSwitch").attr("class", "colorSwitch fa fa-sun");
            }
        }
    }
});
function switchNightMode() {
    var night = document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") || "0";
    if (night == "0") {
        document.body.classList.add("night");
        document.cookie = "night=1;path=/;expires=" + get_let_time();
        $(".colorSwitch").attr("class", "colorSwitch fa fa-sun");
    } else {
        document.body.classList.remove("night");
        document.cookie = "night=0;path=/;expires=" + get_let_time();
        $(".colorSwitch").attr("class", "colorSwitch fas fa-moon");
    }
}