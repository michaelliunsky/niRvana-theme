let autoNightTimer = null;
/** 获取当天结束时间的UTC字符串 */
function getCookieExpireTime() {
  const end = new Date();
  end.setHours(23, 59, 59, 999);
  return end.toUTCString();
}

/** 获取指定Cookie值（不存在返回空字符串） */
function getCookie(name) {
  if (typeof document === "undefined" || !document.cookie) return "";
  const cookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith(name + "="));
  if (!cookie) return "";
  return decodeURIComponent(cookie.split("=")[1] || "");
}

/** 设置Cookie（使用当天结束为过期时间） */
function setCookie(name, value) {
  const v = encodeURIComponent(String(value));
  document.cookie = `${name}=${v}; path=/; expires=${getCookieExpireTime()}`;
}

/** 删除指定Cookie */
function deleteCookie(name) {
  document.cookie = `${name}=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT`;
}

/** 应用深色模式状态 */
function applyNightMode(isNight) {
  document.body.classList.toggle("night", isNight);
  const selector = document.querySelector(".colorSwitch");
  if (selector)
    selector.className = `colorSwitch fas ${isNight ? "fa-sun" : "fa-moon"}`;
}

/** 切换深色模式并保存状态 */
function switchNightMode() {
  const willBeNight = !document.body.classList.contains("night");
  applyNightMode(willBeNight);
  setCookie("night", willBeNight ? "1" : "0");
}

/** 解析时间字符串为分钟数 */
function parseTimeToMinutes(timeStr) {
  if (typeof timeStr !== "string") return null;

  const timeRegex = /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/;
  if (!timeRegex.test(timeStr.trim())) {
    return null;
  }

  const parts = timeStr.split(":");
  const h = parseInt(parts[0], 10);
  const m = parseInt(parts[1], 10);

  if (h < 0 || h > 23 || m < 0 || m > 59) {
    return null;
  }

  return h * 60 + m;
}

/** 初始化深色模式 */
function initNightMode() {
  const config = (window.pandastudio_framework || {}).dark_mode || {};
  const { enable = "unchecked", auto = "unchecked" } = config;

  // 先清理旧定时器，避免重复注册
  if (autoNightTimer) {
    clearInterval(autoNightTimer);
    autoNightTimer = null;
  }

  // 未启用深色模式时删除cookie
  if (enable !== "checked") {
    deleteCookie("night");
    return applyNightMode(false);
  }

  // 如果已有 cookie，直接按 cookie 生效。
  const nightCookie = getCookie("night");
  if (nightCookie !== "") {
    return applyNightMode(nightCookie === "1");
  }

  if (auto !== "checked") {
    deleteCookie("night");
    return applyNightMode(false);
  }

  const DEFAULT_TIMES = { start: "19:00", end: "07:00" };
  const startMinutes =
    parseTimeToMinutes(config.time_start) !== null &&
    parseTimeToMinutes(config.time_start) !== undefined
      ? parseTimeToMinutes(config.time_start)
      : parseTimeToMinutes(DEFAULT_TIMES.start);
  const endMinutes =
    parseTimeToMinutes(config.time_end) !== null &&
    parseTimeToMinutes(config.time_end) !== undefined
      ? parseTimeToMinutes(config.time_end)
      : parseTimeToMinutes(DEFAULT_TIMES.end);

  const checkAutoNight = () => {
    // 自动模式下，如果用户后来手动设置 cookie，就不再覆盖用户的选择
    if (getCookie("night") !== "") return;

    const now = new Date();
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    const isNight =
      startMinutes > endMinutes
        ? nowMinutes >= startMinutes || nowMinutes < endMinutes
        : nowMinutes >= startMinutes && nowMinutes < endMinutes;
    applyNightMode(isNight);
  };

  checkAutoNight();
  autoNightTimer = setInterval(checkAutoNight, 60000);
}

/**
 * 为代码块添加复制按钮
 * @param {HTMLElement} codeBlock - 代码块容器元素
 */
function addCopyButtonToCodeBlock(codeBlock) {
  const codeElement = codeBlock.querySelector("code");
  const copyButton = document.createElement("button");
  const buttonText = document.createTextNode("Copy Code");

  copyButton.appendChild(buttonText);
  copyButton.style.cssText = `
    position: absolute;
    top: 0;
    right: 0;
    margin: 4px;
    padding: 4px 8px;
    font-size: 12px;
    background-color: rgba(200, 200, 200, 0.2);
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    z-index: 1000;
  `;

  // 鼠标悬停效果
  copyButton.addEventListener("mouseenter", () => {
    copyButton.style.backgroundColor = "rgba(0, 0, 0, 0.1)";
  });

  copyButton.addEventListener("mouseleave", () => {
    copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
  });

  // 复制功能
  copyButton.addEventListener("click", () => {
    const range = document.createRange();
    range.selectNode(codeElement);

    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    document.execCommand("copy");
    selection.removeAllRanges();

    // 更新按钮状态
    copyButton.innerText = "Copied!";
    copyButton.style.backgroundColor = "#333";
    copyButton.style.color = "#fff";

    // 3秒后恢复原始状态
    setTimeout(() => {
      copyButton.innerText = "Copy Code";
      copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
      copyButton.style.color = "#fff";
    }, 3000);
  });

  // 添加复制按钮到代码块
  codeBlock.style.position = "relative";
  codeBlock.insertBefore(copyButton, codeElement);
}

/**
 * 初始化代码块样式和功能
 */
function initCodeBlocks() {
  // 为所有代码块添加复制按钮
  document.querySelectorAll(".wp-block-code").forEach(addCopyButtonToCodeBlock);

  // 设置代码块样式
  document.querySelectorAll(".wp-block-code pre").forEach((preElement) => {
    preElement.style.color = "#fff";
  });
}

/**
 * 初始化评论区域代码高亮
 */
function initCommentCodeHighlight() {
  $(".comments pre").each(function (_, element) {
    const codeElement = $(element).children("code");
    const targetElement = codeElement.length > 0 ? codeElement.get(0) : element;

    if (
      !$(targetElement).hasClass("hljs") &&
      !$(targetElement).parent().hasClass("disable_highlightjs")
    ) {
      hljs.highlightBlock(targetElement);
      hljs.lineNumbersBlock(targetElement, { singleLine: true });
    }
  });
}

/**
 * 初始化卡片悬停动画
 */
function initCardHoverAnimations() {
  jQuery(document).ready(($) => {
    $(".postLists.lists .card h2, .pf_hotposts h4").hover(
      function () {
        $(this).stop().animate({ marginLeft: "15px" }, 300);
      },
      function () {
        $(this).stop().animate({ marginLeft: "0px" }, 300);
      }
    );

    // 点击动画
    $(".postLists.lists .card h2, .pf_hotposts h4").click(function () {
      const originalText = this.textContent;
      $(this).text("正在努力加载中 …");

      setTimeout(() => {
        $(this).text(originalText);
      }, 2011);
    });
  });
}

// 主初始化函数
document.addEventListener("DOMContentLoaded", () => {
  initCodeBlocks();
  initCommentCodeHighlight();
  initCardHoverAnimations();
  initNightMode();
});

// AJAX加载完成后重新初始化代码块
add_action("ajax_render_complete", () => {
  initCodeBlocks();

  // 重新设置代码块样式
  document.querySelectorAll(".wp-block-code pre").forEach((preElement) => {
    preElement.style.color = "#fff";
  });
});
