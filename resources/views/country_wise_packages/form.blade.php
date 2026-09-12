@php $package = $package ?? null; @endphp
<div class="col-md-6 col-lg-6 col-xl-6">
 <div class="form-group">
    <label for="country_id">Country</label>
    <select name="country_id" class="form-control" id="country_id" required>
        <option value="">Select Country</option>
        @foreach($countries as $id => $name)
            <option value="{{ $id }}" {{ (old('country_id', $package->country_id ?? '') == $id) ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
</div>
<div class="col-md-6 col-lg-6 col-xl-6">
 <div class="form-group">
    <label for="destination_id">From Destination </label>
    <select name="destination_id" class="form-control" id="destination_id" required>
        <option value="">Select Destination</option>
        @foreach($destination as $id => $name)
            <option value="{{ $id }}" {{ (old('destination_id', $package->destination_id ?? '') == $id) ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
</div>

<div class="col-md-6 col-lg-6 col-xl-6">
    <div class="form-group">
      <label>Name Tag</label>
      <input type="text" class="form-control" name="name" value="{{ old('name', $package->name ?? '') }}" required>
    </div>
</div>


<div class="col-md-6 col-lg-6 col-xl-6">
    <div class="form-group">
    <label>Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $package->slug ?? '') }}" required>
    </div>
</div>

<div class="col-md-6 col-lg-6 col-xl-6">
      <div class="form-group">
    <label>Header Title</label>
    <input type="text" name="header_title" class="form-control" value="{{ old('header_title', $package->header_title ?? '') }}">
</div>
</div>

<div class="col-md-12 col-lg-12 col-xl-12">
      <div class="form-group">
      <label>Header Sub Title</label>
     <textarea class="form-control" name="header_sub_title" id="header_sub_title">{{ old('header_sub_title', $package->header_sub_title ?? '') }}</textarea>
</div>

</div>

<div class="col-md-12 col-lg-12 col-xl-12">
      <div class="form-group">
    <label>Description</label>
    <textarea class="form-control" name="description" id="description">{!! old('description', $package->description ?? '') !!}</textarea>
</div>
</div>

<div class="col-md-6 col-lg-6 col-xl-6">
      <div class="form-group">
    <label>Featured Image (W:1024, H:768)</label>
    <input type="file" name="featured_image" class="form-control">
    @if(!empty($package->featured_image))
        <img src="{{ generateSignedUrl($package->featured_image) }}" width="120" class="mt-2">
    @endif
</div>
</div>

<div class="col-md-6 col-lg-6 col-xl-6">
      <div class="form-group">
        <label>Banner Image (W:1920, H:760)</label>
        <input type="file" name="banner_image" class="form-control">
        @if(!empty($package->banner_image))
            <img src="{{generateSignedUrl($package->banner_image) }}" width="120" class="mt-2">
        @endif
    </div>
</div>
<div class="col-md-6 col-lg-6 col-xl-6">
  <div class="form-group">
    <label for="departure_id">Package Select</label> 
@php
    $selectedDepartureIds = old('departure_ids');

    if (!$selectedDepartureIds && isset($package)) {
        $selectedDepartureIds = explode(',', $package->departure_ids ?? '');
    }
@endphp

<select name="departure_ids[]" id="departure_ids" class="form-control select2" multiple>
    @foreach($departureOptions as $dep)
        <option value="{{ $dep->id }}"
            {{ in_array($dep->id, $selectedDepartureIds ?? []) ? 'selected' : '' }}>
            {{ $dep->dep_dook_ref_id }} ({{ $dep->title }})
        </option>
    @endforeach
</select>


  </div>
</div>


  <div class="box-body">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <h3>Meta Informations</h3>
    <hr style="border-bottom: 2px solid #777">
  </div>  
  
  <div class="col-md-4 col-lg-4 col-sm-12">
    <div class="form-group">
      <label>Meta Title</label> 
      <textarea class="form-control" name="meta_title" id="meta_title"> {{ old('meta_title', $package->meta_title ?? '') }}</textarea>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-sm-12">
    <div class="form-group">
      <label>Meta Keywords</label>
      <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{ old('meta_keywords', $package->meta_keywords ?? '') }}</textarea>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-sm-12">
    <div class="form-group">
      <label>Meta Description</label>
      <textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description', $package->meta_description ?? '') }}</textarea>
    </div>
  </div>
</div>
