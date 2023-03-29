@extends('admin.master')
@section('content')

<section class="main-content">

    <div class="row">
        <div class="col-md-12">
            <div class='widget white-bg friends-group clearfix'>
                    <small class="text-muted">Customer Name </small>
                    <p>{{$record->customer->name}}</p>
                    <small class="text-muted">Property Title</small>
                    <p>{{$record->title}}</p>
                    <small class="text-muted">Property Image</small>
                    <p><img src="{{asset($record->image_url)}}" width="150px" height="100px"></p>
                    <small class="text-muted">Property Address</small>
                    <p>{{$record->address}}</p>
                    <small class="text-muted">Property City</small>
                    <p>{{$record->city}}</p>
                    <small class="text-muted">Property State</small>
                    <p>{{$record->state}}</p>
                    <small class="text-muted">Property Zip Code</small>
                    <p>{{$record->zipcode}}</p>
                    <small class="text-muted">Property MLS Link Url</small>
                    <p>{{$record->mls_detail}}</p>
                    <small class="text-muted">Asking Price</small>
                    <p>{{$record->asking_price}}</p>
                    <small class="text-muted">What's your "Sell by" date?</small>
                    <p>{{$record->sell_date}}</p>
                    <small class="text-muted">Cma Appointment</small>
                    <p>{{$record->cma_appointment}}</p>
                    <small class="text-muted">Property Type</small>
                    <p>{{$record->property_type}}</p>
            </div>
        </div>
    </div>

</section>
@endsection
