<div class="tab-pane" id="kt_tabs_6_13" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('contact-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        <div class="form-group col-md-3">
                            <label>Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{$contact_data->company_name}}" placeholder="Enter company name..">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="{{$contact_data->name}}" class="form-control" placeholder="Enter name..">
                            @if(isset($contact_data->id))
                                <input type="hidden" name="contact_id" value="{{$contact_data->id}}">
                            @else
                                <input type="hidden" name="contact_id" value="">
                            @endif
                            <input type="hidden" name="departureid" value="{{request()->route('id')}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Contact No.</label>
                            <input type="text" id="phone" name="contact_no" value="{{$contact_data->contact_no}}" class="form-control" placeholder="Enter contact no.">
                        </div>
                        <div class="form-group col-md-3">
                            <label>WhatsApp No.</label>
                            <input type="text" id="mobile" name="whatsapp_no" value="{{$contact_data->whatsapp_no}}" class="form-control" placeholder="Enter WhatsApp No.">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Email</label>
                            <input type="email" id="email" name="email" value="{{$contact_data->email}}" class="form-control" placeholder="Enter email..">
                        </div>
                        <div class="form-group col-md-8">
                            <label>Address</label>
                            <textarea id="address" name="address" class="form-control" placeholder="Enter name..">{{$contact_data->address}}</textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Manager Name</label>
                            <input type="text" id="manager_name" name="manager_name" value="{{$contact_data->manager_name}}" class="form-control" placeholder="Enter manager name..">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Manager phone</label>
                            <input type="text" id="manager_phone" name="manager_phone" value="{{$contact_data->manager_phone}}" class="form-control" placeholder="Enter manager mobile..">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Manager email</label>
                            <input type="text" id="manager_email" name="manager_email" value="{{$contact_data->manager_email}}" class="form-control" placeholder="Enter manager email..">
                        </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info m-t-20" type="button"><i class="fas fa-save"></i>Update</button>
                            </div>
                    </form>
                </div>
            </div>

        </div>   

    </div>
</div>