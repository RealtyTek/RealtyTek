@extends('admin.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Page Title
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin-faqs.update',['admin_faq' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Question</label>
                                        <textarea required name="question"  class="form-control">{{$data->question}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <textarea required name="answer"  class="form-control">{{$data->answer}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="0" {{($data->status == 0) ? 'selected' : ""}}>Deactive</option>
                                            <option value="1"  {{($data->status == 1) ? 'selected' : ""}}>Active</option>
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
@endsection
