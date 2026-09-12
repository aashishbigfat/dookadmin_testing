<div class="box-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>#</th>
                <th>Hotel Category</th>
                <th>Price INR</th>
                <th>Price USD</th>
                <th>Destination</th>
                <th>Hotel Name</th>
                <th>Action</th>
            </tr>
            @if (count($hotel_categories) > 0)
                @foreach ($hotel_categories as $hotel_category)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $hotel_category->hotel_category_name }} Star</td>
                        <td>{{ $hotel_category->price_inr }}</td>
                        <td>{{ $hotel_category->price_usd }}</td>
                        <td>{{ $hotel_category->destination }}</td>
                        <td>{{ $hotel_category->hotel_name }}</td>
                        <td>
                            <a class="edit-item" data-toggle="modal" data-id="{{ $hotel_category->id }}"
                                data-hotel="{{ $hotel_category->hotel_category_name }}"
                                data-inr="{{ $hotel_category->price_inr }}" data-usd="{{ $hotel_category->price_usd }}" data-dest="{{ $hotel_category->destination }}"
                                data-hotel_name="{{ $hotel_category->hotel_name }}"
                                title="Edit Hotel Category" style="cursor: pointer;margin-right: 5px;">
                                <i class="fa fa-edit"></i>
                            </a> | <a class="deleteHotelCategory" data-id="{{ $hotel_category->id }}"
                                style="cursor: pointer; color: #dc0b0b;margin-left: 5px;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
