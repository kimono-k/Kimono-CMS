$(document).ready(function() {
    // EDITOR CKEDITOR
    ClassicEditor
        .create( document.querySelector( '#body' ) )
        .catch( error => {
            console.error( error );
        });
    
    // SELECT ALL CHECKBOXES FOREACH LOOP.
    $('#selectAllBoxes').click(function(event) {

        if (this.checked) {
            $('.checkBoxes').each(function() {
               this.checked = true; 
            });

        } else {
            $('.checkBoxes').each(function() {
                this.checked = false;
            });
        }
    });
});