console.log(sessionStorage.theme);
console.log(sessionStorage.toChange);

change_mode(sessionStorage.theme);
function change_mode(theme){
    const toggleSwitch = document.getElementById("toggle-switch");
    const headTag = document.getElementById("head_tag");

    if (!toggleSwitch || !headTag) {
        console.warn("Dark mode toggle elements not found");
        return;
    }

    if(theme == null || theme == "light"){
        sessionStorage.toChange="dark";
        sessionStorage.theme="light";
        toggleSwitch.checked = false;
        headTag.innerHTML = headTag.innerHTML.replace('<link rel="stylesheet" href="/static/assets/css/dark-mode.css">',"");
    }else{
        sessionStorage.toChange="light";
        sessionStorage.theme="dark";
        toggleSwitch.checked = true;
        headTag.innerHTML += '<link rel="stylesheet" href="/static/assets/css/dark-mode.css">';
    }
}
