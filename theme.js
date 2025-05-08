function setTheme(theme) {
    const link = document.getElementById("theme-stylesheet");
    if (!link) return;
    link.href = theme === "dark" ? "dark.css" : "my_trips.css";
    document.cookie = "theme=" + theme + "; path=/";
}

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

window.addEventListener("DOMContentLoaded", () => {
    const theme = getCookie("theme") || "light";
    setTheme(theme);

    const toggle = document.getElementById("themeToggle");
    if (toggle) {
        toggle.addEventListener("click", () => {
            const current = getCookie("theme") || "light";
            const newTheme = current === "light" ? "dark" : "light";
            setTheme(newTheme);
        });
    }
});
