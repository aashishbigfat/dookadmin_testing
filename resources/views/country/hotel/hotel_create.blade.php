@extends('layouts.apps')
@section('headSection')
@section('title', 'Packages | Hotel')
<link rel="stylesheet" href="{{ asset('css/customCSS/departure_pointofinterest.css') }}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Hotel</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Hotel</a></li>
            <li class="active">Hotel Create</li>
        </ol>
    </section>
    <section class="content" id="head_border">
        <div class="row">
            <form role="form" id="hotel_create">
                @csrf
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Destination</label> <span class="validationError" id="city_error"></span>
                            <input type="text" list="destinations" class="form-control" name="city" id="city"
                                placeholder="Search Destination" value="">
                            <datalist id="destinations">
                                @forelse ($destinations as $destination)
                                    <option value="{{ $destination->dest_name }}">
                                    @empty
                                    <option value="No destination found">
                                @endforelse
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Country</label> <span class="validationError" id="country_error"></span>
                            <input type="text" class="form-control" name="country" placeholder="Country Name"
                                id="country" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Region/State</label> <span class="validationError" id="state_error"></span>
                            <select class="form-control" name="state" id="state">
                                <option value="">Select State</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Zip</label> <span class="validationError" id="zip_error"></span>
                            <input type="text" class="form-control" name="zip" id="zip" placeholder="Zip"
                                value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Hotel Category</label> <span class="validationError" id="hotel_cat_error"></span>
                            <select class="form-control" name="hotel_category" id="hotel_category">
                                <option value="">Hotel Category</option>
                                <option value="5">5 Star</option>
                                <option value="4">4 Star</option>
                                <option value="3">3 Star</option>
                                <option value="2">2 Star</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Hotel Name</label> <span class="validationError" id="hotel_name_error"></span>
                            <input type="text" class="form-control" name="hotel_name" id="hotel_name"
                                placeholder="Hotel Name" value="">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                        <button class="btn btn-primary active" type="button" id="store_form">
                            <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                            <span class="crop_wait" style="display: none">
                                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                            </span>
                        </button>
                        <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<style type="text/css">
    table.loading>tbody {
        position: relative
    }

    table.loading>tbody:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, .1);
        background-image: url("{{ asset('images/loaders.gif') }}");
        background-position: center;
        background-repeat: no-repeat;
        background-size: 65px 65px;
        content: ""
    }

    #map {
        height: 180px
    }

    #searchInput {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 100%;
        height: 35px;
        margin-top: 0px;
        margin-left: 0px;
    }

    ul.dropdown-menu.inner {
        height: 200px
    }

    .dropdown-menu.open.show {
        height: 226px
    }

    .sussecmsg {
        font-size: 16px;
        padding-left: 10px;
        color: green
    }

    .error {
        color: red
    }

    .pac-container {
        z-index: 999999;
    }

    button.gm-control-active.gm-fullscreen-control {
        display: none;
    }

    #hotel_create input,
    #hotel_create select {
        border-radius: 8px;
    }

    #head_border {
        margin-top: 10px;
        border-top: 2px solid #9a191e;
    }
</style>
@endsection

@section('footerSection')
<script type="text/javascript">
    $(document).ready(function() {
        $('#store_form').click(function(e) {
            e.preventDefault();
            $(".crop_wait").show();
            $(".crop_text").hide();
            var city = $('#city').val();
            if (city == "") {
                $("span#city_error").html('This field is required!');
                $("input#city").focus();
                return false;
            }
            var country = $('#country').val();
            if (country == "") {
                $("span#city_error").hide();
                $("span#country_error").html('This field is required!');
                $("input#country").focus();
                return false;
            }
            var state = $('#state').val();
            if (state == "") {
                $("span#country_error").hide();
                $("span#city_error").hide();
                $("span#state_error").html('This field is required!');
                $("input#state").focus();
                return false;
            }
            var hotel_cat = $('#hotel_category').val();
            if (hotel_cat == "") {
                $("span#country_error").hide();
                $("span#city_error").hide();
                $("span#state_error").hide();
                $("span#hotel_cat_error").html('This field is required!');
                $("select#hotel_category").focus();
                return false;
            }
            var hotel_name = $('#hotel_name').val();
            if (hotel_name == "") {
                $("span#country_error").hide();
                $("span#city_error").hide();
                $("span#state_error").hide();
                $("span#hotel_cat_error").hide();
                $("span#hotel_name_error").html('This field is required!');
                $("input#hotel_name").focus();
                return false;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('hotel-store') }}",
                data: $('#hotel_create').serialize(),
                success: function(data) {
                    console.log(data);
                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                    window.location = data.url;
                },
                errors: function() {
                    console.log('Something went wrong');
                }

            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#city').change(function(e) {
            var dest_name = $('#city').val();
            //    alert(dest_id);
            $.ajax({
                type: "GET",
                url: "{{ route('hotel-dest-country') }}",
                data: {
                    destination_name: dest_name
                },
                success: function(data) {
                    console.log(data);
                    $("input#country").val(data.country);
                    var options = '<option value="">Select State</option>';
                    data.states.forEach(function(state) {
                        options += '<option value="' + state + '">' + state +
                            '</option>';
                    });
                    $('select#state').html(options);
                },
                errors: function() {
                    console.log('Something went wrong');
                }
            });
        });
    });
</script>
@endsection
