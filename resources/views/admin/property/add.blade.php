@extends('admin.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Add Property
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('admin-property.store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @if(Auth::guard('cms_user')->user()->cms_role_id == 3)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Agent Name</label>
                                            <select name="agent_id" id="agent_id" class="form-control" onchange="changeHandler(event)"> 
                                                <option required value="">-- Select Agent --</option>
                                                @if( count($agents) )
                                                    @foreach($agents as $agent)
                                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <select id="customer_id" name="customer_id" class="form-control"> 
                                            <option required value="">-- Select Customer --</option>
                                            @if(Auth::guard('cms_user')->user()->cms_role_id !== 3)
                                                @if( count($leads) )
                                                    @foreach($leads as $lead)
                                                        <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                                                    @endforeach
                                                @endif                                            
                                            @endif
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input required type="text" name="title" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input required type="file" value="" name="image_url" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea type="text" name="address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input required type="text" value="" name="city" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input required type="text" name="state" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip Code</label>
                                        <input required type="text" value="" name="zipcode" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MLS Link Url</label>
                                        <input required type="text" name="mls_detail" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asking Price</label>
                                        <input required type="number" value="" name="asking_price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>What's your "Sell by" date?</label>
                                        <input required type="date" name="sell_date" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cma Appointment</label>
                                        <input required type="text" value="" name="cma_appointment" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Property Type</label>
                                        <select name="property_type" class="form-control">
                                            <option required value="">-- Property Type --</option>
                                            <option value="Single Family (SF)">Single Family (SF)</option>
                                            <option value="Condo (C)" >Condo (C)</option>
                                            <option value="Town House (TH)">Town House (TH)</option>
                                            <option value="Farm (F)">Farm (F)</option>
                                            <option value="Land (L)">Land (L)</option>
                                        </select>
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


@push('scripts')
    <script>
        function changeHandler(evt)  {
            let agentId = $("#agent_id").val(); 
            let request_url = window.location.origin + '/admin/admin-property/lead/list/' + agentId;
                $.ajax({
                    type:'GET',
                    url:request_url,
                    success:function(response){
                        let option_html = '';    
                            if (response.data.length == 0) {
                                option_html += `<option value="">No lead found</option></option>`;
                            }else{
                                for (let i = 0; i < response.data.length; i++) {
                                    option_html += `<option ${response.data[i].id}  value="${response.data[i].id}">${response.data[i].name}</option>`;
                                }
                            }
                            $('#customer_id').html(option_html);

                    }
                })
          }
    </script>
@endpush


@endsection
