<div class="design_1 fix_pdf_container banner_detail">
    <div class="img banner_img" style="background-image: url('https://adm.dookinternational.com/dook/images/package/{{$banner_data->banner_image}}');"></div>
    <img src="{{public_path('media/itinerary/cover_graphic.png')}}" alt="banner background" class="banner_bg">
    <div class="btm_strip"></div>
    <div class="company_name">
        <img src="https://www.dookinternational.com/images/logo.png" alt="company-logo">
        <h1>{{$banner_data->company_name}}</h1>
    </div>
    <table class="company_contact">
        @if($banner_data->contact_no != '')
        <tr>
            <td><div class="img"><img src="{{public_path('media/itinerary/phone.png')}}" alt="icon"></div></td>
            <td><a href="tel:{{$banner_data->contact_no}}">{{$banner_data->contact_no}}</a></td>
        </tr>
        @endif
        @if($banner_data->whatsapp_no != '')
        <tr>
            <td><div class="img"><img src="{{public_path('media/itinerary/whatsapp.png')}}" alt="icon"></div></td>
            <td><a href="https://api.whatsapp.com/send?phone={{$banner_data->whatsapp_no}}" target="_blank">{{$banner_data->whatsapp_no}}</a></td>
        </tr>
        @endif
        @if(isset($banner_data->email))
            @if($banner_data->email != '')
            <tr>
                <td><div class="img"><img src="{{public_path('media/itinerary/email.png')}}" alt="icon"></div></td>
                <td><a href="mailto:{{$banner_data->email}}">{{$banner_data->email}}</a></td>
            </tr>
            @endif
        @endif
        @if(isset($banner_data->website))
            @if($banner_data->website != '')
            <tr>
                <td><div class="img"><img src="{{public_path('media/itinerary/website.png')}}" alt="icon"></div></td>
                <td><a href="{{$banner_data->website}}">{{$banner_data->website}}</a></td>
            </tr>
            @endif
        @endif
    </table>
</div>