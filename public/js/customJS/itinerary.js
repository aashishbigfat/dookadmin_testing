$("#day").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
          //display error message
          $("#day_error").html("Digits Only").
          show().fadeOut(3000);
          return false;
        }
    });

     $('.destinations').select2({
      placeholder: 'Select Destination(s)',
     });
     $('.edit_destinations').select2();
        //{
    //         placeholder: 'Select Destination(s)',
    //         ajax: {
    //             url: "/get-itinerary_destination-ajax",
    //             dataType: 'json',
    //             delay: 250,
    //             processResults: function (data) {
    //                 return {
    //                     results: $.map(data, function (item) {
    //                         return {
    //                             text: item.dest_name,
    //                             id: item.id
    //                         }
    //                     })
    //                 };
    //             },
    //             cache: true
    //         }
    //     });
    $('.pois').select2();
    $('.edit_pois').select2();
// itinerary ckeditor
    $(document).ready(function() {
      // $('#inclusion').summernote({
      //     height: 100,
      //     focus: true
      //   });
      // $('#exclusion').summernote({
      //     height: 100,
      //     focus: true
      //   });
      // $('#edit_inclusion').summernote({
      //     height: 100,
      //     focus: true
      //   });
      // $('#edit_exclusion').summernote({
      //     height: 100,
      //     focus: true
      //   });
      $('#description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:150
      });
      
      $('#edit_description').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:150
      });
    });
