/*!
 * niRvana theme source - pdmessage.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
function PdMessage() {
  function e(e, t) {
    1 == e ? (PdMessage.prototype.Modal_background_instance.el.style.zIndex = PdMessage.prototype.index - 1, 0 == PdMessage.prototype.model_zIndexs.length && document.body.appendChild(PdMessage.prototype.Modal_background_instance.el), window.setTimeout(function() {
      PdMessage.prototype.Modal_background_instance.el.className = "pdModal_background"
    }, 16), PdMessage.prototype.model_zIndexs.push(PdMessage.prototype.index)) : (PdMessage.prototype.model_zIndexs.splice(PdMessage.prototype.model_zIndexs.indexOf(t), 1), 0 == PdMessage.prototype.model_zIndexs.length ? (PdMessage.prototype.model_zIndexs.pop(), PdMessage.prototype.Modal_background_instance.el.className = "pdModal_background is_disappearing is_changing", window.setTimeout(function() {
      PdMessage.prototype.Modal_background_instance.el.parentNode.removeChild(PdMessage.prototype.Modal_background_instance.el)
    }, 350)) : PdMessage.prototype.Modal_background_instance.el.style.zIndex = PdMessage.prototype.model_zIndexs[PdMessage.prototype.model_zIndexs.length - 1] - 1)
  }

  function t(e) {
    this.el = document.createElement("div"), this.el.className = "pdmsg_wrapper is_showing is_changing", this.el.style.zIndex = PdMessage.prototype.index, PdMessage.prototype.index += 2, this.modal = document.createElement("div"), this.modal.className = "pdmsg_modal " + e._type, this.title = document.createElement("div"), this.title.className = "pdmsg_title", this.title.appendChild(document.createTextNode(e.title || "")), this.content = document.createElement("div"), this.content.className = "pdmsg_content" + (e.messageType ? " " + e.messageType : ""), this.contentSpan = document.createElement("span"), this.contentSpan.appendChild(document.createTextNode(e.message || "")), this.content.appendChild(this.contentSpan), "prompt" == e._type && (this.prompt_input_wrapper = document.createElement("div"), this.prompt_input_wrapper.className = "prompt_input_wrapper", this.prompt_input = document.createElement(e.promptType ? e.promptType : "input"), this.prompt_input.className = "input", this.prompt_input.value = e.value ? e.value : "", this.prompt_input.setAttribute("placeholder", e.placeholder ? e.placeholder : ""), this.prompt_input_wrapper.appendChild(this.prompt_input)), this.buttons = document.createElement("div"), this.buttons.className = "pdmsg_buttons";
    var t = [];
    "confirm" != e._type && "prompt" != e._type || (this.cancel = document.createElement("button"), this.cancel.appendChild(document.createTextNode(e.cancel || "取消")), this.cancel.addEventListener("click", e.reject || function() {
      e.catch && e.catch(), r.closeMessage()
    }), t.push(this.cancel)), this.confirm = document.createElement("button"), this.confirm.appendChild(document.createTextNode(e.confirm || "确定")), this.confirm.className = e.confirmType;
    var n = this.prompt_input;
    if (e.resolve) var i = function() {
      e.resolve(n ? n.value : void 0)
    };
    else i = e.then ? function() {
      e.then(n ? n.value : void 0), r.closeMessage()
    } : function() {
      r.closeMessage()
    };
    this.confirm.addEventListener("click", i), t.push(this.confirm);
    for (var o = 0; o < t.length; o++) this.buttons.appendChild(t[o]);
    this.modal.appendChild(this.title), this.modal.appendChild(this.content), "prompt" == e._type && this.modal.appendChild(this.prompt_input_wrapper), "stop" != e._type && this.modal.appendChild(this.buttons), this.el.appendChild(this.modal)
  }

  function n(e) {
    this.el = document.createElement("div"), this.el.className = "pdtoast_wrapper is_showing is_changing", this.el.style.zIndex = PdMessage.prototype.index, PdMessage.prototype.index += 2, this.toast = document.createElement("div");
    var t = "toast";
    e.type && (t = t + " " + e.type), this.toast.className = t, this.toast.appendChild(document.createTextNode(e.toast || "toast内容未定义！")), this.el.appendChild(this.toast)
  }

  function i(e) {
    this.el = document.createElement("div"), this.el.className = "pdactionsheet_wrapper is_showing is_changing", this.el.addEventListener("click", function() {
      r.closeActionsheet.call(r)
    }), this.el.style.zIndex = PdMessage.prototype.index, PdMessage.prototype.index += 2, this.actionsheet = document.createElement("div"), this.actionsheet.className = "pdactionsheet_actionsheet", this.actions = document.createElement("div"), this.actions.className = "pdactionsheet_actions", (e.title || e.message) && (this.titlewrapper = document.createElement("div"), this.titlewrapper.className = "pdactionsheet_titlewrapper", this.title = document.createElement("div"), this.title.className = "pdactionsheet_title", this.title.appendChild(document.createTextNode(e.title || "")), this.titlewrapper.appendChild(this.title), this.message = document.createElement("div"), this.message.className = "pdactionsheet_message", this.message.appendChild(document.createTextNode(e.message || "")), this.titlewrapper.appendChild(this.message), this.actions.appendChild(this.titlewrapper)), this.actionswrapper = document.createElement("div"), this.actionswrapper.className = "pdactionsheet_actionswrapper", this.actions.appendChild(this.actionswrapper);
    for (var t = 0; t < e.actions.length; t++) {
      var n = e.actions[t],
        i = document.createElement("button");
      i.className = n.type || "", i.appendChild(document.createTextNode(n.name || "按钮名称未定义")), i.addEventListener("click", n.command), this.actionswrapper.appendChild(i)
    }
    this.cancel = document.createElement("button"), this.cancel.className = "pdactionsheet_cancel", this.cancel.appendChild(document.createTextNode(e.cancel || "取消")), this.actionsheet.appendChild(this.actions), this.actionsheet.appendChild(this.cancel), this.el.appendChild(this.actionsheet)
  }

  function o(e) {
    this.el = document.createElement("div"), this.el.className = "pdnotify_wrapper", this.notify = document.createElement("div");
    var t = "pdnotify";
    e.type && (t = t + " " + e.type), this.notify.className = t, this.notifySpan = document.createElement("span"), this.notifySpan.appendChild(document.createTextNode(e.notify || "notify内容未定义！")), this.notify.appendChild(this.notifySpan), this.el.appendChild(this.notify)
  }
  var r = this;
  PdMessage.prototype.index = PdMessage.prototype.index || 8001, PdMessage.prototype.Modal_background_instance = PdMessage.prototype.Modal_background_instance || new function() {
    this.el = document.createElement("div"), this.el.className = "pdModal_background is_showing is_changing"
  }, PdMessage.prototype.model_zIndexs = PdMessage.prototype.model_zIndexs || [], this.message_is_show = !1, this.MessageInstance = void 0, this.confirm = function(n) {
    if ("string" != typeof n && null != n || (n = {
        title: n,
        message: arguments[1]
      }), !this.message_is_show) return this.message_is_show = !0, this.timing = n.timing || 350, e(!0), n.then || n.catch ? (n._type = "confirm", this.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
      r.MessageInstance.el.className = "pdmsg_wrapper"
    }, 16), this) : new Promise(function(e, i) {
      n._type = "confirm", n.resolve = e, n.reject = i, r.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
        r.MessageInstance.el.className = "pdmsg_wrapper"
      }, 16)
    }).finally(function() {
      window.setTimeout(function() {
        r.closeMessage()
      }, 16)
    });
    console.log("is on show!")
  }, this.prompt = function(n) {
    if ("string" != typeof n && null != n || (n = {
        title: n,
        message: arguments[1]
      }), !this.message_is_show) return this.message_is_show = !0, this.timing = n.timing || 350, e(!0), n.then || n.catch ? (n._type = "prompt", this.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
      r.MessageInstance.el.className = "pdmsg_wrapper"
    }, 16), this) : new Promise(function(e, i) {
      n._type = "prompt", n.resolve = e, n.reject = i, r.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
        r.MessageInstance.el.className = "pdmsg_wrapper"
      }, 16)
    }).finally(function() {
      window.setTimeout(function() {
        r.closeMessage()
      }, 16)
    });
    console.log("is on show!")
  }, this.alert = function(n) {
    if ("string" != typeof n && null != n || (n = {
        title: n,
        message: arguments[1]
      }), !this.message_is_show) return this.message_is_show = !0, this.timing = n.timing || 350, n._type = "alert", e(!0), n.then ? (this.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
      r.MessageInstance.el.className = "pdmsg_wrapper"
    }, 16), this) : new Promise(function(e, i) {
      n.resolve = e, n.reject = i, r.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
        r.MessageInstance.el.className = "pdmsg_wrapper"
      }, 16)
    }).finally(function() {
      window.setTimeout(function() {
        r.closeMessage()
      }, 16)
    });
    console.log("is on show!")
  }, this.stop = function(n) {
    if ("string" != typeof n && null != n || (n = {
        title: n,
        message: arguments[1]
      }), !this.message_is_show) return this.message_is_show = !0, this.timing = n.timing || 350, n._type = "stop", e(!0), this.MessageInstance = new t(n), document.body.appendChild(r.MessageInstance.el), window.setTimeout(function() {
      r.MessageInstance.el.className = "pdmsg_wrapper"
    }, 16), this;
    console.log("is on show!")
  }, this.closeMessage = function() {
    this.message_is_show ? (e(this.message_is_show = !1, parseInt(this.MessageInstance.el.style.zIndex)), this.MessageInstance.el.className = "pdmsg_wrapper is_disappearing is_changing", window.setTimeout(function() {
      r.MessageInstance.el.parentNode.removeChild(r.MessageInstance.el)
    }, r.timing)) : console.log("is NOT on show!")
  }, this.ToastInstance = void 0, this.toast = function(e) {
    "string" != typeof e && null != e || (e = {
      toast: e,
      duration: arguments[1]
    }), this.timing = e.timing || 350, this.ToastInstance = new n(e), document.body.appendChild(this.ToastInstance.el), window.setTimeout(function() {
      r.ToastInstance.el.className = "pdtoast_wrapper", window.setTimeout(function() {
        r.closeToast()
      }, e.duration || 2e3)
    }, e.timing || 16)
  }, this.toast.success = function(e) {
    "string" != typeof e && null != e || (e = {
      toast: e,
      duration: arguments[1]
    }), e.type = "success", r.toast(e)
  }, this.toast.error = function(e) {
    "string" != typeof e && null != e || (e = {
      toast: e,
      duration: arguments[1]
    }), e.type = "error", r.toast(e)
  }, this.closeToast = function() {
    this.ToastInstance.el.className = "pdtoast_wrapper is_disappearing is_changing", window.setTimeout(function() {
      r.ToastInstance.el.parentNode.removeChild(r.ToastInstance.el)
    }, r.timing)
  }, this.ActionsheetInstance = void 0, this.actionsheeet_is_show = !1, this.initActionsheet = function(t) {
    null != t.length && (t = {
      actions: t
    }), this.actionsheeet_is_show = !0, this.timing = t.timing || 300, e(!0), this.ActionsheetInstance = new i(t), document.body.appendChild(this.ActionsheetInstance.el), window.setTimeout(function() {
      r.ActionsheetInstance.el.className = "pdactionsheet_wrapper"
    }, t.timing || 16)
  }, this.closeActionsheet = function() {
    this.actionsheeet_is_show && (e(this.actionsheeet_is_show = !1, parseInt(this.ActionsheetInstance.el.style.zIndex)), this.ActionsheetInstance.el.className = "pdactionsheet_wrapper is_disappearing is_changing", window.setTimeout(function() {
      r.ActionsheetInstance.el.parentNode.removeChild(r.ActionsheetInstance.el)
    }, r.timing))
  }, this.NotifyInstance = void 0, PdMessage.prototype.notify_queue = PdMessage.prototype.notify_queue || [], PdMessage.prototype.notify_queue_is_showing = PdMessage.prototype.notify_queue_is_showing || !1, this.notify = function(e) {
    "string" != typeof e && null != e || (e = {
      notify: e,
      duration: arguments[1]
    }), PdMessage.prototype.notify_queue.push(e), PdMessage.prototype.notify_queue_is_showing || this.notify_queue_show()
  }, this.notify_queue_show = function() {
    if (0 < PdMessage.prototype.notify_queue.length) {
      PdMessage.prototype.notify_queue_is_showing = !0;
      var e = PdMessage.prototype.notify_queue.shift();
      window.setTimeout(function() {
        (new PdMessage).showNotify(e), r.notify_queue_show()
      }, 110)
    } else PdMessage.prototype.notify_queue_is_showing = !1
  }, this.showNotify = function(e) {
    if (this.timing = e.timing || 500, this.NotifyInstance = new o(e), !document.getElementById("pdnotify_container")) {
      var t = document.createElement("div");
      t.setAttribute("id", "pdnotify_container"), document.body.appendChild(t)
    }
    var n = document.getElementById("pdnotify_container");
    n.appendChild(this.NotifyInstance.el);
    var i = this.NotifyInstance.el.clientHeight;
    this.NotifyInstance.el.style.height = i + "px", this.NotifyInstance.el.parentNode.removeChild(this.NotifyInstance.el), this.NotifyInstance.el.className = "pdnotify_wrapper is_showing is_changing", n.appendChild(this.NotifyInstance.el), window.setTimeout(function() {
      r.NotifyInstance.el.className = "pdnotify_wrapper", window.setTimeout(function() {
        r.closeNotify()
      }, e.duration || 5e3)
    }, e.timing || 16)
  }, this.closeNotify = function() {
    this.NotifyInstance.el.className = "pdnotify_wrapper is_disappearing is_changing", window.setTimeout(function() {
      r.NotifyInstance.el.parentNode.removeChild(r.NotifyInstance.el);
      var e = document.getElementById("pdnotify_container");
      0 == e.childElementCount && e.parentNode.removeChild(e)
    }, r.timing)
  }
}
