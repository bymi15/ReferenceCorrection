var control = document.getElementById("upload_file");

control.addEventListener("change", function(event){
    var reader = new FileReader();
    reader.onload = function(event){
       var contents = event.target.result;
       document.getElementById('post_references').value = contents;
    };
    reader.onerror = function(event){
       console.error("File could not be read! Error Code: " + event.target.error.code);
    };
    reader.readAsText(control.files[0]);
}, false);
