    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Open+Sans:wght@500&display=swap');
        *, ::after, ::before{
            box-sizing: border-box;
            margin:0;padding:0;outline:0;border:0;line-height:1;
        }
        .fix_pdf_container,.pdf_container{font-family: 'Open Sans', sans-serif;}
        .fix_pdf_container h1,.pdf_container h2,.pdf_container h3{font-family: 'Dancing Script', cursive;margin:0;padding:0;line-height:1;}

        .design_1 .btm_strip{background: #b14e51;}
        .design_1 .btm_powered{background-color: #cc2127;}
        .design_1 .t_basic p strong{color: #b52818;}
        .design_1 .bg_heading,
        .day_banner .desc ul li:before{background-color: #cc2127;}
        
        .design_1 .day_banner .day_title,
        .design_1.daywise_detail .p_day{color: #cc2127;}

        .design_1 .day_banner .day_title strong{color: #282D31;}

        .design_1 .bg_heading{color: #fff;}


        .fix_pdf_container,.pdf_container{
            background: #fff;
            position: relative;
            margin: 0 auto;
            padding:0;
            width: 210mm;
            overflow: hidden;
        }
        .fix_pdf_container{
            height: 297mm;
        }
        .pdf_container{
            height:auto;
            padding-top: 24px;
        }
        .img{background-size: cover;background-position: center;pointer-events: none;object-fit: cover;}
        .banner_detail .banner_img{
            position: absolute;
            z-index: 1;
            top:0;
            left:0;
            height: 622px;
            width: 210mm;
        }
        .banner_detail .banner_bg{
            width: 210mm;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            pointer-events: none;
        }
        .btm_strip{
            height: 30px;
            width: 636px;
            position: absolute;
            z-index: 1;
            bottom:0;
            right: 0;
        }
        .banner_detail .company_name{
            position: absolute;
            z-index: 3;
            bottom: 50px;
            right: 80px;
            width: 370px;
            text-align: center;
        }
        .banner_detail .company_name img{
            max-width:100%;
            height: auto;
            margin:0 auto 12px;
        }
        .banner_detail h1{
            font-size: 52px;
            margin-bottom: 8px;
            color: #fff;
        }
        .banner_detail .company_contact{
            position: absolute;
            z-index: 3;
            bottom:100px;
            left:25px;
            width: 270px;
        }
        .banner_detail .company_contact td{vertical-align:top;}
        .banner_detail .company_contact .img img{width: 20px;}
        .banner_detail .company_contact a{font-size:14px;word-break: break-all;line-height: 1.4;margin-left: 6px;text-decoration: none;color: #000;display: block;}
        .banner_detail .company_contact .img,.banner_detail .company_contact a{margin-bottom: 6px;}
        .watermark{
            position: absolute;
            transform: rotate(-45deg);
            font-size: 60px;
            color: #ddd;
            font-weight: bold;
            z-index: 4;
            pointer-events: none;
            opacity: 0.3;
            top: 45%;
            left: 19%;
        }
        
        .design_1.header{width: 100%;height: 100%;position: fixed;top: 0;left: 0;right: 0;bottom: 0;z-index: 5;pointer-events: none;}
        .t_promotion_img td{text-align: center;}
        .t_promotion_img img{width:300px;margin: 0 auto;}
        
        .day_banner td{vertical-align: top;}

        .t_basic,.t_place,.a_t_poi,.t_inclusion{padding: 0 40px;width:100%;}
        .a_t_poi>tbody>tr>td{width:50%;}
        .a_t_poi.day_banner tr td{width:auto;}
        .a_t_poi.day_banner tr td:nth-child(even){width:100%;}

        .a_t_poi{padding: 0 40px;width:100%;}
        .a_t_poi>tbody>tr>td:nth-child(odd){padding-left: 40px;}
        .a_t_poi>tbody>tr>td:nth-child(even){padding-right: 40px;}
        .a_t_poi .top{vertical-align: top;}
        .a_t_poi .t_img{width: 50px;height: 50px;border-radius: 50%;overflow:hidden;margin-right: 15px;margin-bottom:7px;}
        .a_t_poi .t_title{font-size: 18px;line-height:1;margin-bottom:3px;}
        .a_t_poi .t_decs{font-size: 13px;color:#777;line-height:1.2;margin-bottom:7px;}
        .t_contact{padding:0;border-collapse: collapse;}
        .a_t_poi.t_contact tr td{padding:0 !important;}
        .t_contact td{vertical-align:bottom;}

        .basic_detail .h_title{margin: 0px 40px 22px;font-size:36px;}
        .t_basic .img img{width:22px;margin-right:8px;}
        .t_basic .img,.t_basic p{margin-bottom:6px;}
        .t_basic p{font-size:16px;line-height:1;}
        .t_basic p strong{font-size: 16px;}
        .bg_heading{position: relative;display: inline-block;padding: 12px 22px 12px 26px;margin:16px 0 9px;}
        .bg_heading h3{font-size: 24px;}
        .t_place p{width:348px;margin-bottom:5px;}
        .bg_heading:after{width: 0;content: "";position: absolute;height: 0;top: -0.5px;right: -60px;left: 100%;border-top: 48px solid #cc2127;border-right: 33px solid transparent;}

        .day_banner{padding:0 40px;margin-bottom:24px;}
        .day_banner .banner_img{width: 300px;height: 250px;margin-right: 10px;max-width: 300px;}
        .day_banner .day_title{font-size: 24px;line-height:1;margin-bottom: 5px;display:block;}
        .day_banner .day_title strong{font-size: 20px;}
        .day_banner .desc{font-size: 14px;line-height: 1.3;color: #777;}
        .day_banner .desc ul{list-style-type: none;}
        .day_banner .desc ul li{position:relative;margin-bottom: 5px;padding-left: 20px;}
        .day_banner .desc ul li:before{content: '';position:absolute;left: 7px;top: 5px;width: 5px;height: 5px;border-radius: 50%;}
        /* .daywise_detail{margin-top: 24px} */
        .daywise_detail .p_day{padding:0 40px;margin-bottom:12px;}

        .t_inclusion td{vertical-align:top;}
        .t_inclusion .t_icon{width:24px;margin-right:12px;}
        .t_inclusion p{width:300px;line-height: 1.6;color:#777;}

        .terms-conditions h2{margin: 0px 40px 18px;font-size:36px;}
        .terms-conditions .terms_desc{padding:0 40px;}
        .terms-conditions .terms_desc ol,.terms-conditions .terms_desc ul{list-style-type: none !important;margin-bottom: 16px;}
        .terms-conditions .terms_desc ol li,.terms-conditions .terms_desc ul li{font-size: 14px;line-height:1.4;color: #777;margin-bottom: 5px;position:relative;padding-left: 22px;}
        .terms-conditions .terms_desc ol li:before, .terms-conditions .terms_desc ul li:before{content: '';position: absolute;width: 5px;height: 5px;background-color: #5e5c5c;border-radius: 50%;left: 8px;top: 8px;}
        
        .f_pdf_btm{position: fixed;width: 100%;bottom: 0;height: 34px;margin: 0 auto;z-index: 9;}
        .f_pdf_btm .btm_powered{position: absolute;bottom: 0;right: 0;border-radius: 12px 0 0 0;line-height: 1;margin: 0;text-decoration: none !important;width:198px;height: 32px;}
        .f_pdf_btm .btm_powered>div{position:relative;}
        .f_pdf_btm .btm_powered>div>span{position: absolute;left:12px;top: 12px;color: #fff;font-size: 10px;line-height: 1;}
        .f_pdf_btm .btm_powered>div>strong{position:absolute;right:12px;top: 11px;font-size: 12px;color: #fff;line-height: 1;}
        .f_pdf_btm .btm_powered>div>img{position: absolute;left: 72px;top: 5px;width: 24px;margin: 0 3px 0 6px;}
        
        
        .travel_guide{
            width:397px;
        }
        .user_detail{
            width:396px;
        }
        .travel_guide{background: #861619;position:relative;color: #fff;padding: 25px;}
        .travel_guide:before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 336px solid #ed2028;
            border-top: 162px solid transparent;
        }
        .travel_guide:after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 99%;
            right: 0;
            left: 0;
            border-left: 400px solid #861619;
            border-top: 170px solid transparent;
        }
        .user_detail{background: #2f2f2f;position: relative;margin-left: auto;text-align: center;color: #fff;padding: 25px;padding-top: 0;margin-top: 172px;}
        .user_detail:after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 396px solid #2f2f2f;
            border-top: 170px solid transparent;
        }
        .user_detail .logo_user{max-width:100%;width:100px;margin: 0 auto 12px;}
        .user_detail h2{font-size:30px;}
        .user_detail .user_p{font-size:14px;margin-bottom:9px;line-height:1.3;}
        
        .box_break{page-break-inside: avoid;}
        
        </style>
    <script>
        html2pdf().set({
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        });
    </script>
    @include('dom-pdf-view-pages.banner')
    @include('dom-pdf-view-pages.basic_detail')
    @include('dom-pdf-view-pages.daywise')
    @include('dom-pdf-view-pages.inclusion')
    @include('dom-pdf-view-pages.terms-conditions')
    @include('dom-pdf-view-pages.contact')