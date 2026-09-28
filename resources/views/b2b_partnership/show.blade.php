@extends('layouts.apps')
@section('headSection')
@section('title', 'B2B Enquiry #' . $enquiry->id)

@endsection
@section('main-content')
@php
  $backUrl = \Illuminate\Support\Str::startsWith(url()->previous(), route('b2b_partnerships')) ? url()->previous() : route('b2b_partnerships');
  $pageUrlIsLink = preg_match('#^https?://#i', (string) $enquiry->page_url);
@endphp
<div class="content-wrapper">
    <section class="content-header">
      <h1>B2B Enquiry #{{ $enquiry->id }} <small>Received {{ $enquiry->receivedAt()->format('d M Y, h:i A') }} IST</small></h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('b2b_partnerships') }}">B2B Partnerships</a></li>
        <li class="active">#{{ $enquiry->id }}</li>
      </ol>
    </section>
    <section class="content b2b-enquiry">
      <p>
        <a class="btn btn-default" href="{{ $backUrl }}"><i class="fa fa-arrow-left"></i> Back to enquiries</a>
        <a class="btn btn-primary" href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Your B2B enquiry with Dook International') }}"><i class="fa fa-envelope"></i> Reply by email</a>
        @if(strlen($enquiry->whatsappNumber()) >= 7)
        <a class="btn btn-success" href="https://wa.me/{{ $enquiry->whatsappNumber() }}" target="_blank" rel="noopener"><i class="fa fa-whatsapp"></i> WhatsApp</a>
        @endif
      </p>
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Agency &amp; contact</h3></div>
            <div class="box-body">
              <dl class="dl-horizontal">
                <dt>Name</dt><dd>{{ $enquiry->name }}</dd>
                <dt>Agency / company</dt><dd>{{ $enquiry->company_name }}</dd>
                <dt>Business email</dt><dd><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd>
                <dt>Phone / WhatsApp</dt><dd><a href="tel:{{ $enquiry->mobile }}">{{ $enquiry->mobile }}</a></dd>
                <dt>Agency country</dt><dd>{{ $enquiry->agency_country }}</dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Programme</h3></div>
            <div class="box-body">
              <dl class="dl-horizontal">
                <dt>Programme type</dt><dd>{{ $enquiry->travel_type ?: 'Not specified' }}</dd>
                <dt>Destinations</dt>
                <dd>
                  @foreach((array) $enquiry->destinations as $destination)
                  <span class="label label-primary">{{ \App\B2BPartnershipEnquiry::destinationLabel($destination) }}</span>
                  @endforeach
                </dd>
                <dt>Travel month</dt><dd>{{ $enquiry->travel_month ? $enquiry->travel_month->format('F Y') : 'Not specified' }}</dd>
                <dt>Travellers</dt><dd>{{ $enquiry->no_of_travellers ?: 'Not specified' }}</dd>
                <dt>Hotel category</dt><dd>{{ $enquiry->hotel_category ?: 'Please advise' }}</dd>
                <dt>Budget range</dt><dd>{{ $enquiry->budget_range ?: 'Not specified' }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Brief</h3></div>
            <div class="box-body">
              @if($enquiry->comment)
              <div class="b2b-brief">{{ $enquiry->comment }}</div>
              @else
              <p class="text-muted">No brief was added.</p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Source</h3></div>
            <div class="box-body">
              <dl class="dl-horizontal">
                <dt>Received</dt><dd>{{ $enquiry->receivedAt()->format('d M Y, h:i A') }} IST</dd>
                <dt>Contact consent</dt><dd>{{ $enquiry->contact_consent_at ? 'Given ' . $enquiry->contact_consent_at->copy()->timezone(\App\B2BPartnershipEnquiry::DISPLAY_TIMEZONE)->format('d M Y, h:i A') . ' IST' : 'Not recorded' }}</dd>
                <dt>Page</dt>
                <dd class="b2b-break">
                  @if($pageUrlIsLink)
                  <a href="{{ $enquiry->page_url }}" target="_blank" rel="noopener noreferrer">{{ $enquiry->page_url }}</a>
                  @else
                  {{ $enquiry->page_url ?: '—' }}
                  @endif
                </dd>
                <dt>UTM source</dt><dd>{{ $enquiry->utm_source ?: '—' }}</dd>
                <dt>UTM medium</dt><dd>{{ $enquiry->utm_medium ?: '—' }}</dd>
                <dt>UTM campaign</dt><dd>{{ $enquiry->utm_campaign ?: '—' }}</dd>
                <dt>IP address</dt><dd>{{ $enquiry->ip_address ?: '—' }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<style>
  .b2b-enquiry .dl-horizontal dt {
    width: 140px;
  }

  .b2b-enquiry .dl-horizontal dd {
    margin-left: 160px;
    margin-bottom: 6px;
  }

  .b2b-enquiry .label {
    display: inline-block;
    margin: 0 2px 3px 0;
  }

  .b2b-enquiry .b2b-brief {
    white-space: pre-wrap;
    word-break: break-word;
  }

  .b2b-enquiry .b2b-break {
    word-break: break-all;
  }

  @media (max-width: 767px) {
    .b2b-enquiry .dl-horizontal dd {
      margin-left: 0;
    }
  }
</style>
@endsection
