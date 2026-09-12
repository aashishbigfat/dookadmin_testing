<ul role="tablist" class="tabclass" style="margin-bottom: 30px;">
  @if(request()->route('id'))
    <li class="tab-departure">
      <a class="btn btn-primary not-active0 btns" href="{{route('group_packages_edit',request()->route('id'))}}">
        <span class="Dep_basic_details step">Basic Details</span>
      </a>
    </li>
    <!-- <li class="tab-departure">
      <a class="btn btn-primary not-active1 btns" href="{{route('group_packages_inclusion',request()->route('id'))}}">
        <span class="inclusions step">Inclusions</span>
      </a>
    </li> -->
   <li class="tab-departure">
      <a class="btn btn-primary not-active2 btns" href="{{route('group_packages_poi_create',request()->route('id'))}}">
        <span class="inclusions step">Point of Interests</span>
      </a>
    </li>
    <li class="tab-departure">
      <a class="btn btn-primary not-active04 btns" href="{{route('group_packages_activities',request()->route('id'))}}">
        <span class="inclusions step">Activities</span>
      </a>
    </li>
    <li class="tab-departure">
      <a class="btn btn-primary not-active3 btns" href="{{route('group_packages_itinerary_create',request()->route('id'))}}">
        <span class="itineraries step">Itineraries</span>
      </a>
    </li>
    <li class="tab-departure">
      <a class="btn btn-primary not-active4 btns" href="{{route('group_term_conditions',request()->route('id'))}}">
        <span class="term&condition step">Terms & Conditions</span>
      </a>
    </li>
    <li class="tab-departure">
      <a class="btn btn-primary not-active5 btns" href="{{route('group_visa_informations',request()->route('id'))}}">
        <span class="visainfo step">Visa Informations</span>
      </a>
    </li>
    <li class="tab-departure">
      <a class="btn btn-primary not-active7 btns depDates" href="{{route('group_dates',request()->route('id'))}}">
        <i class="fa fa-calendar" style="color:#d3e918"></i>
        <span class="inclusioninfo step">Group Tour Dates</span>
      </a>
    </li>
  @else
    <li class="tab-departure active">
      <a class="btn btn-primary not-active0 btns" href="{{route('group_packages_create')}}">
        <span class="Dep_basic_details step">Basic Details</span>
      </a>
    </li>
    <!-- <li class="tab-departure not-active1">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="inclusions step">Inclusions</span>
      </a>
    </li> -->
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="inclusions step">Point of Interests</span>
      </a>
    </li>
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="inclusions step">Activities</span>
      </a>
    </li>
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="itineraries step">Itineraries</span>
      </a>
    </li>
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="term&condition step">Terms & Conditions</span>
      </a>
    </li>
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <span class="visainfo step">Visa Informations</span>
      </a>
    </li>
    <li class="tab-departure not-active2">
      <a class="btn btn-primary rrmenu btns" href="#">
        <i class="fa fa-calendar" style="color:#d3e918"></i> <span class="itineraryinfo step">Group Tour Dates</span>
      </a>
    </li>
  @endif
</ul>

<style type="text/css">
  .rrmenu {
      cursor: no-drop !important;
  }
  .btn-primary.btns {
    background-color: #9a191e;
    border-color: #9a191e;
  }
  .btn-primary.active, .btn-primary:active, .open>.dropdown-toggle.btn-primary {
      color: #fff;
      background-color: #3c515d;
      border-color: #3c515d;
  }
  .box.box-primary {
    border-top-color: #9a191e !important;
    background: 0 0;
}
.steps.clearfix>ul>li {
    margin-right: 5px !important;
}
.btn-primary.btns {
    padding: 5px 8px;
}
  /*.wizard-progress{list-style:none;list-style-image:none;padding:0;white-space:nowrap}.wizard-progress li{float:left;text-align:center;position:relative}.wizard-progress .step-name{display:table-cell;height:32px;vertical-align:bottom;text-align:center;width:100%}.wizard-progress .step-num{font-size:14px;font-weight:700;border:3px solid #a9a9a9;background-color:#a9a9a9;border-radius:50%;width:24px;display:inline-block;margin-top:10px;color:#fff}.wizard-progress .step-num:after{content:"";display:block;background:#a9a9a9;height:1px;width:130%;position:absolute;bottom:45px}.wizard-progress li:last-of-type .step-num:after{display:none}.step-done .step-num::after{background-color:#a9a9a9}.wizard-progress .step-active .step-num,.wizard-progress .step-done .step-num{border-color:#a9a9a9;background-color:#a9a9a9}.wizard-progress .step-active .step-name{font-weight:700}.wizard-5-steps{width:750px}.wizard-5-steps li{margin-right:6.9%;width:13%}.wizard-5-steps .step-num:after{left:62%}.wizard-6-steps{width:750px}.wizard-6-steps li{float:left;margin-right:5.5%;width:11%}.wizard-6-steps .step-num:after{left:64%}a.step-num.active {background: green !important;border: 2px solid grey !important;}*/
</style>