console.log(sessionStorage.theme);
console.log(sessionStorage.toChange);

change_mode(sessionStorage.theme);
function change_mode(theme){

    if(theme == null || theme == "light"){
        sessionStorage.toChange="dark";
        sessionStorage.theme="light";
        document.getElementById("toggle-switch").checked = false;
        document.getElementById("head_tag").innerHTML=document.getElementById("head_tag").innerHTML.replace('<link rel="stylesheet" href="/cms/assets/css/dark-mode.css">',"");
    }else{
        sessionStorage.toChange="light";
        sessionStorage.theme="dark";
        document.getElementById("toggle-switch").checked = true;
        document.getElementById("head_tag").innerHTML += '<link rel="stylesheet" href="/cms/assets/css/dark-mode.css">';
    }
}
