/*!
 * niRvana theme source - pandaTab.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解并补齐 IIFE 括号. 压缩丢失注释与局部变量名.
 */
(function(e) {
  e.fn.pandaTab = function(t, n) {
    var i = {
      init: function(t, n) {
        var o = e(t).children("ul");
        if (e(o).data("pandaTab_init")) return !1;
        e(o).contents().filter(function() {
          return 3 === this.nodeType
        }).remove();
        var r = e("<li>");
        r.addClass("anchor"), r.css({
          position: "absolute",
          width: "0"
        }), e(o).prepend(r), e(o).data("subMenu_isActive", !1), e(o).data("pandaTab_init", !0);
        var s = e(t).attr("sub-trigger");
        "hover" == s ? i.addHoverEvent(t) : ("click" == s || i.addHoverEvent(t), i.addClickEvent(t)), i.autoScrolling(t, n);
        var a = e(t).attr("native-scrolling");
        void 0 !== a && !1 !== a && i.useNativeScrolling(t), e(window).resize(function(o) {
          i.hideAllSubMenu(t), e(t).children("ul").children("li").removeClass("hover"), window.setTimeout(function() {
            i.parseActivePosition(e(t).children("ul"))
          }, n || 0), void 0 !== a && !1 !== a && i.useNativeScrolling(t)
        }), i.scrollToActivePosition(t), window.setTimeout(function() {
          i.parseActivePosition(e(t).children("ul"))
        }, n || 0)
      },
      parseActivePosition: function(t) {
        if (!e(t).data("pandaTab_init")) return !1;
        var n = e(t).parent().attr("sub-class"),
          i = e(t).children(".anchor");
        if (i.removeClass("has_sub_menu"), 0 < e(t).children(".hover").length) {
          var o = e(t).parent().attr("ignore-class");
          0 < (s = e(t).children(".hover").not(o).first()).children(n).length && i.addClass("has_sub_menu")
        }
        if (s && 0 < s.length);
        else var r = e(t).parent().attr("active-class"),
          s = e(t).children(r);
        0 < s.length ? i.css({
          left: s.position().left + e(t).scrollLeft() + "px",
          width: s.width() + "px",
          opacity: "1"
        }) : i.css({
          opacity: "0"
        })
      },
      showSubMenu: function(t) {
        var n = e(t).parent().parent().attr("ignore-class");
        if (n && e(t).hasClass(n.slice(1))) return !1;
        var o = e(t).parent().parent().attr("sub-class");
        if (0 < e(t).children(o).length) {
          var r = e(t).children(o).clone();
          if (0 == e("body > .pandaTabWrap").length) {
            var s = e("<div>");
            s.addClass("pandaTabWrap"), e("body").append(s)
          }
          r.removeClass("show"), e("body > .pandaTabWrap").append(r);
          var a = e(t).offset().left;
          a + r.outerWidth() > e(window).width() - 5 && (a = e(window).width() - r.outerWidth() - 5), a < 5 && (a = 5), r.css({
            left: a,
            top: e(t).offset().top + e(t).outerHeight()
          }), window.setTimeout(function() {
            r.addClass("show"), r.attr("father-class", e(t).parent().parent().attr("class")), r.attr("father-id", e(t).parent().parent().attr("id"))
          }, 1), r.first().data("father", e(t)), r.off("mouseenter").off("mouseleave"), r.hover(function() {
            e(t).parent().data("subMenu_isActive", !0)
          }, function() {
            if ("click" == e("li").parent().parent().attr("sub-trigger")) return !1;
            i.hideSubMenu(this), i.parseActivePosition(e(t).parent()), e(t).parent().data("subMenu_isActive", !1)
          })
        }
      },
      hideSubMenu: function(t) {
        e(t).removeClass("show"), window.setTimeout(function() {
          e(t).remove(), 0 == e("body > .pandaTabWrap").children().length && e("body > .pandaTabWrap").remove()
        }, 250)
      },
      hideAllSubMenu: function(t) {
        e("body > .pandaTabWrap").children().each(function(n, o) {
          var r = e(t).attr("class") ? e(t).attr("class") : "fatherClass:undefined";
          fatherId = e(t).attr("id") ? e(t).attr("id") : "fatherId:undefined", e(o).attr("father-class") != r && e(o).attr("father-id") != fatherId || i.hideSubMenu(o)
        })
      },
      addHoverEvent: function(t) {
        e(t).children("ul").children("li").not(".anchor").hover(function() {
          e(this).addClass("hover"), i.parseActivePosition(e(this).parent()), i.hideAllSubMenu(t), i.showSubMenu(this), t.attr("li-hovered", "")
        }, function() {
          e(this).removeClass("hover"), t.removeAttr("li-hovered", "")
        }), e(t).mouseleave(function(n) {
          var o = e(this).children("ul");
          window.setTimeout(function() {
            e(o).data("subMenu_isActive") || (i.hideAllSubMenu(t), i.parseActivePosition(o))
          }, 50)
        })
      },
      addClickEvent: function(t) {
        e(t).children("ul").children("li").not(".anchor").click(function() {
          i.hideAllSubMenu(t), e(this).hasClass("hover") || i.showSubMenu(this), e(this).siblings().removeClass("hover"), 0 < e(this).find(e(t).attr("sub-class")).length && e(this).toggleClass("hover"), i.parseActivePosition(e(this).parent())
        }), e(t).children("ul").children("li").each(function(n, i) {
          var o = e(t).attr("sub-class");
          0 < e(i).children(o).length && e(i).children("a").removeAttr("href")
        })
      },
      destroy: function(t) {
        e(t).children("ul").children(".anchor").remove(), e(t).children("ul").children().off("mouseenter").off("mouseleave").off("click"), i.hideAllSubMenu(t), i.hideScrolling(t), i.disableNativeScrolling(t), e(t).children("ul").removeData()
      },
      makeScrolling: function(t) {
        if (!e(t).children("ul").data("pandaTab_init")) return !1;
        if (e(t).children("ul").data("scrolling")) return !1;
        var n = e("<span>").addClass("prev");
        n.append(e(t).attr("prev-text")), e(t).prepend(n);
        var o = e("<span>").addClass("next");
        o.append(e(t).attr("next-text")), e(t).append(o), e(t).children("ul").css({
          marginLeft: e(t).children(".prev").width(),
          marginRight: e(t).children(".next").width()
        }), o.click(function(n) {
          var o = e(t).children("ul"),
            r = o.get(0).scrollWidth - o.get(0).clientWidth,
            s = 400 < o.width() / 2 ? 400 : .75 * o.width(),
            a = 0;
          a = o.scrollLeft() + s > r ? r : r - o.scrollLeft() - s < o.width() / 4 ? r : o.scrollLeft() + s, o.animate({
            scrollLeft: a
          }, 500), i.hideAllSubMenu(t), e(t).children("ul").children("li").removeClass("hover"), i.parseActivePosition(e(t).children("ul"))
        }), n.click(function(n) {
          var o = e(t).children("ul"),
            r = (o.get(0).scrollWidth, o.get(0).clientWidth, 400 < o.width() / 2 ? 400 : .75 * o.width()),
            s = 0;
          s = o.scrollLeft() - r < 0 ? 0 : o.scrollLeft() - r < o.width() / 4 ? 0 : o.scrollLeft() - r, o.animate({
            scrollLeft: s
          }, 500), i.hideAllSubMenu(t), e(t).children("ul").children("li").removeClass("hover"), i.parseActivePosition(e(t).children("ul"))
        }), e(t).children("ul").scroll(function() {
          i.disableScrollingButton(t)
        }), e(t).children("ul").data("scrolling", !0), e(t).attr("has-scrolling-button", "")
      },
      hideScrolling: function(t) {
        e(t).children(".prev,.next").remove(), e(t).children("ul").removeAttr("style"), e(t).children("ul").removeData("scrolling"), e(t).children("ul").off("scroll"), e(t).removeAttr("has-scrolling-button")
      },
      autoScrolling: function(t, n) {
        function o() {
          var n = e(t).children("ul");
          i.parseActivePosition(n), 0 < n.get(0).scrollWidth - n.get(0).clientWidth && i.makeScrolling(t), n.get(0).scrollWidth < e(t).get(0).scrollWidth && i.hideScrolling(t), i.disableScrollingButton(t)
        }
        if (!e(t).children("ul").data("pandaTab_init")) return !1;
        e(window).resize(function(r) {
          window.setTimeout(function() {
            var n = e(t).attr("auto-scrolling");
            void 0 !== n && !1 !== n && o(), i.parseActivePosition(e(t).children("ul"))
          }, 2 * n || 0)
        }), o()
      },
      disableScrollingButton: function(t) {
        var n = e(t).children(".prev"),
          i = e(t).children(".next"),
          o = e(t).children("ul"),
          r = o.get(0).scrollWidth - o.get(0).clientWidth;
        0 == o.scrollLeft() ? n.addClass("disabled") : n.removeClass("disabled"), o.scrollLeft() >= r ? i.addClass("disabled") : i.removeClass("disabled")
      },
      useNativeScrolling: function(t) {
        if (!e(t).children("ul").data("pandaTab_init")) return !1;
        var n = e(t).children("ul").get(0).clientHeight;
        e(t).css({
          height: n,
          overflow: "hidden"
        }), e(t).children("ul").addClass("native-scrolling")
      },
      disableNativeScrolling: function(t) {
        if (!e(t).children("ul").data("pandaTab_init")) return !1;
        e(t).removeAttr("style"), e(t).children("ul").removeClass("native-scrolling")
      },
      scrollToActivePosition: function(t) {
        var n = e(t).children("ul");
        if (!e(n).data("pandaTab_init")) return !1;
        n.scrollLeft(0);
        var o = e(t).attr("active-class"),
          r = n.children(o);
        0 < r.length && r.position().left + r.width() > n.width() && (n.scrollLeft(r.position().left - n.width() / 2 + r.width() / 2), i.disableScrollingButton(t))
      }
    };
    switch (t) {
      case "makeScrolling":
        i.makeScrolling(this);
        break;
      case "hideScrolling":
        i.hideScrolling(this);
        break;
      case "useNativeScrolling":
        i.useNativeScrolling(this);
        break;
      case "disableNativeScrolling":
        i.disableNativeScrolling(this);
        break;
      case "destroy":
        i.destroy(this);
        break;
      case "parseActivePosition":
        i.parseActivePosition(this.children("ul"));
        break;
      case "init":
      default:
        i.init(this, n)
    }
  }
}(jQuery))
