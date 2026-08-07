/*!
 * niRvana theme source - theme.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
$(function() {
  Mustache.escape = function(e) {
    return e
  }, history.replaceState({
    html: $("html").html()
  }, "", window.location.href.replace(/\/$/gi, "")), jQVue.prototype.$info = function(e, t) {
    (new PdMessage).notify({
      notify: e,
      duration: t ? 1e3 * parseFloat(t) : 5e3
    })
  }, jQVue.prototype.$message = function(e, t) {
    (new PdMessage).notify({
      notify: e,
      type: "success",
      duration: t ? 1e3 * parseFloat(t) : 5e3
    })
  }, jQVue.prototype.$alert = function(e, t) {
    (new PdMessage).notify({
      notify: e,
      type: "error",
      duration: t ? 1e3 * parseFloat(t) : 5e3
    })
  }, jQVue.prototype.$confirm = function(e) {
    (new PdMessage).confirm(e)
  }
});
var a = eval,
  b = window.atob("Y29uc29sZS53YXJuKCdUaGVtZSBuaVJ2YW5hIHwgRGVzaWduZWQgYnkgbWljaGFlbGxpdW5za3lcbmh0dHBzOi8vYmxvZy5ta2xpdS50b3AnKQ=="),
  c = atob,
  d = decodeURIComponent;
new jQVue({
  el: "body",
  data: {
    forceCache: !1,
    optionsLoaded: !1,
    is_user_loggedin: !1,
    comment_author: "",
    comment_email: "",
    comment_url: "",
    comment_text: "",
    comment_ding: !0,
    comment_require_name_email: !1,
    comment_action_url: "",
    global_search_query: "",
    global_search_post: !0,
    global_search_gallery: !0,
    global_search_prod_title: !1,
    chat_msg: [],
    chat_input: "",
    chat_nodata: "找不到相关信息"
  },
  mounted: function() {
    var e = this;
    pandastudio_framework.current_user.logged_in && $.ajaxSetup({
      beforeSend: function(e) {
        e.setRequestHeader("x-wp-nonce", pandastudio_framework.nonce)
      }
    }), this.load_blog_options(), 0 < $("#coverflow[pandaSlider]").length && this.init_coverflow(), 0 < $("#flatflow[pandaSlider]").length && this.init_flatflow(), this.init_sidebar(), 0 < $(".topNav[pandaTab]").length && this.init_navbar(), 0 < $(".categoryNav[pandaTab]").length && this.init_categoryNavbar(), 0 < $(".display-switcher[pandaTab]").length && this.init_displaySwitcher(), 0 < $(".panda_pagi[pandaTab]").length && this.init_pagination(), this.parse_sidebar_height(), this.images_onload(function() {
      console.log("Img Load End")
    }), this.init_comments(), this.is_Mobile() || $('[data-toggle="tooltip"]').tooltip({
      container: "body"
    }), this.single_scrollspy(), $(document).on("keyup", ".fullscreen_search .searchbox input", function(t) {
      $(this).prop("comStart") || (t.preventDefault(), 13 == (t.keyCode ? t.keyCode : t.which) && e.global_search())
    }).on("compositionstart", ".fullscreen_search .searchbox input", function() {
      $(this).prop("comStart", !0)
    }).on("compositionend", ".fullscreen_search .searchbox input", function() {
      $(this).prop("comStart", !1)
    }), window.mounted_hook && mounted_hook.call(this), $(".waterfall.postLists img").imgcomplete(function() {
      e.make_masonry()
    });
    try {
      baiduAudio.getToken()
    } catch (e) {
      this.$alert(e)
    }
    this.init_post_nav_smooth_scroll(), this.init_float_tools(), this.loadAssistance(), this.loadArticleDirtyStyle(), this.initLightbox(), $(".chat_input").keyup(function(t) {
      $(this).prop("comStart") || (t.preventDefault(), 13 == (t.keyCode ? t.keyCode : t.which) && e.send_chat_message())
    }).on("compositionstart", function() {
      $(this).prop("comStart", !0)
    }).on("compositionend", function() {
      $(this).prop("comStart", !1)
    }), 0 < $("#comments .nopassword").length && $("#comments").remove(), window.niRvana_ajax_render_complete || (window.niRvana_ajax_render_complete = []), $(document).on("click", ".wechat-cover", function(e) {
      e.stopPropagation()
    })
  },
  methods: {
    init_coverflow: function() {
      var e = this;
      if ("flat" != $("#coverflow[pandaSlider]").attr("type")) {
        if (e.is_Mac_Chrome() || e.is_Android()) {
          var t = $("#coverflow[pandaSlider]").attr("view");
          2 < (t = parseInt(t)) && $("#coverflow[pandaSlider]").attr("view", "2")
        }
        e.is_Android() && (t = $("#coverflow[pandaSlider]").attr("view"), 2 < (t = parseInt(t)) && $("#coverflow[pandaSlider]").attr("view", "2"), $(".home #coverflow[pandaSlider],.category #coverflow[pandaSlider],.single #coverflow[pandaSlider]").not("[type]").attr("type", "flat"))
      }
      $("#coverflow[pandaSlider]").pandaSlider("init", "", {
        onChange: function(t, n) {
          if ("image" == $("#coverflow[pandaSlider]").attr("type")) return !1;
          e.sliderRenderBackground(t, n)
        },
        onInit: function(t, n) {
          if ("image" == $("#coverflow[pandaSlider]").attr("type")) return !1;
          e.sliderIniteColorAndBlurImg($("#coverflow[pandaSlider]").find(".page")), e.sliderRenderBackground(t, n), e.$waitColorInit = setInterval(function() {
            $(n).data("background-color") && (e.sliderRenderBackground(t, n), clearInterval(e.$waitColorInit))
          }, 100)
        }
      }), "image" != $("#coverflow[pandaSlider]").attr("type") && $("#coverflow").circleMagic({
        elem: "#coverflow",
        radius: 38,
        density: .015,
        color: "rgba(255,255,255, .13)",
        clearOffset: .15
      })
    },
    init_flatflow: function() {
      var e = this;
      $("#flatflow[pandaSlider]").pandaSlider("init", "", {
        onInit: function() {
          $("#flatflow[pandaSlider]").find(".navigator").appendTo($("#flatflow[pandaSlider]").parent())
        }
      });
      var t = $("#flatflow .page"),
        n = $("#flatflow").data("allPages");
      $.merge(t, n).each(function(t, n) {
        if (void 0 !== $(n).attr("blured"));
        else {
          var i = $(n).children(".cover").css("background-image");
          if (i = i.replace(/^url\(["']?/, "").replace(/["']?\)$/, "")) {
            var o = new Image;
            o.crossOrigin = "anonymous", o.onload = function() {
              var t = (new ColorThief).getColor(o),
                r = e.rgbToHsl(t[0], t[1], t[2]);
              r[2] = 40 <= r[2] ? 40 : r[2] <= 20 ? 20 : r[2], r[1] = 10 < r[1] && r[1] < 70 ? 70 : r[1];
              var s = "rgb(" + (t = e.hslToRgb(r[0], r[1], r[2]))[0] + "," + t[1] + "," + t[2] + ")",
                a = r;
              a[0] = 0 < a[0] - 10 ? a[0] - 10 : a[0] - 10 + 360, a[1] = 20 < a[1] && a[1] < 50 ? 1.2 * a[1] : a[1], a[2] = a[2] < 30 ? 1.5 * a[2] : a[2] < 50 ? 1.2 * a[2] : a[2];
              var l = e.hslToRgb(a[0], a[1], a[2]),
                c = "rgb(" + l[0] + "," + l[1] + "," + l[2] + ")";
              if ($(n).css("background", "linear-gradient(170deg," + s + " 30%," + c + ")"), 80 < r[2] ? ($(n).find(".title").addClass("light_color"), $(n).find(".description").addClass("light_color")) : ($(n).find(".title").addClass("dark_color"), $(n).find(".description").addClass("dark_color")), e.canFilterBlur()) {
                $(n).attr("blured", "css");
                var u = $("<div>");
                u.addClass("filterBlured"), u.css("background-image", "url(" + i + ")");
                var d = $("<div>");
                d.addClass("filterBlured_wrap"), d.append(u), $(n).append(d)
              }
            }, o.src = i
          }
        }
      })
    },
    sliderRenderBackground: function(e, t) {
      var n = $(t).find(".card").css("background-image"),
        i = (n && n.replace(/^url\(["']?/, "").replace(/["']?\)$/, ""), $("<div>"));
      if (i.addClass("imgColor").css({
          background: $(t).data("background-color") ? $(t).data("background-color") : "linear-gradient(120deg,#2c4867,#6187af)",
          display: "none"
        }), $(t).hasClass("hasHeadImg") || $("#coverflow").append(i), 1 == $("#coverflow .imgColor").length ? i.delay(0).fadeIn(100) : i.delay(100).fadeIn(500), $("#coverflow .imgColor").not(i).delay(700).fadeOut(0, function() {
          $(this).remove()
        }), i = null, "flat" != $("#coverflow[pandaSlider]").attr("type") && $(t).data("background-image")) {
        var o = $("<div>");
        o.addClass("imgBlur").css({
          "background-image": "url(" + $(t).data("background-image") + ")",
          display: "none"
        }), $(window).width() < 768 && o.css("animation-duration", "5s"), 1600 < $(window).width() && o.css("animation-duration", "15s"), this.is_chrome() && o.addClass("noAnimation"), $(t).hasClass("hasHeadImg") && o.addClass("useHeadImg").css({
          "background-image": "url(" + $(t).attr("headImg") + ")"
        }), $("#coverflow").append(o), o.delay(100).fadeIn(500), $("#coverflow .imgBlur").not(o).delay(700).fadeOut(0, function() {
          $(this).remove()
        }), o = null
      }
      var r = $(t).data("main-color") ? $(t).data("main-color") : "dark-slider";
      $("body").removeClass("dark-slider").removeClass("light-slider").addClass(r)
    },
    sliderIniteColorAndBlurImg: function(e) {
      var t = this;
      $(e).each(function(e, n) {
        var i = $(n).find(".card").css("background-image"),
          o = i ? i.replace(/^url\(["']?/, "").replace(/["']?\)$/, "") : "";
        if (o) {
          var r = new Image;
          r.crossOrigin = "anonymous", r.onload = function() {
            var e = new ColorThief,
              i = e.getColor(r),
              o = i,
              s = t.rgbToHsl(o[0], o[1], o[2]);
            s[2] = 40 <= s[2] ? 40 : s[2] <= 15 ? 15 : s[2], s[1] = 10 < s[1] && s[1] < 40 ? s[1] + 10 : s[1], s[2] <= 70 ? $(n).data("main-color", "dark-slider") : $(n).data("main-color", "light-slider");
            var a = "rgb(" + (o = t.hslToRgb(s[0], s[1], s[2]))[0] + "," + o[1] + "," + o[2] + ")",
              l = s;
            l[0] = 0 < l[0] - 15 ? l[0] - 15 : l[0] - 15 + 360, l[1] = 20 < l[1] && l[1] < 50 ? 1.2 * l[1] : l[1], l[2] = l[2] < 30 ? 1.5 * l[2] : l[2] < 50 ? 1.2 * l[2] : l[2];
            var c = t.hslToRgb(l[0], l[1], l[2]),
              u = "rgb(" + c[0] + "," + c[1] + "," + c[2] + ")";
            if ($(n).data("background-color", "linear-gradient(120deg," + a + " 20%," + u + " 80%)"), "flat" != $("#coverflow[pandaSlider]").attr("type")) {
              var d = r.width,
                h = r.height,
                p = parseInt(100 / (d / h)),
                f = document.createElement("canvas");
              f.width = 100, f.height = p;
              var m = f.getContext("2d");
              m.drawImage(r, 0, 0, d, h, 0, 0, 100, p);
              var g = $(window).width() < 768 ? 6 : 3;
              StackBlur.canvasRGB(f, 0, 0, r.width, r.height, g);
              var v = f.toDataURL();
              $(n).data("background-image", v)
            }
            r = v = i = e = f = m = null
          }, r.src = o
        }
      })
    },
    canBackdropBlur: function() {
      return !(!window.CSS || !window.CSS.supports) && CSS.supports("(backdrop-filter: blur(5px)) or (-webkit-backdrop-filter: blur(5px))")
    },
    canFilterBlur: function() {
      return !(!window.CSS || !window.CSS.supports) && CSS.supports("(filter: blur(25px) brightness(0.9) contrast(1.3)) or (-webkit-filter: blur(25px) brightness(0.9) contrast(1.3))")
    },
    hslToRgb: function(e, t, n) {
      var i, o, r;
      if (e /= 360, n /= 100, 0 == (t /= 100)) i = o = r = n;
      else {
        var s = function(e, t, n) {
            return n < 0 && (n += 1), 1 < n && (n -= 1), n < 1 / 6 ? e + 6 * (t - e) * n : n < .5 ? t : n < 2 / 3 ? e + (t - e) * (2 / 3 - n) * 6 : e
          },
          a = n < .5 ? n * (1 + t) : n + t - n * t,
          l = 2 * n - a;
        i = s(l, a, e + 1 / 3), o = s(l, a, e), r = s(l, a, e - 1 / 3)
      }
      return [Math.round(255 * i), Math.round(255 * o), Math.round(255 * r)]
    },
    rgbToHsl: function(e, t, n) {
      e /= 255, t /= 255, n /= 255;
      var i, o, r = Math.max(e, t, n),
        s = Math.min(e, t, n),
        a = (r + s) / 2;
      if (r == s) i = o = 0;
      else {
        var l = r - s;
        switch (o = .5 < a ? l / (2 - r - s) : l / (r + s), r) {
          case e:
            i = (t - n) / l + (t < n ? 6 : 0);
            break;
          case t:
            i = (n - e) / l + 2;
            break;
          case n:
            i = (e - t) / l + 4
        }
        i /= 6
      }
      return [360 * i, 100 * o, 100 * a]
    },
    is_Mobile: function() {
      var e = navigator.userAgent;
      return navigator.appVersion, !!(!!e.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) | (-1 < e.indexOf("Android") || -1 < e.indexOf("Linux")) | -1 < e.indexOf("iPhone") | -1 < e.indexOf("iPad"))
    },
    is_Android: function() {
      var e = navigator.userAgent;
      return navigator.appVersion, !!(-1 < e.indexOf("Android") || -1 < e.indexOf("Linux"))
    },
    is_Mac_Chrome: function() {
      var e = navigator.userAgent;
      return !!(-1 < e.indexOf("Macintosh") & -1 < e.indexOf("Chrome/"))
    },
    is_mac: function() {
      return -1 < navigator.userAgent.indexOf("Macintosh")
    },
    is_chrome: function() {
      return -1 < navigator.userAgent.indexOf("Chrome/")
    },
    init_sidebar: function() {
      var e = this;
      $('[manual-template="sidebarMenu"]').html(this.sidebarMenu);
      var t = this.sidebar.replace(/%3c/g, "LEFT<left<LEFT");
      t = (t = (t = (t = t.replace(/%3e/g, "RIGHT>right>RIGHT")).replace(/%/g, "BaiFenHao")).replace(/LEFT<left<LEFT/g, "%3c")).replace(/RIGHT>right>RIGHT/g, "%3e");
      var n = d(t);
      if (n = n.replace(/BaiFenHao/g, "%"), $('[manual-template="sidebar"]').html(n), 0 == $("aside").length) return !1;
      if ($(".sidebarMenu[pandaTab]").each(function(e, t) {
          $(t).pandaTab()
        }), $(".sidebarMenu[pandaTab] li").click(function() {
          $(this).addClass("active"), $(this).siblings().removeClass("active");
          var t = $(this).parent().parent(),
            n = t.parent().next();
          t.pandaTab("parseActivePosition");
          var i = $(this).attr("data-id");
          n.find("aside").not('[data-id="' + i + '"]').fadeOut(125), window.setTimeout(function() {
            n.find('aside[data-id="' + i + '"]').fadeIn(125)
          }, 125), window.setTimeout(function() {
            e.parse_sidebar_height()
          }, 250)
        }),
        $(window).resize(function(t) {
          e.parse_sidebar_height()
        }), $(document).on("click", "#wrapper.sidebar-visible #main", function(e) {
          $("#wrapper").removeClass("sidebar-visible")
        }), 0 < $('.single .sidebarMenu li[data-id="sidebar-0"]').length) $('.single .sidebarMenu li[data-id="sidebar-0"]').click();
      else {
        t = ["archive", "single", "home", "page"], n = !0;
        for (var i = 0; i < t.length && n; i++) $("." + t[i] + " .sidebarMenu").each(function(e, o) {
          $(o).find("li[show-on-" + t[i] + "]").first().click(), n = !1
        })
      }
      0 < $(".sidebar-affix").length && ($(window).resize(function(t) {
        e.parse_sidebar_width()
      }), e.parse_sidebar_width(), $(".sidebar-affix").affix({
        offset: {
          top: function() {
            return $(".coverflow-wrapper").height()
          },
          bottom: function() {
            return $("#footer").outerHeight() + 30
          }
        }
      }))
    },
    parse_sidebar_width: function() {
      $(".sidebar-affix").width($(".sidebar-affix").parent().width())
    },
    parse_sidebar_height: function() {
      var e = $('[height-to="sidebar"]').height() + $(".readMore").height();
      if (!e) {
        var t = $(".postListsModel .postLists").height() - 60,
          n = $(window).height() - 78 - 32;
        e = n < t ? n : t
      }
      var i = this;
      e = 240 < e ? e : 240, $('[manual-template="sidebar"]').height(e - 15), $('[manual-template="sidebar"]').custom_scrollbar({
        scrollbarClassName: "scrollBar-background",
        scrollbarWidth: 6,
        scrollbarTrackColor: "transparent",
        scrollbarColor: "rgba(31,45,61,.2)"
      }), $(".custom-scrollbar-content-wrapper").data("hasScrollingEvent") || ($(".custom-scrollbar-content-wrapper").data("hasScrollingEvent", !0), $(".custom-scrollbar-content-wrapper").scroll(function(e) {
        clearTimeout(i.sidebarScrollingTimeOut), $(this).siblings().find(".scrollBar-background").addClass("scrolling");
        var t = this;
        i.sidebarScrollingTimeOut = setTimeout(function() {
          $(t).siblings().find(".scrollBar-background").removeClass("scrolling")
        }, 500)
      }))
    },
    init_displaySwitcher: function() {
      var e = this;
      $(".display-switcher[pandaTab]").each(function(t, n) {
        $(n).pandaTab(), e.make_masonry()
      }), $(".display-switcher[pandaTab] li.list").click(function(t) {
        $('[height-to="sidebar"].cards').addClass("disappear"), window.setTimeout(function() {
          $('[height-to="sidebar"].cards').removeClass("disappear").removeClass("cards").addClass("lists"), e.make_masonry()
        }, 250)
      }), $(".display-switcher[pandaTab] li.card").click(function(t) {
        $('[height-to="sidebar"].lists').addClass("disappear"), window.setTimeout(function() {
          $('[height-to="sidebar"].lists').removeClass("disappear").removeClass("lists").addClass("cards"), e.make_masonry()
        }, 250)
      }), $(".display-switcher[pandaTab] li").click(function() {
        $(this).addClass("active"), $(this).siblings().removeClass("active"), $(this).parent().parent().pandaTab("parseActivePosition"), window.setTimeout(function() {
          e.parse_sidebar_height()
        }, 300)
      })
    },
    init_navbar: function() {
      function e() {
        var e = t.parse_nav_class_style();
        $(".main-nav").removeClass("float").removeClass("flat").addClass(e)
      }
      var t = this;
      $(".topNav[pandaTab]").pandaTab("init", 250), this.is_Mobile() && $(".topNav[pandaTab]").pandaTab("useNativeScrolling"), e(), $(window).resize(function() {
        e()
      }), $(window).scroll(function() {
        e()
      })
    },
    init_categoryNavbar: function() {
      $(".categoryNav[pandaTab]").pandaTab(), this.is_Mobile() && $(".categoryNav[pandaTab]").pandaTab("useNativeScrolling")
    },
    parse_nav_class_style: function(e) {
      var t = "float";
      return 0 < $(".container #coverflow").length && $(window).scrollTop() < 20 && 992 <= $(window).width() && (t = "flat"), t
    },
    toggle_mobile_menu: function() {
      $("#wrapper").hasClass("sidebar-visible") ? this.$message(d("请登录")) : $("#wrapper").toggleClass("menu-wrap-visible")
    },
    toggle_mobile_sidebar: function() {
      $("#wrapper").hasClass("menu-wrap-visible") ? this.$message(d("请登录")) : $("#wrapper").toggleClass("sidebar-visible")
    },
    show_global_search: function() {
      var e = Mustache.render(this.fullscreen_search, {});
      $(".fullscreen_search").html(e), this.$beforeMounted.jv_element(".fullscreen_search"), this.blur_front(), $(".fullscreen_search").addClass("visible"), this.$rerender()
    },
    hide_global_search: function() {
      $(".fullscreen_search").removeClass("visible"), this.return_blur_front(), window.setTimeout(function() {
        $(".fullscreen_search").empty()
      }, 250)
    },
    global_search_toggle_advanced: function() {
      $(".fullscreen_search .advanced").toggleClass("open"), $(".fullscreen_search .checkbox-group").slideToggle()
    },
    global_search: function() {
      var e = this;
      if ("" == this.global_search_query) return this.$alert("请输入搜索关键词！"), !1;
      var t = [];
      if (this.global_search_post && t.push("post"), this.global_search_gallery && t.push("gallery"), 0 == t.length) return $(".fullscreen_search .advanced").hasClass("open") || this.global_search_toggle_advanced(), this.$alert("至少选择一种类型再搜索！"), !1;
      $(".fullscreen_search .searchbox .button .icon").empty(), $(".fullscreen_search .searchbox .button .icon").append('<i class="fas fa-circle-notch fa-spin"></i>'), $(".fullscreen_search .advanced").hasClass("open") && this.global_search_toggle_advanced(), $(".fullscreen_search .searchbox input").attr("disabled", "disabled"), $(".fullscreen_search .searchbox .button").attr("disabled", "disabled"), $(".fullscreen_search .postLists").removeClass("visible");
      var n = function(t) {
        t = apply_filters("get_query_data", t), window.setTimeout(function() {
          var n = Mustache.render(e.post_list, {
            data: t
          });
          n = $(n), e.is_Mobile() && $(n).find(".card").removeClass("card").addClass("low_cpu_card"), $(".fullscreen_search .postLists").html(n), $(".fullscreen_search .postLists img").imgcomplete(function() {
            e.make_masonry()
          }), $(".fullscreen_search .postLists").addClass("visible"), $(".fullscreen_search .searchbox").css("margin-top", "10vh"), $(".fullscreen_search .searchbox .button .icon").empty(), $(".fullscreen_search .searchbox .button .icon").append('<i class="fas fa-search"></i>'), $(".fullscreen_search .searchbox input").removeAttr("disabled"), $(".fullscreen_search .searchbox .button").removeAttr("disabled")
        }, 300)
      };
      apply_filters("replace_query_func", function() {
        $.ajax({
          url: pandastudio_framework.route + "pandastudio/nirvana/restapi/",
          type: "POST",
          dataType: "json",
          data: JSON.stringify({
            e: "$result = pf_global_search($arg);",
            arg: {
              search_prod_title: e.global_search_prod_title ? e.global_search_query : "",
              s: e.global_search_query,
              post_type: t
            }
          }),
          forceCache: !0
        }).done(function(e) {
          n(e)
        }).fail(function() {
          e.$alert("网络错误\n请刷新后再试！")
        })
      })(n, {
        s: e.global_search_query,
        post_type: t,
        only_title: e.global_search_prod_title
      })
    },
    blur_front: function() {
      $("#wrapper").addClass("blur"), $("body").addClass("overflow-hidden"), $("canvas").hide()
    },
    return_blur_front: function() {
      $("#wrapper").removeClass("blur"), $("body").removeClass("overflow-hidden"), $("canvas").show()
    },
    init_pagination: function() {
      $(".panda_pagi[pandaTab]").pandaTab()
    },
    images_onload: function(e) {
      var t = [];
      $("img").each(function() {
        var e = $.Deferred();
        $(this).load(function() {
          e.resolve()
        }), t.push(e)
      }), $.when.apply(null, t).done(function() {
        e()
      })
    },
    load_blog_options: function() {
      var e = this,
        t = function(t) {
          (t = apply_filters("get_blog_options", t)).error && e.enable_pageLoader(t.msg), t.ajax_forceCache && (e.forceCache = !0), t.enable_pageLoader && e.init_pageLoader(), t.is_user_loggedin && (e.is_user_loggedin = t.is_user_loggedin), t.cmt_req_name_email && (e.comment_require_name_email = t.cmt_req_name_email), t.cmt_action_url && (e.comment_action_url = t.cmt_action_url), t.chat_nodata && (e.chat_nodata = t.chat_nodata), e.optionsLoaded = !0, $(".comment-meta.nick-name.guests").popover({
            container: "body",
            animation: !0,
            content: $(".popover_guests").clone().removeClass("hidden"),
            html: !0,
            placement: "top",
            trigger: "manual",
            title: t.cmt_req_name_email_title
          }), t.enable_highlightjs && ($("article pre").each(function(e, t) {
            var n = $(t).children("code");
            return t = 0 < n.length ? n.get(0) : t, !$(t).hasClass("hljs") && !$(t).parent().hasClass("disable_highlightjs") && (hljs.highlightBlock(t), void hljs.lineNumbersBlock(t, {
              singleLine: !0
            }))
          }), $("#comments pre").each(function(e, t) {
            return !$(t).hasClass("hljs") && !$(t).parent().hasClass("disable_highlightjs") && (hljs.highlightBlock(t), void hljs.lineNumbersBlock(t, {
              singleLine: !0
            }))
          }))
        };
      apply_filters("replace_load_blog_options_func", function() {
        $.ajax({
          url: pandastudio_framework.route + "pandastudio/nirvana/restapi/",
          type: "POST",
          dataType: "json",
          data: JSON.stringify({
            e: "$result = frontend_opts();"
          }),
          forceCache: e.forceCache
        }).done(function(e) {
          t(e)
        }).fail(function() {
          window.setTimeout(function() {
            e.$alert("REST API请求错误！", 1e3), e.$alert("服务器地址重写错误，请检查：\n1、后台/设置/固定链接设置为朴素\n2、.htaccess文件是否配置有误", 1e3)
          }, 2e3)
        })
      })(t)
    },
    url_is_image: function(e) {
      var t = [".svg", ".SVG", ".bmp", ".BMP", ".gif", ".GIF", ".jpg", ".JPG", ".png", ".PNG", ".tif", ".TIF", ".jpeg", ".JEPG", ".tiff", ".TIFF", ".webp", ".WEBP"],
        n = e.slice(-4),
        i = e.slice(-5);
      return -1 < t.indexOf(n) || -1 < t.indexOf(i)
    },
    init_pageLoader: function() {
      if ($(window).data("pageLoader_enable")) return !1;
      var e = this;
      $(document).on("click", 'a[href]:not([target="_blank"]):not([onclick])', function(t) {
        if (!(0 < $(this).parents("#comments,.popover").length)) {
          var n = $(this).attr("href");
          if (n && "#" != n.charAt(0) && $(window).data("pageLoader_enable") && !e.url_is_image(n)) try {
            history.pushState({}, "", n), t.preventDefault(), $(window).trigger("pageLoader:start", [n]), $.ajax({
              url: n,
              type: "GET",
              dataType: "html",
              forceCache: e.forceCache
            }).done(function(e) {
              $(window).trigger("pageLoader:success", [n, e])
            }).fail(function() {
              $(window).trigger("pageLoader:fail", [n])
            }).always(function() {
              $(window).trigger("pageLoader:complete", [n])
            })
          } catch (t) {}
        }
      }), $(window).on("popstate", function(t) {
        if (history.state) {
          $("#wrapper").remove(), $(".popover").remove(), $(".tooltip").remove(), $(".wechat-cover-wrapper").remove(), e.hide_global_search();
          var n = history.state.html,
            i = $("<div></div>");
          i.html(n), document.title = i.find("title").html();
          var o = i.find("#wrapper");
          $("body").prepend(o);
          var r = i.find('[jqvue-template="sidebarMenu"]').html(),
            s = i.find('[jqvue-template="sidebar"]').html();
          $('[jqvue-template="sidebarMenu"]').html(r), $('[jqvue-template="sidebar"]').html(s), e.sidebarMenu = r, e.sidebar = s, e.$beforeMounted.jv_element(), e.$mounted()
        }
      }), $(window).data("pageLoader_enable", !0), $(window).on("pageLoader:start", function(t, n) {
        e.enable_pageLoader("")
      }), $(window).on("pageLoader:complete", function(t, n) {
        e.comment_text = "", $(".pageLoader").removeClass("loading")
      }), $(window).on("pageLoader:success", function(t, n, i) {
        e.hide_global_search();
        var o = $("<div></div>");
        o.html(i);
        var r = o.find("#wrapper");
        r.hasClass("wp-theme-niRvana") ? (n = n.replace(/\/$/gi, ""), history.replaceState({
          html: i
        }, "", n), $(".nav").addClass("before_view"), $("#main").addClass("before_view"), window.setTimeout(function() {
          $("#wrapper").remove(), $(".popover").remove(), $(".tooltip").remove(), $(".wechat-cover-wrapper").remove(), e.hide_global_search(), r.find(".nav").addClass("before_view"), r.find("#main").addClass("before_view"), $("body").prepend(r);
          var t = o.find('[jqvue-template="sidebarMenu"]').html(),
            n = o.find('[jqvue-template="sidebar"]').html();
          $('[jqvue-template="sidebarMenu"]').html(t), $('[jqvue-template="sidebar"]').html(n), e.sidebarMenu = t, e.sidebar = n, $(window).scrollTop(0), e.$rerender(), e.$beforeMounted.jv_element(), e.$mounted(), window.setTimeout(function() {
            r.find(".nav").removeClass("before_view"), r.find("#main").removeClass("before_view"), document.title = o.find("title").html(), do_action("ajax_render_complete")
          }, 16)
        }, 250)) : location.reload()
      }), $(window).on("pageLoader:fail", function(e, t) {
        location.reload()
      })
    },
    enable_pageLoader: function(e) {
      $(".pageLoader").addClass("loading"), $("body > .pandaTabWrap").children().each(function(e, t) {
        $(t).removeClass("show"), window.setTimeout(function() {
          $(t).remove(), $("body > .pandaTabWrap").remove()
        }, 250)
      }), e && this.$alert(e, 250)
    },
    is_Email: function(e) {
      return /^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(e)
    },
    submit_comments: function(e) {
      if ("submit" == $(e.target).attr("type"));
      else {
        e.preventDefault();
        var t = [];
        if (this.is_user_loggedin || ("" == this.comment_author.replace(/ /g, "") & this.comment_require_name_email && t.push("昵称未填写"), "" == this.comment_email.replace(/ /g, "") & this.comment_require_name_email && t.push("邮箱未填写"), "" != this.comment_email.replace(/ /g, "") & !this.is_Email(this.comment_email) && t.push("邮箱格式错误")), 0 < t.length && this.optionsLoaded && window.setTimeout(function() {
            $(".comment-meta.nick-name.guests").popover("show")
          }, 200), "" == this.comment_text.replace(/ /g, "") && (t.push("评论内容未填写"), 0 == t.length && $("#comment").trigger("focus")), 0 < t.length)
          for (var n = 0; n < t.length; n++) this.$alert(t[n]);
        var i = apply_filters("isHuman", !0);
        if (!i && 0 == t.length) return this.enable_pageLoader("严重错误：非人为操作\n操作过快，判定为机器人操作，请刷新重试"), !1;
        if (0 == t.length && i) {
          localStorage.pandastudio_guest_info = JSON.stringify({
            comment_author: this.comment_author,
            comment_email: this.comment_email,
            comment_url: this.comment_url
          });
          var o = $('<input name="wp_nonce" class="hidden">');
          if (o.val(pandastudio_framework.nonce), $("#cmt_form").append(o), !this.comment_action_url) return this.enable_pageLoader("严重错误：评论地址无效"), !1;
          $("#cmt_form").attr("action", this.comment_action_url), $(e.target).attr("type", "submit"), $(e.target).trigger("click")
        }
      }
    },
    addCommentFace: function(e) {
      this.comment_text += e, $("textarea#comment").focus()
    },
    init_comments: function() {
      var e = this;
      if ($(".commentlist .children > li").each(function(e, t) {
          var n = "<span class='reply'>@" + $(this).parent().siblings().find(".name").html() + "</span>";
          $(this).find(".comment-author.vcard").first().find("p").first().prepend(n)
        }), $("#comment_faces_toggle").popover({
          container: "body",
          animation: !0,
          content: $(".popover_faces").clone().removeClass("hidden"),
          html: !0,
          placement: "top",
          trigger: "focus"
        }), $(".comment-meta.nick-name.loggedin").popover({
          container: "body",
          animation: !0,
          content: $(".popover_loggedin").clone().removeClass("hidden"),
          html: !0,
          placement: "top",
          trigger: "focus",
          title: '<i class="fas fa-sign-out-alt"></i> 退出登录？'
        }), $(".comment-meta.nick-name.guests").click(function(t) {
          if (!e.optionsLoaded) return e.$info("请稍后"), void e.$info("网页还未加载完成");
          $(".comment-meta.nick-name.guests").popover("show"), "" != e.comment_author ? "" != e.comment_email ? "" != e.comment_url || $('.popover [v-model="comment_url"]').focus() : $('.popover [v-model="comment_email"]').focus() : $('.popover [v-model="comment_author"]').focus()
        }), $("body").click(function(t) {
          $(t.target).is(".comment-meta.nick-name.guests") || 0 != $(t.target).parents(".popover").length || $(t.target).is("textarea#comment") && e.comment_require_name_email && ("" == e.comment_author || "" == e.comment_email) || $(".comment-meta.nick-name.guests").popover("hide")
        }), $("textarea#comment").click(function(t) {
          if (e.comment_require_name_email && ("" == e.comment_author || "" == e.comment_email) && !e.is_user_loggedin) {
            if ($(".comment-meta.nick-name.guests").popover("show"), "" == e.comment_author) return void $('.popover [v-model="comment_author"]').focus();
            if ("" == e.comment_email) return void $('.popover [v-model="comment_email"]').focus()
          }
        }), localStorage.pandastudio_guest_info) {
        var t = JSON.parse(localStorage.pandastudio_guest_info);
        this.comment_author = t.comment_author, this.comment_email = t.comment_email, this.comment_url = t.comment_url
      }
    },
    single_scrollspy: function() {
      $("body").scrollspy({
        target: ".post_nav"
      })
    },
    single_toggle_sidebar: function(e) {
      function t() {
        $(".postListsModel > .row > div").eq(0).attr("class", "col-md-9 col-lg-9_5"), window.setTimeout(function() {
          $(".postListsModel > .row > div").eq(1).attr("class", "col-md-3 col-lg-2_5 hidden-xs hidden-sm"), i.parse_sidebar_width(), $(window).scrollTop($(window).scrollTop() + 1), $(window).scrollTop($(window).scrollTop() - 1), $(".sidebarMenu li.active").click()
        }, 500)
      }

      function n() {
        $(".postListsModel > .row > div").eq(0).attr("class", "col-xs-12 no-sidebar"), $(".postListsModel > .row > div").eq(1).attr("class", "hidden")
      }
      var i = this;
      "show" == e ? t() : "hide" == e ? n() : $(".postListsModel > .row > div").eq(0).hasClass("no-sidebar") ? t() : n()
    },
    ding: function(e) {
      var t = this;
      if ("true" == $(".post-like.like.favorite").attr("is_ajaxing")) return this.$info("点赞处理中...", 1), !1;
      if ($(".post-like.like.favorite").hasClass("done")) this.$alert("您已经点过赞了！");
      else {
        $(".post-like.like.favorite").attr("is_ajaxing", "true"), $(".post-like.like.favorite > .count.number").html('<i class="fas fa-circle-notch fa-spin"></i>'), this.$info("点赞处理中...", 1);
        var n = function(e) {
          $(".post-like.like.favorite > .count.number").html(e), $(".post-like.like.favorite i").removeClass("far").addClass("fas"), window.setTimeout(function() {
            t.$message("已赞，感谢您的支持！")
          }, 1e3), window._ajaxForceCache = {}, $(".post-like.like.favorite").addClass("done").removeAttr("is_ajaxing")
        };
        apply_filters("replace_like_func", function() {
          $.ajax({
            url: pandastudio_framework.route + "pandastudio/nirvana/restapi/",
            type: "POST",
            dataType: "json",
            data: JSON.stringify({
              e: "$result = pf_post_ding($arg);",
              arg: e
            })
          }).done(function(e) {
            n(e)
          }).fail(function() {
            t.$alert("网络错误\n请刷新后再试！"), $(".post-like.like.favorite").removeAttr("is_ajaxing")
          })
        })(n, e)
      }
    },
    make_masonry: function() {
      $(".waterfall").masonry({
        itemSelector: ".post-card-wrapper",
        transitionDuration: 0
      }), $(".waterfall").masonry("reloadItems")
    },
    gallery_bgsize_toggle: function() {
      "cover" == $('#coverflow.gallery[type="image"]').attr("background-size") ? $('#coverflow.gallery[type="image"]').attr("background-size", "contain") : $('#coverflow.gallery[type="image"]').attr("background-size", "cover")
    },
    init_post_nav_smooth_scroll: function() {
      $("#wrapper").on("click", ".post_nav a", function(e) {
        if (e.preventDefault(), location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
          var t = $(this.hash);
          if ((t = t.length && t || $("[name=" + this.hash.slice(1) + "]")).length) {
            var n = t.offset().top;
            return $("html,body").animate({
              scrollTop: n
            }, 500), !1
          }
        }
      })
    },
    send_chat_message: function(e) {
      var t = this,
        n = e || this.chat_input;
      if (!n) return !1;
      this.chat_msg.push({
        user: "me",
        content: n
      }), !e && (this.chat_input = ""), this.renderChat(), this.send_chat_message_loading = window.setTimeout(function() {
        t.chat_loading(!0)
      }, 250);
      var i = function(e) {
        var n = "";
        if (e.is_content) n = "<span style='font-weight:bold;margin-bottom:10px;display:inline-block'>" + e.title + "</span><br>" + e.content;
        else if (0 < e.list.length) {
          n = "找到以下内容，点击查看详情<ol>";
          for (var i = 0; i < e.list.length; i++) n += "<li><span @click='this.send_chat_message(\"" + e.list[i] + "\")'>" + e.list[i] + "</span></li>";
          n += "</ol>"
        } else n = t.chat_nodata;
        t.chat_msg.push({
          user: "server",
          content: n
        }), window.clearTimeout(t.send_chat_message_loading), t.chat_loading(!1)
      };
      apply_filters("replace_faq_search_func", function() {
        $.ajax({
          url: pandastudio_framework.route + "pandastudio/nirvana/restapi/",
          type: "POST",
          dataType: "json",
          data: JSON.stringify({
            e: "$result = pf_faq($arg);",
            arg: n
          }),
          forceCache: !0
        }).done(function(e) {
          i(e)
        }).fail(function() {
          t.chat_msg.push({
            user: "server",
            content: "数据加载失败"
          }), window.clearTimeout(t.send_chat_message_loading), t.chat_loading(!1)
        })
      })(i, n)
    },
    renderChat: function() {
      var e = $("#chatTemplate").scrollTop(),
        t = Mustache.render(this.chatTemplate, this);
      $("#chatTemplate").html(t), $("#chatTemplate").css("max-height", "inherit");
      var n = $("#chatTemplate").height();
      $("#chatTemplate").attr("style", ""), $("#chatTemplate").scrollTop(e), $("#chatTemplate").animate({
        scrollTop: n - 400 + 15
      }, 200)
    },
    chat_loading: function(e) {
      function t() {
        for (var e = n.chat_msg.length - 1; 0 <= e; e--) n.chat_msg[e].is_loading && n.chat_msg.splice(e, 1)
      }
      var n = this;
      e ? (t(), this.chat_msg.push({
        user: "server",
        content: '<span class="loading"><span></span><span></span><span></span></span>',
        is_loading: !0
      })) : t(), this.renderChat()
    },
    init_float_tools: function() {
      $("#wrapper").append(this.floatTools), $(window).scroll(function(e) {
        300 < $(window).scrollTop() ? $(".floatTools .tool-button.backToTop").removeClass("unavailable") : $(".floatTools .tool-button.backToTop").addClass("unavailable")
      })
    },
    backToTop: function() {
      $("html,body").animate({
        scrollTop: 0
      }, 400)
    },
    loadAssistance: function() {
      $("#assistance").html(this.assistance_wrapper), this.renderChat();
      var e = $(".tool-button.assistance").get(0);
      if (!e) return !1;
      var t = e.getBoundingClientRect().bottom;
      t = $(window).height() - t - 5, $("#assistance").css({
        bottom: t + "px",
        right: "50px"
      })
    },
    showAssistance: function() {
      $("#assistance").toggleClass("unavailable")
    },
    shareToWeibo: function() {
      var e = $("h1").html(),
        t = location.href;
      window.open("https://service.weibo.com/share/share.php?title=" + encodeURIComponent(e) + "&url=" + encodeURIComponent(t) + "&searchPic=true", "_blank", "scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes")
    },
    shareToWechat: function(e, t) {
      0 == $("script#html2canvas").length && $("body").append('<script src="' + pandastudio_framework.theme.route + '/assets/minify/html2canvas.min.js" id="html2canvas"></script>'), 0 == $(".wechat-cover").length && $("body").append('<div class="wechat-cover-wrapper unavailable" @click="this.hide_wechat_cover()">');
      var n = location.href,
        i = {
          title: $("h1").html(),
          thumbImg: e,
          description: t
        };
      this.blur_front();
      var o = Mustache.render(this.wechatCoverWrapper, i);
      $(".wechat-cover-wrapper").html(o), $(".wechat-cover-wrapper").removeClass("unavailable"), $(".wechat-cover-wrapper .qrcode").qrcode({
        width: 100,
        height: 100,
        correctLevel: 0,
        text: this.utf16to8(n),
        background: "#fff",
        foreground: "#8492A6"
      }), window.setTimeout(function() {
        html2canvas($(".wechat-cover-wrapper .renderCoverImg").get(0), {
          width: $(".wechat-cover-wrapper .renderCoverImg").width(),
          height: $(".wechat-cover-wrapper .renderCoverImg").height(),
          useCORS: !0
        }).then(function(e) {
          var t = e.getContext("2d");
          t.mozImageSmoothingEnabled = !1, t.webkitImageSmoothingEnabled = !1, t.msImageSmoothingEnabled = !1, t.imageSmoothingEnabled = !1, $(".wechat-cover-wrapper .renderCoverImg").empty(), $(".wechat-cover-wrapper .renderCoverImg").append('<img src="' + e.toDataURL() + '">'), $(".wechat-cover .wait span").html("封面已生成！保存图片可分享至社交网站"), $(".wechat-cover .wait span").addClass("success")
        })
      }, 750)
    },
    hide_wechat_cover: function() {
      this.return_blur_front(), $(".wechat-cover-wrapper").addClass("unavailable")
    },
    utf16to8: function(e) {
      var t, n, i, o;
      for (t = "", i = e.length, n = 0; n < i; n++) 1 <= (o = e.charCodeAt(n)) && o <= 127 ? t += e.charAt(n) : (2047 < o ? (t += String.fromCharCode(224 | o >> 12 & 15), t += String.fromCharCode(128 | o >> 6 & 63)) : t += String.fromCharCode(192 | o >> 6 & 31), t += String.fromCharCode(128 | o >> 0 & 63));
      return t
    },
    loadArticleDirtyStyle: function() {
      if (!pandastudio_framework.article_dirty_selector) return !1;
      var e = pandastudio_framework.article_dirty_selector,
        t = ".carousel " + e.join(",.carousel ");
      $(t).remove();
      var n = "pre " + e.join(",pre ");
      $(n).remove();
      var i = "code " + e.join(",code ");
      $(i).remove();
      var o = "article > " + e.join(",article > ");
      $(o).remove()
    },
    initLightbox: function() {
      var e = this;
      $("#wrapper").on("click", "article a", myFunc = function(t) {
        var n = $(this),
          i = n.attr("href");
        i && "_blank" != n.attr("target") && e.url_is_image(i) && (t.preventDefault(), e.makeLightbox(i, t, n))
      }), $("#wrapper").on("click", ".lightbox_wrapper", myFunc = function(t) {
        if ("IMG" == $(t.target).prop("tagName")) return !1;
        e.clearLightbox()
      }), $(window).on("keydown", myFunc = function(t) {
        e.clearLightbox()
      })
    },
    makeLightbox: function(e, t, n) {
      this.clearLightbox(!0);
      var i = $('<div class="lightbox_wrapper flex-center unvisible"></div>');
      $("#wrapper").append(i), i.data("windowScrollTop", $(window).scrollTop()), i.data("clientX", t.clientX), i.data("clientY", t.clientY);
      var o = $('<img src="' + e + '" class="unvisible"/>'),
        r = t.clientX,
        s = t.clientY,
        a = $(window).width() / 2 - r,
        l = $(window).height() / 2 - s;
      o.css("transform-origin", "calc(50% - " + a + "px) calc(50% - " + l + "px)"), i.append(o), window.setTimeout(function() {
        i.removeClass("unvisible"), o.removeClass("unvisible")
      }, 16)
    },
    clearLightbox: function(e) {
      var t = this,
        n = $("#wrapper > .lightbox_wrapper");
      if (0 == n.length) return !1;
      if (e) n.remove();
      else {
        n.addClass("unvisible");
        var i = n.find("img"),
          o = n.data("clientX"),
          r = n.data("clientY") - $(window).scrollTop() + n.data("windowScrollTop"),
          s = $(window).width() / 2 - o,
          a = $(window).height() / 2 - r;
        i.css("transform-origin", "calc(50% - " + s + "px) calc(50% - " + a + "px)"), i.addClass("unvisible"), window.setTimeout(function() {
          t.clearLightbox(!0)
        }, 500)
      }
    },
    insert_code_to_comment_form: function() {
      var e = "<pre>\n请输入代码...\n</pre>";
      "" == this.comment_text ? this.comment_text += e : "\n" == this.comment_text.substring(this.comment_text.length - 1) ? this.comment_text += e : this.comment_text += "\n<pre>\n请输入代码...\n</pre>", $("textarea#comment").focus()
    },
    insert_images_to_comment_form: function() {
      var e = this;
      (new PdMessage).prompt({
        title: "插入图片",
        message: "请粘贴图片地址，每个图片一行",
        value: "",
        placeholder: "https://...",
        promptType: "textarea",
        then: function(t) {
          var n = t.split("\n"),
            i = "";
          n.map(function(e) {
            "" !== e.replace(/ /g, "") && (i += '<img src="'.concat(e, '" />\n'))
          }), "" == e.comment_text ? e.comment_text += i : "\n" == e.comment_text.substring(e.comment_text.length - 1) ? e.comment_text += i : e.comment_text += "\n" + i, $("textarea#comment").focus()
        }
      })
    }
  }
}), a(b)
