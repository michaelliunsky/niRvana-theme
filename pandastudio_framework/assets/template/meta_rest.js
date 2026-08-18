// WP 核心脚本 wp-i18n 提供的翻译函数 (admin_enqueue_scripts 先加载 wp-i18n)
var __ = wp.i18n.__;
var metaCfg = window.nirvanaMetaConfig || {},
  restRoute = metaCfg.route || "",
  restNonce = metaCfg.nonce || "";

function nirvanaMetaBoot() {
  var postID = metaCfg.postID,
    postType = metaCfg.postType;
  jQuery.ajax({
    url: restRoute + "pandastudio/framework/get_posttype_and_meta_json",
    type: "GET",
    headers: { "X-WP-Nonce": restNonce },
    success: function(t) {
    "string" == typeof t && (t = JSON.parse(t));
    for (var e = t.meta, n = [], a = 0; a < e.length; a++) e[a].screen.indexOf(postType) >= 0 && n.push(e[a]);
    n <= 0 && jQuery("#pandastudio_framework_meta,label[for='pandastudio_framework_meta-hide']").remove(), jQuery(function() {
      jQuery("#postcustom,label[for='postcustom-hide']").remove()
    }), window.vue_rest = {
      tabs: n
    };
    var o = t.components;
    for (var s in o) o[s].methods = {
      _: function(t) {
        for (var e = 0; e < window.vue_rest.tabs.length; e++)
          for (var n = 0; n < window.vue_rest.tabs[e].content.length; n++)
            if (window.vue_rest.tabs[e].content[n].name == t) return window.vue_rest.tabs[e].content[n].value
      }
    };
    window.vue_rest = new Vue({
      el: "#vue_rest",
      components: o,
      data: function() {
        return {
          tabIndex: "0",
          loading: !0,
          show: !0,
          tabs: n
        }
      },
      watch: {
        tabs: {
          deep: true,
          handler: function() {
            this.syncHiddenInputs()
          }
        }
      },
      mounted: function() {
        this.loading = !0;
        for (var t = {}, e = 0; e < this.tabs.length; e++)
          for (var n = 0; n < this.tabs[e].content.length; n++) null != this.tabs[e].content[n].name & null != this.tabs[e].content[n].value && (t[this.tabs[e].content[n].name] = "");
        var a = this,
          o = {
            postID: postID,
            postMeta: t
          };
        jQuery.ajax({
          url: restRoute + "pandastudio/framework/get_post_meta",
          type: "POST",
          beforeSend: function(t) {
            t.setRequestHeader("X-WP-Nonce", restNonce)
          },
          data: JSON.stringify(o)
        }).done(function(t) {
          for (var e = 0; e < a.tabs.length; e++)
            for (var n = 0; n < a.tabs[e].content.length; n++)
              if (null != a.tabs[e].content[n].name & null != a.tabs[e].content[n].value) switch (a.tabs[e].content[n].type) {
                case "slider":
                case "inputNumber":
                  t[a.tabs[e].content[n].name] ? a.tabs[e].content[n].value = parseFloat(t[a.tabs[e].content[n].name]) : a.tabs[e].content[n].value = 0;
                  break;
                case "multitypes":
                  "" == t[a.tabs[e].content[n].name] ? a.tabs[e].content[n].value = [] : a.tabs[e].content[n].value = t[a.tabs[e].content[n].name];
                  break;
                default:
                  a.tabs[e].content[n].value = t[a.tabs[e].content[n].name]
              }
          a.syncHiddenInputs(), a.loading = !1
        }).fail(function() {
          a.loading = !1, a.show = !1, a.$alert(__("连接服务器失败或后台读取出错！", "niRvana"), __("设置项读取失败", "niRvana"), {
            confirmButtonText: __("确定", "niRvana")
          })
        })
      },
      methods: {
        syncHiddenInputs: function() {
          var box = this.$el;
          for (var e = 0; e < this.tabs.length; e++)
            for (var n = 0; n < this.tabs[e].content.length; n++) {
              var f = this.tabs[e].content[n];
              if (null == f.name || null == f.value) continue;
              var input = box.querySelector('input[name="' + f.name + '"]');
              if (!input) {
                input = document.createElement("input");
                input.type = "hidden";
                input.name = f.name;
                box.appendChild(input);
              }
              input.value = "object" == typeof f.value ? JSON.stringify(f.value) : f.value;
            }
        },
        waitToUpdate: function() {
          this.syncHiddenInputs()
        },
        mediaUpload: function(t, e, n) {
          var a = this,
            o = window.parent.wp.media({
              title: __("上传", "niRvana"),
              button: {
                text: __("插入", "niRvana")
              },
              multiple: !1
            });
          o.open(), o.on("select", function() {
            attachment = o.state().get("selection").first().toJSON(), value = attachment.url;
            for (var s = 0; s < a.tabs.length; s++)
              for (var r = 0; r < a.tabs[s].content.length; r++) a.tabs[s].content[r].name == t && ("" != n ? (a.tabs[s].content[r].value[e][n] = value, a.waitToUpdate()) : (a.tabs[s].content[r].value = value, a.waitToUpdate()))
          })
        },
        multiMediaUpload: function(t) {
          var e = this,
            n = window.parent.wp.media({
              title: __("按住 ctrl 批量选择", "niRvana"),
              button: {
                text: __("批量插入", "niRvana")
              },
              multiple: !0
            });
          n.open(), n.on("select", function() {
            for (var a = n.state().get("selection").toJSON(), o = [], s = 0; s < a.length; s++) o[s] = a[s].url;
            for (s = 0; s < e.tabs.length; s++)
              for (var r = 0; r < e.tabs[s].content.length; r++) e.tabs[s].content[r].name == t && ("string" == typeof e.tabs[s].content[r].value ? (e.tabs[s].content[r].value = o, e.waitToUpdate()) : (e.tabs[s].content[r].value = e.tabs[s].content[r].value.concat(o), e.waitToUpdate()))
          })
        },
        multiMediaUpload_input: function(t) {
          var e = this;
          this.$prompt(__("请输入图片地址", "niRvana"), __("外链图片", "niRvana"), {
            confirmButtonText: __("确定", "niRvana"),
            cancelButtonText: __("取消", "niRvana")
          }).then(function(n) {
            for (var a = n.value, o = 0; o < e.tabs.length; o++)
              for (var s = 0; s < e.tabs[o].content.length; s++) e.tabs[o].content[s].name == t && ("string" == typeof e.tabs[o].content[s].value ? (e.tabs[o].content[s].value = [a], e.waitToUpdate()) : (e.tabs[o].content[s].value = e.tabs[o].content[s].value.concat(a), e.waitToUpdate()))
          }).catch(function() {})
        },
        addMultiTypes: function(t) {
          for (var e = 0; e < this.tabs.length; e++)
            for (var n = 0; n < this.tabs[e].content.length; n++)
              if (this.tabs[e].content[n].name == t) {
                for (var a = this.tabs[e].content[n], o = {}, s = 0; s < a.types.length; s++) o[a.types[s].name] = "";
                a.value.push(o), this.waitToUpdate()
              }
        },
        removeMultiTypes: function(t, e) {
          for (var n = 0; n < this.tabs.length; n++)
            for (var a = 0; a < this.tabs[n].content.length; a++)
              if (this.tabs[n].content[a].name == t) {
                var o = this.tabs[n].content[a];
                o.value.splice(e, 1), this.waitToUpdate()
              }
        },
        removeMultiUpload: function(t, e) {
          for (var n = 0; n < this.tabs.length; n++)
            for (var a = 0; a < this.tabs[n].content.length; a++)
              if (this.tabs[n].content[a].name == t) {
                var o = this.tabs[n].content[a];
                o.value.splice(e, 1), this.waitToUpdate()
              }
        },
        removeAllMultiUpload: function(t) {
          for (var e = 0; e < this.tabs.length; e++)
            for (var n = 0; n < this.tabs[e].content.length; n++) this.tabs[e].content[n].name == t && (this.tabs[e].content[n].value = "", this.waitToUpdate())
        },
        swapItems: function(t, e, n) {
          return t[e] = t.splice(n, 1, t[e])[0], t
        },
        multitypeMoveUp: function(t, e) {
          0 != e && this.swapItems(t, e, e - 1)
        },
        multitypeMoveDown: function(t, e) {
          e != t.length - 1 && this.swapItems(t, e, e + 1)
        },
        gear_show: function(t, e) {
          if (null == t | "" == t | null == e) return !0;
          for (var n = 0; n < this.tabs.length; n++)
            for (var a = 0; a < this.tabs[n].content.length; a++)
              if (this.tabs[n].content[a].name == t & this.tabs[n].content[a].value == e) return !0;
          return !1
        }
      }
    })
  }}).fail(function() {
    alert(__("MetaBox数据获取失败！请检查：\n1、WordPress版本大于4.7\n2、Rest API是否被插件关闭\n3、服务器配置不正确导致“固定链接”故障，请将“设置-固定链接”设置为“朴素”并保存\n4、请检查“设置-常规”，WordPress安装地址是否与当前浏览器地址栏的地址不一致？", "niRvana"))
  });
}

if (typeof jQuery === "undefined" && metaCfg.jqueryUrl) {
  var nirvanaJq = document.createElement("script");
  nirvanaJq.src = metaCfg.jqueryUrl;
  nirvanaJq.onload = nirvanaMetaBoot;
  document.head.appendChild(nirvanaJq);
} else {
  nirvanaMetaBoot();
}
