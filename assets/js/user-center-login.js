/*!
 * niRvana theme source - user-center-login.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
function pf_login() {
  function e(e, t, n) {
    var o, r = $('\n\t\t<div class="inputer">\n\t\t\t<input type="'.concat(t, '">\n\t\t\t<span class="title">').concat(e, "</span>\n\t\t</div>\n\t\t"));
    return r.find("input").change(function(e) {
      $(this).val() ? ($(this).addClass("has_value"), r.addClass("has_value")) : ($(this).removeClass("has_value"), r.removeClass("has_value"))
    }), "password" == t && ("ontouchstart" in document.documentElement ? (o = $('<div class="view_password"><div class="pf_tooltip">点击查看密码</div></div>')).click(function() {
      r.find("input").val() && o.find(".pf_tooltip").addClass("disabled"), "text" == r.find("input").attr("type") ? r.find("input").attr("type", "password") : r.find("input").attr("type", "text")
    }) : (o = $('<div class="view_password"><div class="pf_tooltip">按住查看密码</div></div>')).mousedown(function() {
      r.find("input").val() && o.find(".pf_tooltip").addClass("disabled"), r.find("input").attr("type", "text")
    }).mouseup(function() {
      r.find("input").attr("type", "password")
    }), n && r.find("input").focus(function() {
      i(o.find(".pf_tooltip"), "show", 5e3)
    }), r.prepend(o)), r
  }

  function t(e, t) {
    var n = (new PdMessage).stop({
      title: "",
      message: "正在注册...",
      messageType: "waiting"
    });
    $.ajax({
      url: pandastudio_framework.route + "pandastudio/user-center/register-new-user/",
      type: "post",
      data: JSON.stringify(e)
    }).done(function(e) {
      if (e.is_error) return (new PdMessage).notify({
        notify: e.error_message,
        type: "error",
        duration: 5e3
      }), i(t, "error", 600), !1;
      (new PdMessage).notify({
        notify: "注册成功",
        type: "success",
        duration: 5e3
      }), (new PdMessage).notify({
        notify: "请使用注册的账号登录",
        type: "info",
        duration: 5e3
      }), s(!0)
    }).fail(function() {
      (new PdMessage).notify({
        notify: "用户注册接口请求失败！",
        type: "error",
        duration: 5e3
      })
    }).always(function() {
      n.closeMessage()
    })
  }

  function n(e) {
    var t = (new PdMessage).stop({
      title: "",
      message: "请稍后...",
      messageType: "waiting"
    });
    $.ajax({
      url: pandastudio_framework.route + "pandastudio/user-center/send-email-nonce/",
      type: "post",
      data: JSON.stringify({
        email: e,
        check_email_exists: !0
      })
    }).done(function(e) {
      if (e.is_error) return (new PdMessage).notify({
        notify: e.error_message,
        type: "error",
        duration: 5e3
      }), !1;
      (new PdMessage).notify({
        notify: "验证码发送成功",
        type: "success",
        duration: 5e3
      }), (new PdMessage).notify({
        notify: "请填写邮箱接收到的验证码",
        type: "info",
        duration: 5e3
      })
    }).fail(function() {
      (new PdMessage).notify({
        notify: "发送邮件验证码接口请求失败！",
        type: "error",
        duration: 5e3
      })
    }).always(function() {
      t.closeMessage()
    })
  }

  function i(e, t, n, i) {
    $(e).addClass(t), window.setTimeout(function() {
      $(e).removeClass(t), i && i()
    }, n)
  }

  function o() {
    var e = $(".pandastudio_framework_login");
    if (0 == e.length) {
      var t = $('<div class="pandastudio_framework_login"></div>');
      $("body").append(t), i(t, "is_showing", 16), is_show = !0, t.on("click", function(e) {
        if ("pandastudio_framework_login" == e.target.className && 1 == is_show) {
          var n = function() {
              r(), s(), window.setTimeout(function() {
                i(t, "is_showing", 350, function() {
                  t.remove()
                })
              }, 350)
            },
            o = !1;
          $(".pandastudio_framework_register__form input").each(function(e, t) {
            "" !== $(t).val().replace(/ /g, "") && (o = !0)
          }), o ? (new PdMessage).confirm({
            title: "取消注册",
            message: "注册表单尚有数据，请确认是否取消注册？",
            messageType: "question",
            cancel: "继续注册",
            confirm: "取消注册",
            catch: function() {},
            then: function() {
              n()
            }
          }) : n()
        }
      })
    }
    return 0 < e.length
  }

  function r(e, t) {
    var n = $(".pandastudio_framework_login__card");
    n.find(".pandastudio_framework_login__card___new").addClass("is_disappearing"), window.setTimeout(function() {
      i(n, "is_disappearing", 300, function() {
        n.remove(), e && a.showRegister(t)
      })
    }, 150)
  }

  function s(e, t) {
    var n = $(".pandastudio_framework_register__card");
    n.find(".pandastudio_framework_register__card___back").addClass("is_disappearing"), window.setTimeout(function() {
      i(n, "is_disappearing", 300, function() {
        n.remove(), e && a.showLogin(!0, t)
      })
    }, 150)
  }
  var a = this;
  this.is_show = !1;
  var l = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-user fa-w-14 fa-2x"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" class=""></path></svg>',
    c = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="key" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-key fa-w-16 fa-2x"><path fill="currentColor" d="M512 176.001C512 273.203 433.202 352 336 352c-11.22 0-22.19-1.062-32.827-3.069l-24.012 27.014A23.999 23.999 0 0 1 261.223 384H224v40c0 13.255-10.745 24-24 24h-40v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24v-78.059c0-6.365 2.529-12.47 7.029-16.971l161.802-161.802C163.108 213.814 160 195.271 160 176 160 78.798 238.797.001 335.999 0 433.488-.001 512 78.511 512 176.001zM336 128c0 26.51 21.49 48 48 48s48-21.49 48-48-21.49-48-48-48-48 21.49-48 48z" class=""></path></svg>',
    u = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16 fa-2x"><path fill="currentColor" d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z" class=""></path></svg>',
    d = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="copy" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-copy fa-w-14 fa-2x"><path fill="currentColor" d="M320 448v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24V120c0-13.255 10.745-24 24-24h72v296c0 30.879 25.121 56 56 56h168zm0-344V0H152c-13.255 0-24 10.745-24 24v368c0 13.255 10.745 24 24 24h272c13.255 0 24-10.745 24-24V128H344c-13.2 0-24-10.8-24-24zm120.971-31.029L375.029 7.029A24 24 0 0 0 358.059 0H352v96h96v-6.059a24 24 0 0 0-7.029-16.97z" class=""></path></svg>',
    h = '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="qq" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-qq fa-w-14 fa-2x"><path fill="currentColor" d="M433.754 420.445c-11.526 1.393-44.86-52.741-44.86-52.741 0 31.345-16.136 72.247-51.051 101.786 16.842 5.192 54.843 19.167 45.803 34.421-7.316 12.343-125.51 7.881-159.632 4.037-34.122 3.844-152.316 8.306-159.632-4.037-9.045-15.25 28.918-29.214 45.783-34.415-34.92-29.539-51.059-70.445-51.059-101.792 0 0-33.334 54.134-44.859 52.741-5.37-.65-12.424-29.644 9.347-99.704 10.261-33.024 21.995-60.478 40.144-105.779C60.683 98.063 108.982.006 224 0c113.737.006 163.156 96.133 160.264 214.963 18.118 45.223 29.912 72.85 40.144 105.778 21.768 70.06 14.716 99.053 9.346 99.704z" class=""></path></svg>';
  this.showLogin = function(t, n) {
    var s = o(),
      a = t ? '\n\t\t<div class="pandastudio_framework_login__card___new">\n\t\t\t<div class="pf_plus"></div>\n\t\t\t<div class="pf_tooltip">注册</div>\n\t\t</div>\n\t\t' : "";
    if (n) var u = e("".concat(l, "QQ/用户名/邮箱"), "text"),
      d = e("".concat(c, "网站密码"), "password");
    else u = e("".concat(l, "用户名/邮箱"), "text"), d = e("".concat(c, "密码"), "password");
    var h = $('\n\t\t<div class="pandastudio_framework_login__form___remember">\n\t\t\t<input type="checkbox" id="pf_login_remember"><label for="pf_login_remember">记住我</label>\n\t\t\t<a href="'.concat(pandastudio_framework.user_center.find_my_password, '" class="forget" target="_blank">忘记密码</a>\n\t\t</div>\n\t\t')),
      p = $('\n\t\t<div class="pandastudio_framework_login__form___submit">\n\t\t\t登录\n\t\t</div>\n\t\t');
    p.on("click", function() {
      var e, t, n, o = u.find("input").val(),
        r = d.find("input").val(),
        s = h.find("input").is(":checked"),
        a = !1;
      if ("" == o.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入账号"
        }), a = !0), "" == r.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入密码"
        }), a = !0), a) return i(p, "error", 600), !1;
      e = {
        user_login: o,
        user_password: r,
        remember: s
      }, t = p, n = (new PdMessage).stop({
        title: "",
        message: "登录中...",
        messageType: "waiting"
      }), $.ajax({
        url: pandastudio_framework.route + "pandastudio/user-center/user-signon/",
        type: "post",
        data: JSON.stringify(e)
      }).done(function(e) {
        if (e.is_error) return (new PdMessage).notify({
          notify: e.error_message,
          type: "error",
          duration: 5e3
        }), i(t, "error", 600), !1;
        (new PdMessage).notify({
          notify: "登陆成功！",
          type: "success",
          duration: 5e3
        }), window.setTimeout(function() {
          location.reload()
        }, 1e3)
      }).fail(function() {
        (new PdMessage).notify({
          notify: "登录接口请求失败！",
          type: "error",
          duration: 5e3
        })
      }).always(function() {
        n.closeMessage()
      })
    });
    var f = $('\n\t\t\t<div class="pandastudio_framework_login__card">\n\t\t\t\t'.concat(a, '\n\t\t\t\t<div class="pandastudio_framework_login__title">LOGIN</div>\n\t\t\t\t<div class="pandastudio_framework_login__form">\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t'));
    [u, d, h, p].map(function(e) {
      f.find(".pandastudio_framework_login__form").append(e)
    }), f.find(".pandastudio_framework_login__card___new").on("click", function(e) {
      r(!0, n)
    });
    var m = s ? 16 : 300;
    i(f, "is_showing", m), i(u, "is_showing", m + 350), i(d, "is_showing", m + 350 + 100), i(h, "is_showing", m + 350 + 100 + 100), i(p, "is_showing", m + 350 + 100 + 100 + 100), i(f.find(".pandastudio_framework_login__card___new"), "is_showing", m + 350 + 100 + 100 + 100 + 100), $("body > .pandastudio_framework_login").append(f)
  }, this.showRegister = function(r) {
    var a = o(),
      p = $('\n\t\t<div class="pandastudio_framework_register__card___back">\n\t\t\t<div class="pf_back"></div>\n\t\t\t<div class="pf_tooltip">登录</div>\n\t\t</div>');
    if (p.on("click", function() {
        s(!0, r)
      }), r) var f = e("".concat(h, "QQ号注册"), "text"),
      m = e("".concat(c, "网站密码"), "password", !0);
    else {
      f = e("".concat(l, "用户名"), "text"), m = e("".concat(c, "密码"), "password", !0);
      var g = e("".concat(u, "邮箱"), "text")
    }
    var v = e("".concat(d, "验证码"), "text"),
      b = $('<span class="send_email_nonce"></span>');
    b.on("click", function() {
      if (r) {
        var e = f.find("input").val();
        if (parseInt(e).toString() !== e || e.length < 5) return (new PdMessage).notify({
          type: "error",
          notify: "请输入正确的QQ号码"
        }), !1;
        var t = e + "@qq.com";
        return n(t), (new PdMessage).notify({
          type: "info",
          notify: "验证码发往：\n" + t,
          duration: 1e4
        }), !1
      }
      var i = g.find("input").val();
      return "" == i.replace(/ /g, "") ? ((new PdMessage).notify({
        type: "error",
        notify: "请输入邮箱"
      }), !1) : i.match(/[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/) ? void n(i) : ((new PdMessage).notify({
        type: "error",
        notify: "邮箱格式错误"
      }), !1)
    }), v.prepend(b);
    var y = $('\n\t\t<div class="pandastudio_framework_register__form___submit">\n\t\t\t注册\n\t\t</div>\n\t\t');
    y.on("click", function() {
      if (r) {
        var e = f.find("input").val(),
          n = m.find("input").val(),
          o = v.find("input").val(),
          s = !1;
        return "" == e.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入QQ号"
        }), s = !0), "" == n.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入网站密码"
        }), s = !0), "" == o.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入QQ邮箱接收的验证码"
        }), s = !0), s ? (i(y, "error", 600), !1) : (t({
          username: e,
          password: n,
          email: e + "@qq.com",
          email_nonce: o
        }, y), !1)
      }
      e = f.find("input").val(), n = m.find("input").val();
      var a = g.find("input").val();
      if (o = v.find("input").val(), s = !1, "" == e.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入用户名"
        }), s = !0), "" == n.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入密码"
        }), s = !0), "" == a.replace(/ /g, "") ? ((new PdMessage).notify({
          type: "error",
          notify: "请输入邮箱"
        }), s = !0) : a.match(/[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/) || ((new PdMessage).notify({
          type: "error",
          notify: "邮箱格式错误"
        }), s = !0), "" == o.replace(/ /g, "") && ((new PdMessage).notify({
          type: "error",
          notify: "请输入邮箱接收的验证码"
        }), s = !0), s) return i(y, "error", 600), !1;
      t({
        username: e,
        password: n,
        email: a,
        email_nonce: o
      }, y)
    });
    var w = $('\n\t\t\t<div class="pandastudio_framework_register__card">\n\t\t\t\t<div class="pandastudio_framework_register__title">SIGN UP</div>\n\t\t\t\t<div class="pandastudio_framework_register__form">\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t');
    w.prepend(p), [f, m, g, v, y].map(function(e) {
      w.find(".pandastudio_framework_register__form").append(e)
    });
    var _ = a ? 16 : 300;
    i(w, "is_showing", _), i(f, "is_showing", _ + 350 + 100), i(m, "is_showing", _ + 350 + 100 + 100), i(g, "is_showing", _ + 350 + 100 + 100 + 100), i(v, "is_showing", _ + 350 + 100 + 100 + 100 + 100), i(y, "is_showing", _ + 350 + 100 + 100 + 100 + 100 + 100), i(p, "is_showing", _ + 350 + 100 + 100 + 100 + 100 + 100 + 100), $("body > .pandastudio_framework_login").append(w)
  }
}
