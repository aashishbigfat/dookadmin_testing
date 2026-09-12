<div class="design_1 fix_pdf_container contact_detail box_break">
    <h1 style="text-align:center;margin: 26px 40px 16px;">Contact Details</h1>
    <table class="a_t_poi t_contact">
        <tr>
            <td>
                <table class="travel_guide">
                    <tr>
                        <td>
                            @if($contact_data->manager_name != '' || $contact_data->manager_phone != '' || $contact_data->manager_email != '')
                            <div>
                                <img src="https://adm.dookinternational.com/media/itinerary/guide.png" style="width:40px;margin-right:10px;">
                            </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if($contact_data->manager_name != '')
                                <h1>Tour Manager</h1>
                                <p>{{$contact_data->manager_name}}</p>
                                @endif
                                @if($contact_data->manager_phone != '')
                                <h1>Tour Manager Phone</h1>
                                <p>{{$contact_data->manager_phone}}</p>
                                @endif
                                @if($contact_data->manager_email != '')
                                <h1>Tour Manager Email</h1>
                                <p>{{$contact_data->manager_email}}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <div class="user_detail">
                    <img src="https://www.dookinternational.com/assets/images/logo.png" alt="company-logo" class="logo_user">
                    <h2>{{$contact_data->company_name}}</h2>
                    <p style="border-bottom:1px solid #fff;width: 100px;margin:10px auto;"></p>
                    <p style="font-size:16px;margin-bottom:9px;">Company Contact</p>
                    <p class="user_p"><strong>Name:</strong> {{$contact_data->name}}</p>
                    <p class="user_p"><strong>Tel.:</strong> {{$contact_data->contact_no}}</p>
                    <p class="user_p"><strong>M.:</strong> {{$contact_data->whatsapp_no}}</p>
                    <p class="user_p"><strong>Email:</strong> {{$contact_data->email}}</p>
                    <p style="border-bottom:1px solid #fff;width: 100px;margin:10px auto;"></p>
                    <p class="user_p" style="margin-bottom:28px;">
                        <strong>Address</strong><br>
                        {{$contact_data->address}}
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>