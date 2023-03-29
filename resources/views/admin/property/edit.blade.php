@extends('admin.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Edit Property
                    </div>
                    <div class="card-body">
                        {{-- {{ route('admin-property.update',['route_name' => $record->slug]) }} --}}
                        <form method="post" action="{{ route('admin-property.update',['admin_property' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" id="current_customer_id" name="current_customer_id" value="{{$record->customer_id}}">
                            <input type="hidden" id="current_login_user_id" value="{{Auth::guard('cms_user')->user()->cms_role_id}}">
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Agent Name</label>
                                        <input  type="text" value="{{$record->agent->name}}" class="form-control" readonly>
                                    </div>
                                </div>  
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input  type="text" value="{{$record->customer->name}}" class="form-control" readonly>
                                       
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input required type="text" name="title" value="{{$record->title}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" value="" name="image_url" class="form-control">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea type="text" name="address">{{$record->address}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input required type="text" value="{{$record->city}}" name="city" class="form-control">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input required type="text" name="state" value="{{$record->state}}" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MLS Link Url</label>
                                        <input required type="text" name="mls_detail" value="{{$record->mls_detail}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asking Price</label>
                                        <input required type="number" value="{{$record->asking_price}}" name="asking_price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>What's your "Sell by" date?</label>
                                        <input required type="date"  value="{{$record->sell_date}}"  name="sell_date" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cma Appointment</label>
                                        <input required type="text"  value="{{$record->cma_appointment}}" name="cma_appointment" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Property Type</label>
                                        <select name="property_type" class="form-control">
                                            <option required value="">-- Property Type --</option>
                                            <option value="Single Family (SF)" {{$record->property_type == "Single Family (SF)"  ? 'selected' : ''}}>Single Family (SF)</option>
                                            <option value="Condo (C)" {{$record->property_type == "Condo (C)"  ? 'selected' : ''}}>Condo (C)</option>
                                            <option value="Town House (TH)" {{$record->property_type == "Town House (TH)"  ? 'selected' : ''}}>Town House (TH)</option>
                                            <option value="Farm (F)" {{$record->property_type == "Farm (F)"  ? 'selected' : ''}}>Farm (F)</option>
                                            <option value="Land (L)" {{$record->property_type == "Land (L)"  ? 'selected' : ''}}>Land (L)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip Code</label>
                                        <input required type="text" value="{{$record->zipcode}}" name="zipcode" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.footer')
    </section>

@endsection
