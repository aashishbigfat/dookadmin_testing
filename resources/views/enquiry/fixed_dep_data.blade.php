<div class="container">
<form action="{{ route('enquiry.report') }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-md-6">
    <div class="form-group">
      <label for="from_date">From Date:</label>
      <input type="date" class="form-control" id="from_date" name="from_date" required>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="to_date">To Date:</label>
      <input type="date" class="form-control" id="to_date" name="to_date" required>
    </div>
  </div>
  </div>
  <button type="submit" class="btn btn-primary">Generate Report</button>
</form>
</div>

@if(isset($destinationData))
<div class="container mt-5">
  <h3>Enquiry Report from {{ $from_date }} to {{ $to_date }}</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Destination</th>
        <th>Enquiry Count</th>
        <th>Lead Count</th>
        <th>Duplicate Count</th>
        <th>Enquiry-Lead Difference</th>
      </tr>
    </thead>
    <tbody>
      @foreach($destinationData as $data)
      <tr>
        <td>{{ $data['destination'] }}</td>
        <td>{{ $data['enquiryCount'] }}</td>
        <td>{{ $data['leadCount'] }}</td>
        <td>{{ $data['duplicateCount'] }}</td>
        <td>{{ $data['leadDuplicateDifference'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</div>
@endif