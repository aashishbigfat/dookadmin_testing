@extends('layouts.apps')
@section('headSection')
@section('title', 'Package | Hotel Categories')
<link rel="stylesheet" href="{{ asset('css/customCSS/inclusion.css') }}">
@endsection
@section('main-content')
<?php
$hCategories = [5, 4, 3, 2, 1];
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Hotel Categories</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('packages') }}">Packages</a></li>
            <li class="active">Add Hotel Categories</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="steps clearfix text-center">
                        @include('layouts/land_itinerary_menu')
                    </div>
                </div>
            </div>
            <form id="InclusionForm">
                @csrf
                <div class="box-body wrappers">
                    <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                        <h4 style="padding-bottom: 20px;">Hotel Category Create</h4>
                    </div>
                    <div class="rowes">
                        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Hotel Category</label>
                                <select class="form-control" name="hotel_category_name" id="hotel_category_name">
                                  <option value="">Select Hotel Category</option>
                                    @foreach ($hCategories as $hc)
                                        <option value="{{ $hc }}">{{ $hc }} Star</option>
                                    @endforeach
                                </select>
                                <span class="validationError" id="hotel_category_name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Price INR</label>
                                <input type="text" id="price_inr" name="price_inr" class="form-control">
                                <span class="validationError" id="price_inr_error"></span>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Price USD</label>
                                <input type="text" id="price_usd" name="price_usd" class="form-control">
                                <span class="validationError" id="price_usd_error"></span>
                            </div>
                        </div>
                              <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Destination</label>
                                <select name="destination" id="destination" class="form-control">
                                    <option value="">Select Destination</option>
                                    @forelse ($destinations as $destination)
                                        <option value="{{ $destination->dest_name }}">{{ $destination->dest_name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No destination exists</option>
                                    @endforelse
                                </select>
                                <span class="validationError" id="destination_error"></span>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Hotel</label>
                                <select name="hotel" id="hotel" class="form-control hotel">
                                    <option value="">Select Hotel</option>
                                </select>
                                <span class="validationError" id="hotel_error"></span>
                            </div>
                        </div>
                        <!-- <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12" style="margin-top: 25px;">
                  <div class="floating-label">
                    <a href="javascript:void(0);" class="add_button" title="Add field"><img class="ImgWidth" src="{{ asset('images/add-icon.png') }}"></a>
                  </div>
                </div> -->
                    </div>
                    <!-- </div> -->
                    <!-- <div class="box-body"> -->
                    <div class="col-md-2 col-lg-2 col-sm-12 button-submit text-center" style="    margin-top: 25px;">
                        <button class="btn btn-primary active" type="button" id="store_form">
                            <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                            <span class="crop_wait" style="display: none">
                                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                            </span>
                        </button>
                        <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;"> -->
                        <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                    </div>
                    <!--  </div>  -->
            </form>
        </div>
        <div class="box" style="margin-top: 50px;padding: 0px 15px 0px 15px;">
            <div class="box-header with-border" style="margin-top: -10px;">
                <h3>Hotel Category List <span style="color: brown;margin-left: 35px;">{{ $package }}</span></h3>
            </div>
            <div class="ItninerarListing">
                @include('packages/hotel_category_data_list')
            </div>

        </div>
    </section>
</div>
<div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form method="post" name="myForm" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="modal-dialog modal-xl" role="document" style="width: 50%">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Edit Hotel Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="itinerary-setup m-t-20">
                        <div class="days" style="margin:-10px">
                            <div class="rowes">
                                <input class="form-control" type="hidden" id="edit_id">
                                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Hotel Category</label>
                                        <select class="form-control" name="edit_hotel_category_name"
                                            id="edit_hotel_category_name">
                                            @foreach ($hCategories as $hc)
                                                <option value="{{ $hc }}">{{ $hc }} Star</option>
                                            @endforeach
                                        </select>


                                        <span class="validationError" id="edit_hotel_category_name_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Price INR</label>
                                        <input type="text" id="edit_price_inr" name="edit_price_inr"
                                            class="form-control">
                                        <span class="validationError" id="edit_price_inr_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Price USD</label>
                                        <input type="text" id="edit_price_usd" name="edit_price_usd"
                                            class="form-control">
                                        <span class="validationError" id="edit_price_usd_error"></span>
                                    </div>
                                  </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <select name="edit_destination" id="edit_destination" class="form-control">
                                            <option value="">Select Destination</option>
                                            @forelse ($destinations as $destination)
                                                <option value="{{ $destination->dest_name }}">
                                                    {{ $destination->dest_name }}
                                                </option>
                                            @empty
                                                <option value="" disabled>No destination exists</option>
                                            @endforelse
                                        </select>
                                        <span class="validationError" id="edit_destination_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Hotel</label>
                                        <select name="edit_hotel" id="edit_hotel" class="form-control hotel">
                                            <option value="">Select Hotel</option>
                                            @forelse ($hotel_categories as $hotel)
                                                (@isset($hotel->hotel_name)
                                                    <option value="{{ $hotel->hotel_name }}">{{ $hotel->hotel_name }}
                                                    </option>
                                                @endisset)
                                            @empty
                                                <option value="">Select Hotel</option>
                                            @endforelse
                                        </select>
                                        <span class="validationError" id="edit_hotel_error"></span>
                                    </div>
                               
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-primary" id="update_itinerary">
                        <span class="crop_text_edit"><i class="fa fa-save"></i> Update</span>
                        <span class="crop_wait_edit" style="display: none">
                            Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                        </span>
                    </button>
                    <!-- <img src="{{ asset('images/loader.gif') }}" id="edit_gif" style="width: 6%; visibility: hidden;"> -->
                    <span id="messages"></span>
                </div>
            </div>
        </div>
    </form>
</div>
<style type="text/css">
    .modal-content {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ebedf2;
        border-radius: .3rem;
        outline: 0;
    }

    .validationError {
        color: #9a191e;
    }
</style>
@endsection
@section('footerSection')
<script>
    //Inr To Usd & vise Versa
    var default_inr = "<?php echo $inr; ?>"
    $('#price_inr').keyup(function() {
        var price_inr;
        price_inr = parseFloat($('#price_inr').val());
        if (price_inr) {
            var result = Math.floor(price_inr / default_inr);
            if ($("#price_usd").val(result)) {
                $("#price_usd").val(result)
                //$("#price_usd").prop("readonly", true);
            }
        } else {
            $("#price_usd").val('')
            //$("#price_usd").prop("readonly", false);
        }
    });
    $('#price_usd').keyup(function() {
        var price_usd;
        price_usd = parseFloat($('#price_usd').val());
        if (price_usd) {
            var result = Math.floor(price_usd * default_inr);
            if ($("#price_inr").val(result)) {
                $("#price_inr").val(result)
                //$("#price_inr").prop("readonly", true);
            }
        } else {
            $("#price_inr").val('')
            //$("#price_inr").prop("readonly", false);
        }
    });

    // Edit Form

    //Inr To Usd & vise Versa
    $('#edit_price_inr').keyup(function() {
        var edit_price_inr;
        edit_price_inr = parseFloat($('#edit_price_inr').val());
        if (edit_price_inr) {
            var edit_result = Math.floor(edit_price_inr / default_inr);
            if ($("#edit_price_usd").val(edit_result)) {
                $("#edit_price_usd").val(edit_result)
                //$("#edit_price_usd").prop("readonly", true);
            }
        } else {
            $("#edit_price_usd").val('')
            //$("#edit_price_usd").prop("readonly", false);
        }
    });
    $('#edit_price_usd').keyup(function() {
        var edit_price_usd;
        edit_price_usd = parseFloat($('#edit_price_usd').val());
        if (edit_price_usd) {
            var edit_result = Math.floor(edit_price_usd * default_inr);
            if ($("#edit_price_inr").val(edit_result)) {
                $("#edit_price_inr").val(edit_result)
                //$("#edit_price_inr").prop("readonly", true);
            }
        } else {
            $("#edit_price_inr").val('')
            //$("#edit_price_inr").prop("readonly", false);
        }
    });
    // fIELD INCEMENT
    // $(document).ready(function(){
    //   var maxFields = 50;
    //   var addButtons = $('.add_button');
    //   var wrappers = $('.wrappers');
    //   var fieldHTMLs = '<div class="rowes"><div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12"><label>Hotel Category</label><div class="form-group"><input name="name[]" id="name" class="form-control" type="text"></div></div><div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12"><label>Price INR</label><div class="form-group"><input type="text" name="price_inr[]" id="price_inr" class="form-control"></div></div><div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12"><label>Price USD</label><div class="form-group"><input type="text" name="price_usd[]" id="price_usd" class="form-control"></div></div><div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset('images/remove-icon.png') }}"/></a></div></div></div>';  
    //   var x = 1;

    //   $(addButtons).click(function(){
    //       if(x < maxFields){ 
    //           x++;
    //           $(wrappers).append(fieldHTMLs);
    //       }
    //   });
    //   $(wrappers).on('click', '.remove_button', function(e){
    //       e.preventDefault();
    //        $(".rowes").last().remove();

    //       x--;
    //   });
    // });

    // Form Submit 

    $(document).ready(function() {
        $('#store_form').click(function(e) {
            e.preventDefault();
            //$('#gif').show();
            $(".crop_wait").show();
            $(".crop_text").hide();
            //var checkbox = $("input[type='checkbox']").val();
            //alert(checkbox);
            var hotel_category_name = $('#hotel_category_name').val();
            if (hotel_category_name == "") {
                $(".crop_wait").hide();
                $(".crop_text").show();
                $("span#hotel_category_name_error").html('This field is required!');
                $("input#hotel_category_name").focus();
                return false;
            }
            var price_inr = $('#price_inr').val();
            if (price_inr == "") {
                $(".crop_wait").hide();
                $(".crop_text").show();
                $("span#hotel_category_name_error").hide();
                $("span#price_inr_error").html('This field is required!');
                $("input#price_inr").focus();
                return false;
            }
            var price_usd = $('#price_usd').val();
            if (price_usd == "") {
                $(".crop_wait").hide();
                $(".crop_text").show();
                $("span#title_error").hide();
                $("span#price_inr_error").html('This field is required!');
                $("input#price_usd").focus();
                return false;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('hotel_category_store', request()->route('id')) }}",
                data: $('#InclusionForm').serialize(),
                success: function(data) {
                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                    //window.location = data.url;
                    window.location.reload();
                },
                errors: function() {

                }
            });
        });
    });
</script>
<script>
    $('.edit-item').on('click', function() {
        $('#edit-item').modal('show');
        var id = $(this).data('id');
        var hotel = $(this).data('hotel');
        var inr = $(this).data('inr');
        var usd = $(this).data('usd');
        var dest = $(this).data('dest');
        var hotelname = $(this).data('hotel_name');

        $("#edit_id").val(id);
        //$("#edit_hotel_category_name").val(hotel);
        $("#edit_price_inr").val(inr);
        $("#edit_price_usd").val(usd);
        $('#edit_hotel_category_name option[value="' + hotel + '"]').attr("selected", "selected");
           $('#edit_destination option[value="' + dest + '"]').attr("selected", "selected");
        $('#edit_hotel option[value="' + hotelname + '"]').attr("selected", "selected");
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#update_itinerary').click(function(e) {
            e.preventDefault();
            $(".crop_wait_edit").show();
            $(".crop_text_edit").hide();
            var edit_id = $("#edit_id").val();
            var edit_hotel_category_name = $('#edit_hotel_category_name').val();
            if (edit_hotel_category_name == "") {
                $(".crop_wait_edit").hide();
                $(".crop_text_edit").show();
                $("span#edit_hotel_category_name_error").html('This field is required!');
                $("input#edit_hotel_category_name").focus();
                return false;
            }
            var edit_price_inr = $('#edit_price_inr').val();
            if (edit_price_inr == "") {
                $(".crop_wait_edit").hide();
                $(".crop_text_edit").show();
                $("span#edit_hotel_category_name_error").hide();
                $("span#edit_price_inr_error").html('This field is required!');
                $("input#edit_price_inr").focus();
                return false;
            }
            var edit_price_usd = $('#edit_price_usd').val();
            if (edit_price_usd == "") {
                $(".crop_wait_edit").hide();
                $(".crop_text_edit").show();
                $("span#price_inr_error").hide();
                $("span#edit_price_usd_error").html('This field is required!');
                $("input#edit_price_usd").focus();
                return false;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: '/package/hotelcategory/update/' + edit_id,
                data: $('#myForm').serialize(),
                success: function(data) {
                    $('#messages').html(
                        "<span class='sussecmsg'>Successfully updated!</span>");
                    location.reload();
                },
                errors: function() {
                    $(".crop_wait_edit").hide();
                    $(".crop_text_edit").show();
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(".deleteHotelCategory").click(function() {
        if (confirm("Are you sure you want to delete this Category?"))
            var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        if (id) {
            $.ajax({
                url: '/package/hotelcategory/delete/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    });
</script>
<script>
    $("li a").each(function() {
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
</script>
{{-- To get destination on basis of Hotel Category --}}
<script>
    $(document).ready(function() {
        $('#hotel_category_name').change(function(e) {
            var hotel_cat = $('#hotel_category_name').val();
            // alert(hotel_cat);
            $.ajax({
                type: "GET",
                url: "{{ route('destination-details', ['id' => $route_id]) }}",
                data: {
                    hotel_category: hotel_cat
                },
                success: function(data) {
                    console.log(data);
                    var options = '<option value="">Select Destination</option>';
                    if (data.destinations && data.destinations.length > 0) {
                        data.destinations.forEach(function(destination) {
                            options += '<option value="' + destination + '">' +
                                destination +
                                '</option>';
                        });
                    } else {
                        options += '<option value="">No destination exists</option>';
                    }
                    $('select#destination').html(options);
                },
                errors: function() {
                    console.log('Something went wrong');
                }
            });
        });
    });
</script>
{{-- To get hotel on basis of destination --}}
<script>
    $(document).ready(function() {
        $('#destination').change(function(e) {
            var dest_name = $('#destination').val();
            var hotel_category = $('#hotel_category_name').val();
            // alert(dest_name);
            $.ajax({
                type: "GET",
                url: "{{ route('hotel-details') }}",
                data: {
                    destination_name: dest_name,
                    hotel_category: hotel_category
                },
                success: function(data) {
                    console.log(data);
                    var options = '<option value="">Select Hotel</option>';
                    data.hotel.forEach(function(hotel) {
                        options += '<option value="' + hotel + '">' + hotel +
                            '</option>';
                    });
                    $('select#hotel').html(options);
                },
                errors: function() {
                    console.log('Something went wrong');
                }
            });
        });
    });
</script>
{{-- To get hotel on basis of destination while editing --}}
<script>
    $(document).ready(function() {
        $('#edit_destination').change(function(e) {
            var dest_name = $('#edit_destination').val();
            var hotel_category = $('#edit_hotel_category_name').val();
            // alert(dest_name);
            $.ajax({
                type: "GET",
                url: "{{ route('hotel-details') }}",
                data: {
                    destination_name: dest_name,
                    hotel_category: hotel_category
                },
                success: function(data) {
                    console.log(data);
                    var options = '<option value="">Select Hotel</option>';
                    data.hotel.forEach(function(hotel) {
                        options += '<option value="' + hotel + '">' + hotel +
                            '</option>';
                    });
                    $('select#edit_hotel').html(options);
                },
                errors: function() {
                    console.log('Something went wrong');
                }
            });
        });
    });
</script>
@endsection
