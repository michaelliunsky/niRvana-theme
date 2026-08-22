/*!
 * niRvana theme source - jquery.vue.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
function jQVue(options) {
  var _this = this;
  for (var event in this.$unbind = function(e) {
      return JSON.parse(JSON.stringify(e))
    }, this.$evalReturn = function(str, event) {
      return eval(str)
    }, this.$default_renderer = {
      default: function(e, t) {
        $(options.el + " [\\:html]").each(function(e, t) {
          var n = $(t).attr(":html"),
            i = _this.$evalReturn(n);
          $(t).html(i)
        }), $(options.el + " [\\:style]").each(function(e, t) {
          var n = $(t).attr(":style"),
            i = _this.$evalReturn(n);
          $(t).css(i)
        }), $(options.el + " [v-show]").each(function(e, t) {
          var n = $(t).attr("v-show");
          _this.$evalReturn(n) ? $(t).show() : $(t).hide()
        }), $(options.el + " [\\:template]").each(function(e, t) {
          var n = $(t).attr(":template"),
            i = _this.$evalReturn("this." + n);
          if (i) {
            var o = $(t).attr(":template-data"),
              r = _this.$evalReturn(o);
            r = r || {};
            var s = Mustache.render(i, r);
            $(t).html(s)
          }
        })
      },
      input: function(e, t) {
        $("input[v-model][type=text],input[v-model][type=search],input[v-model]:not([type])").each(function(e, t) {
          var n = $(t).attr("v-model"),
            i = _this.$evalReturn("this." + n);
          $(t).val(i)
        })
      },
      textarea: function(e, t) {
        $("textarea").each(function(e, t) {
          var n = $(t).attr("v-model"),
            i = _this.$evalReturn("this." + n);
          $(t).val(i)
        })
      },
      checkbox: function(e, t) {
        $("input[v-model][type=checkbox]").each(function(e, t) {
          var n = $(t).attr("v-model"),
            i = _this.$evalReturn("this." + n);
          $(t).prop("checked", i)
        })
      }
    }, this.$rerender = function(e, t) {
      for (var n in this.$default_renderer) try {
        this.$default_renderer[n](e, t)
      } catch (e) {
        console.log(e)
      }
      for (var n in this.$renderer) try {
        this.$renderer[n](e, t)
      } catch (e) {
        console.log(e)
      }
    }, this.$default_writeBack = {
      input: function() {
        var selector = "input[v-model][type=text],input[v-model][type=search],input[v-model]:not([type])";
        $(document).on("change", selector, function(event) {
          var value = $(this).val(),
            variable = $(this).attr("v-model");
          eval("_this." + variable + " = value")
        })
      },
      textarea: function() {
        $(document).on("input", "textarea", function(event) {
          var value = $(this).val(),
            variable = $(this).attr("v-model");
          eval("_this." + variable + " = value")
        })
      },
      checkbox: function() {
        $(document).on("change", "input[v-model][type=checkbox]", function(event) {
          var value = $(this).prop("checked"),
            variable = $(this).attr("v-model");
          eval("_this." + variable + " = value")
        })
      }
    }, this.$default_writeBack) try {
    this.$default_writeBack[event]()
  } catch (e) {
    console.log(e)
  }
  for (var event in this.$writeBack) try {
    this.$writeBack[event]()
  } catch (e) {
    console.log(e)
  }
  for (var method in $(document).on("click", options.el + " [\\@click]:not([disabled])", function(e) {
      var t = $(this).attr("@click");
      _this.$evalReturn(t, e)
    }), options.methods) options[method] || (this[method] = options.methods[method]);
  for (var key in this.$data = {}, this.$set = function(e, t, n) {
      Object.defineProperty(e, t, {
        get: function() {
          return this.$data[t]
        },
        set: function(e) {
          this.$data[t] = e, this.$rerender(t, this.$data[t])
        },
        configurable: !0
      }), this[t] = this.$unbind(n)
    }, options.data) this.$set(this, key, options.data[key]);
  $(function() {
    for (var e in _this.$beforeMounted) try {
      _this.$beforeMounted[e]()
    } catch (e) {
      console.log(e)
    }
    $('[jQVue-template][type="text/html"]').each(function(e, t) {
      var n = $(t).attr("jQVue-template");
      _this.$set(_this, n, $(t).html())
    })
  }), options.mounted && (this.$mounted = options.mounted, $(function() {
    _this.$mounted()
  }))
}
