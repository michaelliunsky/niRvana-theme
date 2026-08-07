/*!
 * niRvana theme source - pandaSlider.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解并补齐 IIFE 括号. 压缩丢失注释与局部变量名.
 */
(function(e) {
  e.fn.pandaSlider = function(t, n, i) {
    var o = {
      init: function(t) {
        if (t.data("pandaSlider_init")) return !1;
        t.data("callbacks", i);
        var n = e(t).attr("prev-text"),
          r = e(t).attr("next-text"),
          s = e(t).attr("navigator-wrap-class") || "";
        if (void 0 !== n && !1 !== n || void 0 !== r && !1 !== r) {
          t.append('<div class="navigator ' + s + '">');
          var a = t.children(".navigator");
          void 0 !== n && !1 !== n && (a.append('<div class="prev">' + n + "</div>"), a.children(".prev").on("click", function(e) {
            o.prev(t)
          })), void 0 !== r && !1 !== r && (a.append('<div class="next">' + r + "</div>"), a.children(".next").on("click", function(e) {
            o.next(t)
          }))
        }
        var l = e(t).attr("show-anchor"),
          c = e(t).attr("anchor-wrap-class") || "";
        if (void 0 !== l && !1 !== l) {
          t.append('<div class="anchor ' + c + '">');
          for (var u = 0; u < t.children(".page").length; u++) {
            var d = e("<div>");
            e(t.children(".page")[u]).attr("anchor-background-image") && d.css("background-image", "url(" + e(t.children(".page")[u]).attr("anchor-background-image") + ")"), e(t.children(".page")[u]).attr("anchor-background-color") && d.css("background-color", e(t.children(".page")[u]).attr("anchor-background-color")), t.children(".anchor").append(d)
          }
        }
        var h = e(t).attr("allow-anchor-click");
        void 0 !== h && !1 !== h && t.children(".anchor").children().on("click", function(n) {
          var i = e(this).index();
          o.jumpTo(t, i)
        }), o.parse_anchor_with_currentIndex(t), t.data("pageSize", t.children(".page").length);
        var p = e(t).attr("showbox-wrap-class") || "",
          f = e('<div class="showBox ' + p + '">'),
          m = 2 * parseInt(e(t).attr("view")) + 1,
          g = Math.ceil(m / e(t).children(".page").length) - 1;
        for (u = 0; u < g; u++) f.append(e(t).children(".page").clone());
        for (f.append(e(t).children(".page")), u = f.children(".page").length - 1; 0 <= u; u--) {
          var v = e('<div class="hidden">');
          v.append(f.children(".page")[0]), f.append(v)
        }
        t.prepend(f), t.data("currentIndex", 0), o.fill_pages_with_currentIndex(t);
        var b = e(t).attr("allow-keyboard");
        void 0 !== b && !1 !== b && e(document).keyup(function(e) {
          switch (e.keyCode || e.which) {
            case 37:
            case 38:
              o.prev(t);
              break;
            case 39:
            case 40:
              o.next(t)
          }
        });
        var y = e(t).attr("allow-swipe");
        void 0 !== y && !1 !== y && (t.children(".showBox").on("swiperight", function(n) {
          o.prev(t);
          var i = e(t).attr("hover-disable-interval");
          void 0 !== i && !1 !== i && (o.interval(t, !1), window.setTimeout(function() {
            o.interval(t, !0)
          }))
        }), t.children(".showBox").on("swipeleft", function(n) {
          o.next(t);
          var i = e(t).attr("hover-disable-interval");
          void 0 !== i && !1 !== i && (o.interval(t, !1), window.setTimeout(function() {
            o.interval(t, !0)
          }))
        })), o.interval(t, !0);
        var w = e(t).attr("hover-disable-interval");
        void 0 !== w && !1 !== w && t.hover(function() {
          o.interval(t, !1)
        }, function() {
          o.interval(t, !0)
        }), t.attr("init", ""), t.data("pandaSlider_init", !0), o.callback_onInit(t)
      },
      fill_pages_with_currentIndex: function(t) {
        var n = t.data("currentIndex"),
          i = parseInt(e(t).attr("view")),
          o = t.children(".showBox").children("div"),
          r = [];
        o.each(function() {
          r.push("hidden")
        }), r[n] = "current";
        for (var s = 1; s <= i; s++) {
          if (n + s < o.length) var a = n + s;
          else a = n + s - o.length;
          r[a] = "future" + (s - 1)
        }
        for (s = 1; s <= i; s++) a = 0 <= n - s ? n - s : o.length + n - s, r[a] = "past" + (s - 1);
        o.each(function(t, n) {
          e(n).attr("class", r[t])
        })
      },
      interval: function(t, n) {
        if (n) {
          var i = e(t).attr("interval-time");
          if (void 0 !== i && !1 !== i) {
            var r = parseInt(i);
            r && t.data("interval", window.setInterval(function() {
              o.next(t)
            }, r))
          }
        } else clearInterval(t.data("interval"))
      },
      parse_anchor_with_currentIndex: function(t) {
        var n = e(t).attr("show-anchor");
        if (void 0 === n || !1 === n) return !1;
        var i = t.find(".anchor").children(),
          o = t.data("currentIndex") || 0,
          r = t.data("pageSize") || 1,
          s = e(i[o % r]);
        i.removeClass("active"), s.addClass("active"), window.setTimeout(function() {
          var e = t.children(".anchor").outerWidth(),
            n = s.outerWidth(),
            i = s.position().left + t.children(".anchor").scrollLeft() - (e - n) / 2;
          i = (i = i > t.children(".anchor").get(0).scrollWidth - e ? t.children(".anchor").get(0).scrollWidth - e : i) < 0 ? 0 : i;
          var o = t.children(".anchor").outerHeight(),
            r = s.outerHeight(),
            a = s.position().top + t.children(".anchor").scrollTop() - (o - r) / 2;
          a = (a = a > t.children(".anchor").get(0).scrollHeight - o ? t.children(".anchor").get(0).scrollHeight - o : a) < 0 ? 0 : a, t.children(".anchor").animate({
            scrollTop: a,
            scrollLeft: i
          }, 300)
        }, 16)
      },
      next: function(e) {
        if (!e.data("pandaSlider_init")) return !1;
        var t = e.data("currentIndex") + 1;
        t = t > e.children(".showBox").children("div").length - 1 ? 0 : t, e.data("currentIndex", t), o.fill_pages_with_currentIndex(e), o.parse_anchor_with_currentIndex(e), o.callback_onChange(e)
      },
      prev: function(e) {
        if (!e.data("pandaSlider_init")) return !1;
        var t = e.data("currentIndex") - 1;
        t = t < 0 ? e.children(".showBox").children("div").length - 1 : t, e.data("currentIndex", t), o.fill_pages_with_currentIndex(e), o.parse_anchor_with_currentIndex(e), o.callback_onChange(e)
      },
      jumpTo: function(e, t) {
        if (!e.data("pandaSlider_init")) return !1;
        var n = e.children(".showBox").children("div").length,
          i = e.data("pageSize"),
          r = e.data("currentIndex");
        if (i - 1 < t || t < 0) return console.log("Index不在范围内"), !1;
        for (var s = [], a = 0; a < n / i; a++) s.push(a * i + t);
        var l = [];
        for (a = 0; a < s.length; a++) {
          var c = s[a],
            u = Math.abs(r - c),
            d = n - u,
            h = Math.min(u, d);
          if (h == u) var p = c <= r ? "-" : "+";
          else p = c <= r ? "+" : "-";
          l.push({
            step: h,
            towards: p
          })
        }
        var f = l[0];
        for (a = 0; a < l.length; a++) l[a].step < f.step ? f = l[a] : l[a].step == f.step && "+" == l[a].towards && (f = l[a]);
        ! function e(t, n, i, o) {
          0 < i && !o && (t(), i--, window.clearTimeout(window.jumping_timeout)), 0 < i && (window.jumping_timeout = window.setTimeout(function() {
            t(), e(t, n, i - 1, !0)
          }, n))
        }(function() {
          "+" == f.towards ? o.next(e) : o.prev(e)
        }, parseInt(e.attr("jump-transition-time")) || 200, f.step)
      },
      callback_onChange: function(e) {
        var t = e.data("currentIndex"),
          n = t % (e.data("pageSize") || 1),
          i = e.children(".showBox").find(".page")[n],
          o = e.data("callbacks");
        o && o.onChange && o.onChange(t, i)
      },
      callback_onInit: function(e) {
        var t = e.data("currentIndex"),
          n = t % (e.data("pageSize") || 1),
          i = e.children(".showBox").find(".page")[n],
          o = e.data("callbacks");
        o && o.onInit && o.onInit(t, i)
      }
    };
    switch (t) {
      case "next":
        o.next(this);
        break;
      case "prev":
        o.prev(this);
        break;
      case "jumpTo":
        o.jumpTo(this, n);
        break;
      case "init":
      default:
        o.init(this)
    }
  }
}(jQuery))
