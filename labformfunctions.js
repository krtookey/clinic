function toggle(source){
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}

function confirmOverwrite(){
    if (document.getElementById("overwritetext")){
        confirmField = document.getElementById("confirm_failed");
        if(!confirm('Are you sure you want to overwrite the current lab order in this note?')){
            confirmField.value = 1;
            return false;
        } else {
            confirmField.value = 0;
            return true;
        }
    }
}