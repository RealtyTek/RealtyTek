@extends('admin.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Application Setting
                    </div>
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Application Name</label>
                                <input type="text" value="{{  CustomHelper::appSetting('application_setting','application_name') }}" name="application_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" name="logo" class="form-control">
                                <input type="hidden" name="old_logo" value="{{ CustomHelper::appSetting('application_setting','logo') }}">
                                @if( !empty(CustomHelper::appSetting('application_setting','logo')) )
                                    <img style="width: 150px; height: 80px; object-fit: contain" src="{{ URL::to(CustomHelper::appSetting('application_setting','logo')) }}">
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Favicon</label>
                                <input type="file" name="favicon" class="form-control">
                                <input type="hidden" name="old_favicon" value="{{ CustomHelper::appSetting('application_setting','favicon') }}">
                                @if( !empty(CustomHelper::appSetting('application_setting','favicon')) )
                                    <img style="width:60px; height: 60px; object-fit: contain" src="{{ URL::to(CustomHelper::appSetting('application_setting','favicon')) }}">
                                @endif
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
