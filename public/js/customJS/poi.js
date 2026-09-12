// Ajax POI Search from pullit
    $('#destinations').select2();
    // $('#experiences').select2({
    //   placeholder: 'Select Experience(s)',
    // });
    $('#activities').select2({
      placeholder: 'Select Activities',
    });
    $('#poiName').select2({
      placeholder: 'Select Point of Interest(s)',
    });
    // Validation onclick
    $("#pois").keypress(function (e) {
      var destpoi = $('#destinations').val();
        if(destpoi == ''){
          alert('Select a destination before searching POIs, for which you want to add poi!');
          return false;
        }
    });
    //$("#experiences").click(function() {
      //var destpoi = $('#destinations').val();
        // if($('#destinations').val()){
        //   $('#experiences').prop('disabled', 'false');
        //   return false;
        // }
    //});

    // $("#activities").click(function() {
    //   var destpoi = $('#destinations').val();
    //     if(destpoi == ''){
    //       alert('Select a destination before selecting the activities.!');
    //       return false;
    //     }
    // });
    //Validation onclick end
    $(document).ready(function(){
        $('.pois').on('keyup', function(){
            var text = $('.pois').val();
           if(text.length > 1){
                  $.ajax({
                      type:"post",
                      url: 'http://api.tutterflycrm.com/ptprog/api/get_poi',
                      data: {text: $('.pois').val()},
                      success: function(data) {
                        console.log(data);
                        if(data && data.pois.length>0){
                            fetch_from_pullit(data);  
                        }
                       }
                  });

           }else{
                $(".autocomplete-items").css('display','none');
           }          
        });
      });
    function fetch_from_pullit(data){
        $(".autocomplete-items").css('display','block');
                          var html="";
                          html+="<ul class='search-list'>";                
                           let k=0;
                          for(poi of data.pois){
                              if(k<10){
                                 var poi_id=poi.id;
                                 var poi_name=poi.point_name;
                                 var poi_names = poi_name.replace(/'/,'');  

                                 var poi_type=poi.poi_type;
                                 if(poi_type){
                                  var poi_types = poi_type.replace(/'/,'');
                                 }
                                 else{
                                  var poi_types = '';
                                 }
                                  
                                 var rating=poi.rating;
                                 if(rating){
                                  var ratings = rating.replace(/'/,'');
                                 }
                                 else{
                                  var ratings = '';
                                 }

                                 var review=poi.reviews;

                                 var dest_name=poi.dest_name;
                                 var dest_names = dest_name.replace(/'/,'');

                                 var descriptions = JSON.stringify(poi.description);

                                 //console.log(typeof(descriptions));
                                 if(descriptions){
                                     var descriptionz = descriptions.replace(/["']+/g, '',/'/);
                                 }
                                 else{
                                  var descriptionz = '';
                                 }

                                 var addres=poi.address;
                                 var address = addres.replace(/'/,'');

                                 var countryname = poi.country_name;
                                 var country_names = countryname.replace(/'/,'');  
                                 
                                 var add = poi_names+',' +dest_names+',' +country_names;
                                 html+="<li onclick='initPOI("+poi.latitude+","+poi.longitude+",&quot;"+poi_names+"&quot;,&quot;"+poi_types+"&quot;,&quot;"+ratings+"&quot;,&quot;"+review+"&quot;,&quot;"+address+"&quot;,&quot;"+poi.image+"&quot;,&quot;"+descriptionz+"&quot;,"+poi_id+")'><i style='margin-right:5px; color:#A9A9A9' class='fas fa-map-marker-alt'></i> <b style='color:#222'>"+poi.point_name+"</b>, "+poi.dest_name+", "+poi.country_name+"</li>";
                              }                       
                              k++;
                          }
                          html+="</ul>";                  
                         $(".autocomplete-items").html(html);
          }

          var dest_selected=[];
    function initPOI(lat, long, pname, ptype, rating, review, address, image, description, poiId) {

        dest_selected.push(
            {
                'name':pname,
                'id':poiId,
                'ptype':ptype,
                'rating':rating,
                'reviews':review,
                'address':address,
                'description':description,
                'latitude':lat,
                'longitude':long,
                'image':image,
            }
            );
        $(".autocomplete-items").css('display','none');
        $(".pois").val('');
        $('#poiName').val(JSON.stringify(dest_selected));
        console.log(dest_selected);
        set_dest_html();
         
        
        
    }

    function remove_des(i){
        console.log(dest_selected[i]);
        dest_selected.splice(i,1);
        set_dest_html();
    }

   function set_dest_html(){
        var dest_html='';
        if(dest_selected.length>0){
            var i=0;
            for(var des of dest_selected){
                dest_html+='<span class="badge badge-pill badge-primary dest_badge">'+des.name+'  <i class="fa fa-times" onclick="remove_des('+i+')"></i></span>';
                i++;

            }
            $("#dropdest").html(dest_html);
            $('#poiName').val(JSON.stringify(dest_selected));
        }else{
             $("#dropdest").html('');
             $('#poiName').val(JSON.stringify(dest_selected));
        }
    }