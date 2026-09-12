$('#difficulty_tags').select2({
    placeholder: 'Add Tour Difficulty Tag(s)',
    //tags: true,
})
$('#type_tags').select2({
    placeholder: 'Add Tour Type Tag(s)',
    //tags: true,
})  
$('#tags').select2({
    placeholder: 'Select Tag(s)',
    //tags: true,
})
$('#tour_class').select2();

    function get_no_of_days(e) {
          if($('#nights').val()){
            $('#days').val(parseInt($('#nights').val()) +1 );
          }else{
            $('#days').val('');
          }
    }
    $(document).ready(function() {
      $('#visa_information').summernote({
          height: 100,
          focus: true
        });
      $('#conditions').summernote({
          height: 100,
          focus: true
        });
    });
    $("#price").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
          //display error message
          $("#price_error").html("Digits Only").
          show().fadeOut(3000);
          return false;
        }
    });
    $("#team_size").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
          //display error message
          $("#team_sizes_error").html("Digits Only").
          show().fadeOut(3000);
          return false;
        }
    });
    // Agent itinerary ajax list

    $('.itinerary').select2({
            placeholder: 'Select Itinerary',
            ajax: {
                url: "/agent-itinerary-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.title,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

     $('.experiences').select2({
            placeholder: 'Select Experience(s)',
            ajax: {
                url: "/get-experience-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.experience_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
// Ajax Destination Search from pullit

    $(document).ready(function(){
      //var s_count=0;
        $('.destinations').on('keyup', function(){
          //alert('hello');
            var text = $('.destinations').val();
           if(text.length > 1){
                  $.ajax({
                      type:"post",
                      url: 'https://pullit.tutterflycrm.com/api/get_destination',
                      data: {text: $('.destinations').val()},
                      success: function(data) {
                        console.log(data);
                        if(data && data.destination.length>0){
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
                          for(dest of data.destination){
                              if(k<15){
                                //console.log(dest.regionIds);
                                 var dest_id=dest.id;
                                 var dest_name=dest.dest_name;
                                 var dest_names = dest_name.replace(/'/,'');  

                                 var actual_name=dest.actualname;
                                 if(actual_name){
                                    var actual_names = actual_name.replace(/'/,''); 
                                 }
                                 else{
                                  var actual_names = '';
                                 }

                                var f_class=dest.feature_class;
                                if(f_class){
                                    var f_classs = f_class.replace(/'/,'');
                                }
                                else{
                                  var f_classs = '';
                                 }

                                 var f_code=dest.feature_code;
                                 if(f_code){
                                     var f_codes = f_code.replace(/'/,'');
                                  }
                                  else{
                                  var f_codes = '';
                                 }

                                 var geonameids=dest.geonameid;

                                 var region=dest.region;
                                 var regions = region.replace(/'/,'');

                                 var region_code=dest.region_code;
                                 var region_codes = region_code.replace(/'/,'');
                                 //console.log(destination);

                                 var region_ids=dest.dest_region_id;

                                 var iso2=dest.country_iso_2;
                                 var iso2s = iso2.replace(/'/,'');

                                 var iso3=dest.country_iso_3;
                                 var iso3s = iso3.replace(/'/,'');

                                 var singleimage = dest.image;
                                 if(singleimage){
                                     var dest_img = singleimage.replace(/'/,'');    
                                 } 

                                 var descriptions = JSON.stringify(dest.description);

                                 //console.log(typeof(descriptions));
                                 if(descriptions){
                                     var descriptionz = descriptions.replace(/["']+/g, '',/'/);
                                 }

                                 var countryname=dest.country_name;
                                 var country_names = countryname.replace(/'/,'');  
                                 
                                 var countryid=dest.count_id;

                                 var official_names=dest.official_name;
                                 if(official_names){
                                  var official_name = official_names.replace(/'/,''); 
                                 } 
                                 else{
                                  var official_name = '';
                                 }

                                 var capitals=dest.capital;
                                 if(capitals){
                                  var capital = capitals.replace(/'/,'');  
                                 }
                                 else{
                                  var capital = '';
                                 }
                                 var largest_citys=dest.largest_city;
                                 if(largest_citys){
                                  var largest_city = largest_citys.replace(/'/,'');  
                                 }else{
                                  var largest_city = '';
                                 }
                                 var continents=dest.continent;
                                 if(continents){
                                  var continent = continents.replace(/'/,'');
                                  }else{
                                  var continent = '';
                                 }
                                 var count_dess = JSON.stringify(dest.count_des);
                                 if(count_dess){
                                     var count_des = count_dess.replace(/["']+/g, '',/'/);
                                 } 
                                 else{
                                    var count_des = '';
                                 }

                                 var sub_continents=dest.sub_continent;
                                 if(sub_continents){
                                  var sub_continent = sub_continents.replace(/'/,''); 
                                 } 
                                 else{
                                    var sub_continent = '';
                                 } 

                                 var iso_2s=dest.iso_2;
                                 var iso_2 = iso_2s.replace(/'/,'');  

                                 var iso_3s=dest.iso_3;
                                 var iso_3 = iso_3s.replace(/'/,'');

                                 var isd_codes=dest.isd_code;
                                 if(isd_codes){
                                    var isd_code = isd_codes.replace(/'/,'');  
                                 } 
                                 else{
                                    var isd_code = '';
                                 } 
                                 var count_lats=dest.count_lat; 

                                 var count_longs=dest.count_long;  

                                 var internet_tlds=dest.internet_tld;
                                 if(internet_tlds){
                                   var internet_tld = internet_tlds.replace(/'/,'');
                                 }else{
                                   var internet_tld = '';
                                 }
                                 var currencys=dest.currency;
                                 if(currencys){
                                   var currency = currencys.replace(/'/,'');
                                 }else{
                                   var currency = '';
                                 }  

                                 var currency_symbols=dest.currency_symbol;
                                 if(currency_symbols){
                                   var currency_symbol = currency_symbols.replace(/'/,''); 
                                 }else{
                                   var currency_symbol = '';
                                 } 

                                 var currency_codes=dest.currency_code;
                                 if(currency_codes){
                                 var currency_code = currency_codes.replace(/'/,'');  
                                 }else{
                                   var currency_code = '';
                                 } 

                                 var drives_ons=dest.drives_on;
                                 if(drives_ons){
                                  var drives_on = drives_ons.replace(/'/,'');
                                 }else{
                                   var drives_on = '';
                                 } 

                                 var areas=dest.area;
                                 if(areas){
                                  var area = areas.replace(/'/,''); 
                                 }else{
                                   var area = '';
                                 } 

                                 var area_units=dest.area_unit;
                                 if(area_units){
                                 var area_unit = area_units.replace(/'/,'');
                                 }else{
                                   var area_unit = '';
                                 }

                                 var populations=dest.population;
                                 if(populations){
                                 var population = populations.replace(/'/,''); 
                                 }else{
                                   var population = '';
                                 } 

                                 var count_images=dest.count_image;
                                 if(count_images){
                                 var count_image = count_images.replace(/'/,''); 
                                 }else{
                                   var count_image = '';
                                 } 

                                 var flags=dest.flag;
                                 if(flags){
                                 var flag = flags.replace(/'/,'');
                                 }else{
                                   var flag = '';
                                 } 

                                 //var sub_continent = dest.sub_cont_name;
                                 //var sub_continent_name = sub_continent.replace(/'/,''); 

                                 var sub_continent_id=dest.regionIds;
                                 //console.log(sub_continent_id);
                                 var add = dest_names +'('+country_names+')';
                                 html+="<li onclick='initDestination("+dest.latitude+","+dest.longitude+",&quot;"+dest_names+"&quot;,&quot;"+country_names+"&quot;,&quot;"+actual_names+"&quot;,&quot;"+f_classs+"&quot;,&quot;"+f_codes+"&quot;,&quot;"+geonameids+"&quot;,&quot;"+regions+"&quot;,&quot;"+region_codes+"&quot;,&quot;"+region_ids+"&quot;,&quot;"+iso2s+"&quot;,&quot;"+iso3s+"&quot;,&quot;"+dest_img+"&quot;,&quot;"+descriptionz+"&quot;,"+dest.id+","+count_lats+","+count_longs+",&quot;"+official_name+"&quot;,&quot;"+capital+"&quot;,&quot;"+largest_city+"&quot;,&quot;"+continent+"&quot;,&quot;"+count_des+"&quot;,&quot;"+sub_continent+"&quot;,&quot;"+iso_2+"&quot;,&quot;"+iso_3+"&quot;,&quot;"+isd_code+"&quot;,&quot;"+internet_tld+"&quot;,&quot;"+currency+"&quot;,"+countryid+",&quot;"+currency_symbol+"&quot;,&quot;"+currency_code+"&quot;,&quot;"+drives_on+"&quot;,&quot;"+area+"&quot;,&quot;"+area_unit+"&quot;,&quot;"+population+"&quot;,&quot;"+sub_continent_id+"&quot;,&quot;"+count_image+"&quot;,&quot;"+flag+"&quot;)'><i style='margin-right:5px; color:#A9A9A9' class='fas fa-map-marker-alt'></i> <b style='color:#222'>"+dest.dest_name+' ('+regions+')'+' ('+country_names+')'+"</b></li>";
                              }                       
                              k++;
                          }
                          html+="</ul>";                  
                         $(".autocomplete-items").html(html);
          }

          var dest_selected=[];
    function initDestination(Lat, Long, dest_name, country, actual_names, f_classs, f_codes, geonameids, regions, region_codes, region_ids, iso2s, iso3s, dest_img, descriptionz, destId, countLat, countLong, officeName, capital, largeCity, continent, countDesc, subCountinent, countIso2, countIso3, isdCode, internetTld, currency, cointryId, currencySymbol, currencyCode, driveOn, area, areaUnit, population, sub_continent_id, countryImage, flag) {

        dest_selected.push(
            {

                'name':dest_name,
                'actual_name':actual_names,
                'country':country,
                'id':destId,
                'lat':Lat,
                'long':Long,
                'fclass':f_classs,
                'fcodes':f_codes,
                'geonameid':geonameids,
                'region':regions,
                'regioncode':region_codes,
                'regionid':region_ids,
                'iso2':iso2s,
                'iso3':iso3s,
                'destimg':dest_img,
                'description':descriptionz,
                'country_id':cointryId,
                'country_lat':countLat,
                'country_long':countLong,
                'official_name':officeName,
                'capital':capital,
                'largest_city':largeCity,
                'continent':continent,
                'sub_continent':subCountinent,
                'count_description':countDesc,
                'count_iso2':countIso2,
                'count_iso3':countIso3,
                'isd_code':isdCode,
                'internet_tld':internetTld,
                'currency':currency,
                'currency_symbol':currencySymbol,
                'currency_code':currencyCode,
                'drive_on':driveOn,
                'area':area,
                'area_unit':areaUnit,
                'population':population,
                //'sub_continent_name':sub_continent_name,
                'sub_continent_id':sub_continent_id,
                'country_image':countryImage,
                'flag':flag,
            }
            );
        $(".autocomplete-items").css('display','none');
        $(".destinations").val('');
        $('#destinationName').val(JSON.stringify(dest_selected));
        console.log(dest_selected);
        set_dest_html();     
    }

    function remove_des(i){
        //alert(i);
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
            $('#destinationName').val(JSON.stringify(dest_selected));
        }else{
             $("#dropdest").html('');
             $('#destinationName').val(JSON.stringify(dest_selected));
        }
    }

  
