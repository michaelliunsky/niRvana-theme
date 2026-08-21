const THEME_KEY = "nirvana-theme";
const THEME_PREFS = ["light", "dark", "system"];
let systemThemeMql = null;

function getThemePref() {
  try {
    const pref = localStorage.getItem(THEME_KEY);
    if (THEME_PREFS.indexOf(pref) !== -1) return pref;
  } catch (e) {}
  return "system";
}

function setThemePref(pref) {
  try {
    localStorage.setItem(THEME_KEY, pref);
  } catch (e) {}
}

function systemPrefersDark() {
  return window.matchMedia("(prefers-color-scheme: dark)").matches;
}

function resolvedDark(pref) {
  return pref === "dark" || (pref === "system" && systemPrefersDark());
}

function themeIcon(pref) {
  if (pref === "system") return "fa-adjust";
  if (pref === "dark") return "fa-sun";
  return "fa-moon";
}

const THEME_LABELS = { system: "跟随系统", light: "浅色", dark: "深色" };

function applyTheme(pref) {
  const dark = resolvedDark(pref);
  document.documentElement.classList.toggle("night", dark);
  document.documentElement.style.colorScheme = dark ? "dark" : "light";
  const icon = document.querySelector(".colorSwitch");
  if (icon) {
    icon.className = `colorSwitch fas ${themeIcon(pref)}`;
    const tip = icon.closest("[data-description]");
    if (tip) tip.dataset.description = THEME_LABELS[pref];
  }
}

function onSystemThemeChange() {
  if (getThemePref() === "system") applyTheme("system");
}

function bindSystemThemeListener(pref) {
  const mql = window.matchMedia("(prefers-color-scheme: dark)");
  if (systemThemeMql) {
    systemThemeMql.removeEventListener("change", onSystemThemeChange);
    systemThemeMql = null;
  }
  if (pref === "system") {
    systemThemeMql = mql;
    mql.addEventListener("change", onSystemThemeChange);
  }
}

function switchTheme() {
  const cur = getThemePref();
  const next =
    cur === "system" ? "light" : cur === "light" ? "dark" : "system";
  setThemePref(next);
  applyTheme(next);
  bindSystemThemeListener(next);
}

function initTheme() {
  const config = (window.pandastudio_framework || {}).dark_mode || {};
  if (config.enable !== "checked") {
    document.documentElement.classList.remove("night");
    document.documentElement.style.colorScheme = "light";
    if (systemThemeMql) {
      systemThemeMql.removeEventListener("change", onSystemThemeChange);
      systemThemeMql = null;
    }
    return;
  }
  const pref = getThemePref();
  applyTheme(pref);
  bindSystemThemeListener(pref);
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

  copyButton.addEventListener("mouseenter", () => {
    copyButton.style.backgroundColor = "rgba(0, 0, 0, 0.1)";
  });

  copyButton.addEventListener("mouseleave", () => {
    copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
  });

  copyButton.addEventListener("click", () => {
    const range = document.createRange();
    range.selectNode(codeElement);

    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    document.execCommand("copy");
    selection.removeAllRanges();

    copyButton.innerText = "Copied!";
    copyButton.style.backgroundColor = "#333";
    copyButton.style.color = "#fff";

    setTimeout(() => {
      copyButton.innerText = "Copy Code";
      copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
      copyButton.style.color = "#fff";
    }, 3000);
  });

  codeBlock.style.position = "relative";
  codeBlock.insertBefore(copyButton, codeElement);
}

function initCodeBlocks() {
  document.querySelectorAll(".wp-block-code").forEach(addCopyButtonToCodeBlock);
}

document.addEventListener("DOMContentLoaded", () => {
  initCodeBlocks();
  initTheme();
});

add_action("ajax_render_complete", () => {
  initTheme();
  initCodeBlocks();
});
