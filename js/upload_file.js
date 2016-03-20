var control = document.getElementById("upload_file");

control.addEventListener("change", function(event){
    var reader = new FileReader();

    reader.onload = function(event){
       var contents = event.target.result;
       document.getElementById('post_references').value = contents;

       //create hidden textfield with the filetype [ris, enw, bib]
       var fileName = control.files[0].name;
       var form = document.getElementById("create_post_form");
       var txtFileType = document.createElement("input");
       txtFileType.name = "file_type";
       txtFileType.type = "hidden";
       form.appendChild(txtFileType);
       txtFileType.value = fileName.substr(fileName.length - 3);
    };

    reader.onerror = function(event){
       console.error("File could not be read! Error Code: " + event.target.error.code);
    };

    reader.readAsText(control.files[0]);

}, false);

