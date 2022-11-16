function refreshElement(elementid){
    //alert("In function");
    var container = document.getElementById(elementid);
    var content = container.innerHTML;
    //alert(content);
    container.innerHTML = content;
}