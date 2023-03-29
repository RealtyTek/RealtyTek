<!DOCTYPE html>
<html>
<head>
    <title>Help and Support | {{ env('APP_NAME') }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>
<div class="form-gap"></div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3><i class="fa fa-envelope fa-4x"></i></h3>
                        <h2 class="text-center">Help and Support</h2>
                        <div class="panel-body">
                            @include('admin.flash-message')
                            <form action="{{route('app.submit.support')}}"  class="form" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="text" name="name" placeholder="Enter Name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        <input type="text" name="email" placeholder="Enter Email" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-keyboard-o"></i></span>
                                        <input type="text" name="subject" placeholder="Enter Subject" class="form-control">
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-keyboard-o"></i></span>
                                        <textarea name="description" placeholder="Enter Subject" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-lg btn-primary btn-block" value="Submit" type="submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
