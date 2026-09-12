<div class="box-body">
            <table id="reviewListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 2%">#</th>
                    <th style="width: 8%;">Name</th>
                    <th style="width: 1%;">Email</th>
                    <th style="width: 1%;">Mobile</th>
                    <th style="width: 1%;">Rating</th>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 4%;">Status</th>
                    <th style="width: 1%;text-align: center;">Action</th>
                  </tr>
                @if(count($reviews)> 0 )
                  @foreach( $reviews as $key => $review )
                    <tr>
                      <td>{{ ($reviews->currentpage()-1) * $reviews->perpage() + $key + 1 }}</td>
                      <td>{{$review->name}}</td>
                      <td>{{$review->email}}</td>
                      <td>{{$review->mobile}}</td>
                      <td>{{$review->rating}}</td>
                      <td style="text-align:justify">{{$review->description}}</td>
                      <td>
                        @if($review->active_status == '1')
                            <a class="disableReview" data-id="{{ $review->id }}" data-status="{{ $review->active_status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                        @else
                          <a class="disableReview" data-id="{{ $review->id }}" data-status="{{ $review->active_status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                        @endif
                      </td>
                      <td>
                        <a class="deleteReview" data-id="{{ $review->id }}" data-status="{{ $review->active_status }}" title="Delete">
                          <i class="fa fa-trash" style="cursor: pointer; color: #F9423C;"></i>
                        </a>
                    </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $reviews->links() }}
</div>

<script type="text/javascript">
  $(".disableReview").click(function () {
    var id = $(this).data("id");
    var status = $(this).data("status");
    var flag = status?'inactive':'active';
    var token = $("meta[name='csrf-token']").attr("content");
    if (confirm("Are you sure you want to "+flag+" this Review?"))
    $.ajax(
    {
      url: '/review-disable/' + id,
      type: 'POST',
      data: {
          "id": id,
          "_token": token,
      },
      success: function (data) {
        window.location.reload();
      }
    });
  });

  // Review Delete
  $(".deleteReview").click(function () {
    var id = $(this).data("id");
    var status = $(this).data("status");
    var token = $("meta[name='csrf-token']").attr("content");
    if (confirm("Are you sure you want to delete this Review?"))
    $.ajax(
    {
      url: '/review-delete/' + id,
      type: 'POST',
      data: {
          "id": id,
          "_token": token,
      },
      success: function (data) {
        window.location.reload();
      }
    });
  });
</script>