<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar" style="height: auto;">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu tree" data-widget="tree">
      <?php 
            $current_route_name = Route::current()->getName();

            $dashboard = array('home');
            if(in_array($current_route_name, $dashboard)){
              $dashboard_class = "active";
            }
            else{
              $dashboard_class = "";
            }
            $all_modules=['departures','departure_edit','departure_inclusion','departure_poi_create','departure_itinerary_create','departure_term_conditions','departure_visa_informations','departure_activities','pull_index','agent_itinerary_index','agent_itinerary_edit','optional_activity','packages','packages_create','packages_edit','packages_inclusion','packages_poi_create','packages_itinerary_create','visa_informations','term_conditions','group_packages','group_packages_create','group_visa_informations','group_term_conditions','group_packages_edit','group_packages_inclusion','group_packages_poi_create','group_packages_itinerary_create','packages_activities','popular_packages','activity_index','destination_index','top_destinations_create','region_index','experience_index','countries_index','landing_pages','top_destinations_destinations_create','region_update','departure_in_focus','departure_in_recommended','top_destinations_destinations_create','landing_experience','banner_index','banner_create','popular_destination','landing_home','landing_home_slider','landing_country','about_index','point_of_interest_edit','landing_region','existingpoi_index','reviews','enquiries','job_manage','pdf-pages.create','pdf-pages.index','departure_date_inclusion','departure_dates','group_dates','group_date_inclusion','visa_destination','mega_country','mega_destination','searching_destinations','home_setting','home_edit','landing_pages','contact_index','create_signature'];
            
            $routes=explode(".",$current_route_name);
            $current_controller=$routes[0] ;
            $active_class='active';

            if(in_array($current_controller, $all_modules )){
              $main_link_class = "hasChild active open";
              $main_link_display_css = "display: block;";
              $main_link_display_menu_open_class = "menu-open;";
              $active = "active";
              $link_class = 'active';
            }else{
              $main_link_class = "";
              $main_link_display_css = "display: none;";
               $link_class = '';
            } 
      ?>
      <li class="header" style="color:#fbfbfb">MAIN NAVIGATION</li>
      <li class="{{$dashboard_class}}">
        <a href="{{ route('home') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="@if ($current_controller=='pull_index' ) echo active @endif">
        <a href="{{route('pull_index')}}" style="color: #d3f602;"><i class="fa fa fa-bullhorn"></i>Pull Departures</a>
      </li>
      <li
        class="@if ($current_controller=='packages' ) echo active @elseif($current_controller=='packages_create' ) echo active @elseif($current_controller=='packages_edit' ) echo active @elseif($current_controller=='packages_inclusion' ) echo active @elseif($current_controller=='packages_poi_create' ) echo active @elseif($current_controller=='packages_itinerary_create' ) echo active @elseif($current_controller=='term_conditions' ) echo active @elseif($current_controller=='visa_informations' ) echo active
        @elseif($current_controller=='packages_activities' ) echo active @elseif($current_controller=='pdf-pages.create' ) echo active @elseif($current_controller=='pdf-pages.index' ) echo active @endif">
        <a href="{{route('packages')}}"><i class="fa fa-gift"></i> Packages</a>
      </li>

      {{--<li class="@if ($current_controller=='group_packages' ) echo active @elseif($current_controller=='group_packages_create' ) echo active @elseif($current_controller=='group_packages_edit' ) echo active @elseif($current_controller=='group_packages_inclusion' ) echo active @elseif($current_controller=='group_packages_poi_create' ) echo active @elseif($current_controller=='group_packages_itinerary_create' ) echo active @elseif($current_controller=='group_term_conditions' ) echo active @elseif($current_controller=='group_visa_informations' ) echo active @elseif($current_controller=='group_packages_activities' ) echo active @elseif($current_controller=='group_date_inclusion' ) echo active
        @elseif($current_controller=='group_dates' ) echo active @endif"><a href="{{route('group_packages')}}"><i
            class="fa fa-snowflake-o"></i> Group Tours</a>
      </li>--}}
      <li class="@if ($current_controller=='departures' ) echo active @elseif($current_controller=='departure_edit' ) echo active @elseif($current_controller=='departure_inclusion' ) echo active @elseif($current_controller=='departure_poi_create' ) echo active @elseif($current_controller=='departure_itinerary_create' ) echo active @elseif($current_controller=='departure_term_conditions' ) echo active @elseif($current_controller=='departure_visa_informations' ) echo active
        @elseif($current_controller=='departure_activities' ) echo active @elseif($current_controller=='departure_date_inclusion' ) echo active
        @elseif($current_controller=='departure_dates' ) echo active @endif"><a href="{{route('departures')}}"><i
            class="fa fa-cloud"></i> Group Tours</a></li>

      <li
        class="@if ($current_controller=='home_setting' ) echo active @elseif($current_controller=='home_edit' ) echo active @endif">
        <a href="{{route('home_setting')}}"><i class="fa fa-cog"></i> Home Settings</a>
      </li>

      <li
        class="treeview @if ($current_controller=='landing_pages' || $current_controller=='landing_pages' || $current_controller=='about_index' || $current_controller=='contact_index')menu-open @endif">
        <a href="#">
          <i class="fa fa-plane"></i>
          <span>Landing Pages</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul
          class="landings treeview-menu @if ($current_controller=='landing_pages' || $current_controller=='about_index' || $current_controller=='contact_index')topDestBlock @endif">
          <li class="@if ($current_controller=='landing_pages') echo active @endif">
            <a href="{{route('landing_pages')}}"><i class="fa fa-map-marker"></i>Pages</a>
          </li>
          <li class="@if ($current_controller=='about_index')active @endif">
            <a href="{{route('about_index')}}"><i class="fa fa-map-marker"></i>About Us</a>
          </li>
          <li class="@if ($current_controller=='contact_index')active @endif">
            <a href="{{route('contact_index')}}"><i class="fa fa-map-marker"></i>Contact Us</a>
          </li>
        </ul>
      </li>
      <li class="@if ($current_controller=='top_destinations_destinations_create' ) echo active @endif">
        <a href="{{route('top_destinations_destinations_create')}}"><i class="fa fa-map-marker"></i>Top Destinations</a>
      </li>
      <li class="@if ($current_controller == 'hotel-create') echo active @endif">
        <a href="{{ route('hotel-create') }}"><i class="fa fa-hotel"></i>Add Hotel</a>
      </li>
      <li
        class="@if ($current_controller=='region_index' ) echo active @elseif($current_controller=='region_update') echo active  @endif">
        <a href="{{route('region_index')}}"><i class="fa fa-map-marker"></i>Regions</a>
      </li>

      <li
        class="treeview @if ($current_controller=='countries_index' || $current_controller=='mega_country') echo menu-open @endif">
        <a href="#">
          <i class="fa fa-plane"></i>
          <span>Countries</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul
          class="treeview-menu @if ($current_controller=='countries_index' || $current_controller=='mega_country') echo topDestBlock @endif">
          <li class="@if ($current_controller=='countries_index' ) echo active @endif">
            <a href="{{route('countries_index')}}"><i class="fa fa-map-marker"></i>Countries</a>
          </li>
          <li class="@if ($current_controller=='mega_country' ) echo active @endif">
            <a href="{{route('mega_country')}}"><i class="fa fa-map-marker"></i>Mega Menu Countries</a>
          </li>
        </ul>
      </li>



      <li
        class="treeview @if ($current_controller=='destination_index' || $current_controller=='mega_destination') echo menu-open @endif">
        <a href="#">
          <i class="fa fa-plane"></i>
          <span>Destinations</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul
          class="treeview-menu @if ($current_controller=='destination_index' || $current_controller=='mega_destination') echo topDestBlock @endif">
          <li class="@if ($current_controller=='destination_index' ) echo active @endif">
            <a href="{{route('destination_index')}}"><i class="fa fa-map-marker"></i>Destinations</a>
          </li>
          <li class="@if ($current_controller=='mega_destination' ) echo active @endif">
            <a href="{{route('mega_destination')}}"><i class="fa fa-map-marker"></i>Mega Menu Destinations</a>
          </li>
        </ul>
      </li>

      <li class="@if ($current_controller=='experience_index' ) echo active @endif"><a
          href="{{route('experience_index')}}"><i class="fa fa-trophy"></i>Experiences</a></li>

      <li class="@if ($current_controller=='activity_index' ) echo active @endif"><a
          href="{{route('activity_index')}}"><i class="fa fa-trophy"></i>Activities</a></li>

      <li
        class="@if ($current_controller=='banner_index' ) echo active @elseif($current_controller=='banner_create') echo active  @endif">
        <a href="{{route('banner_index')}}"><i class="fa fa-map-marker"></i>Banners</a>
      </li>

      <!-- <li class="@if ($current_controller=='about_index' ) echo active @elseif($current_controller=='about_index') echo active  @endif"><a href="{{route('about_index')}}"><i class="fa fa-map-marker"></i>About Us</a></li> -->

      <li class="@if ($current_controller=='point_of_interest_edit' ) echo active @endif"><a
          href="{{route('point_of_interest_edit')}}"><i class="fa fa-map-marker"></i>POI Edit</a></li>
          <li class="@if ($current_controller=='visa' ) echo active @endif"><a
          href="/visa"><i class="fa fa-map-marker"></i>Visa</a></li>
      <li class="@if ($current_controller=='existingpoi_index' ) echo active @endif"><a
          href="{{route('existingpoi_index')}}"><i class="fa fa-map-marker"></i>Existing Remaining POIs</a></li>
      <li class="treeview @if ($current_controller=='popular_destination') echo menu-open @endif">
        <a href="#">
          <i class="fa fa-plane"></i>
          <span>Footer</span>
          <!-- <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span> -->
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu @if ($current_controller=='popular_destination') echo topDestBlock @endif">
          <li class="@if ($current_controller=='popular_destination' )active @endif">
            <a href="{{route('popular_destination')}}"><i class="fa fa-map-marker"></i>Popular Desttinations</a>
          </li>
          <li class="@if ($current_controller=='footer_create' )active @endif">
            <a href="{{route('footer_create')}}"><i class="fa fa-map-marker"></i>Footer Create</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if ($current_controller=='reviews') echo menu-open @endif">
        <a href="#">
          <i class="fa fa-plane"></i>
          <span>Others</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu @if ($current_controller=='reviews') echo topDestBlock @endif">
          <li class="@if ($current_controller=='searching_destinations' ) echo active @endif">
            <a href="{{route('searching_destinations')}}"><i class="fa fa-question-circle" aria-hidden="true"></i> Dook
              Searching Destination</a>
          </li>
          <li class="@if ($current_controller=='visa_destination' ) echo active @endif">
            <a href="{{route('visa_destination')}}"><i class="fa fa-star"></i>Visa Destinations</a>
          </li>
          <li class="@if ($current_controller=='reviews' ) echo active @endif">
            <a href="{{route('reviews')}}"><i class="fa fa-star"></i>Reviews</a>
          </li>
          <li class="@if ($current_controller=='job_manage' ) echo active @endif">
            <a href="{{route('job_manage')}}"><i class="fa fa-tasks" aria-hidden="true"></i>Job Post</a>
          </li>
          <li class="@if ($current_controller=='enquiries' ) echo active @endif">
            <a href="{{route('enquiries')}}"><i class="fa fa-question-circle" aria-hidden="true"></i> Dook Enquiries</a>
          </li>
           <li class="@if ($current_controller=='enquiry' ) echo active @endif">
            <a href="{{route('enquiry')}}"><i class="fa fa-question-circle" aria-hidden="true"></i> Dook Report</a>
          </li>
          <li class="@if ($current_controller=='create_signature' ) echo active @endif">
            <a href="{{route('signature_upload')}}"><i class="fa fa-upload"></i>Signature</a>
          </li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<style>
  .topDestBlock {
    display: block;
  }

  .landings {
    padding-left: 20px;
  }

  .skin-blue .sidebar-menu>li>.treeview-menu {
    background: #1d323a;
    padding: 5px 0px 5px 20px;
  }
</style>