$(function() {
        function addEditor(a, b, c) {
            if (document.selection) {
                a.focus();
                sel = document.selection.createRange();
                c ? sel.text = b + sel.text + c: sel.text = b;
                a.focus()
            } else {
                if (a.selectionStart || a.selectionStart == "0") {
                    var d = a.selectionStart;
                    var e = a.selectionEnd;
                    var f = e;
                    c ? a.value = a.value.substring(0, d) + b + a.value.substring(d, e) + c + a.value.substring(e, a.value.length) : a.value = a.value.substring(0, d) + b + a.value.substring(e, a.value.length);
                    c ? f += b.length + c.length: f += b.length - e + d;
                    if (d == e && c) {
                        f -= c.length
                    }
                    a.focus();
                    a.selectionStart = f;
                    a.selectionEnd = f
                } else {
                    a.value += b + c;
                    a.focus()
                }
            }
        }
        var g = document.getElementById("comment") || 0;
        var h = {
            quote: function() {
                addEditor(g, "<blockquote>", "</blockquote>")
            },
            ahref: function() {
                var a = prompt("请输入链接地址", "http(s)://");
                var b = prompt("请输入要显示成文字链接的描述", "");
                if (a) {
                    addEditor(g, '<a target="_blank" href="' + a + '" rel="external nofollow">' + b + "</a>", "")
                }
            },
        };
        window["SIMPALED"] = {};
        window["SIMPALED"]["Editor"] = h
});
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