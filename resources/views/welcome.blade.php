@extends('layouts/withoutAuth')

@section('content') 

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <div class="social-auth-links text-center">
            
            <form id="login_form">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </form>    

            <a href="{{ url('forgot-password') }}">Forgot Password?</a><br>
            <a href="{{ url('register') }}" class="text-center">Register</a>

        </div>

    </div>
@endsection

@section('script')

<script type="text/javascript">
    
$(document).ready(function() {

    $('#login_form').on('submit', function(e) {
        e.preventDefault();
        $('#login_form').block({
            message: 'Logging in...'
        });
        var data = getFormObj('login_form');
        
        $.ajax({
            method: 'POST',
            url: '/api/login',
            data: data,
            success: function(response) {
                if(response.status == 1) {
                    setCookie('authToken', response.data.auth_token);
                    setCookie('user', JSON.stringify(response.data.user));
                    $.ajax({
                        method: 'POST',
                        url: '/login',
                        data: response.data
                    });
                    window.location.href = 'dashboard';
                } else {
                    $('#login_form').unblock();
                    swal('Error!', response.error.message, 'fail');
                }
            },
            error: function(response) {
                $('#login_form').unblock();
                swal('Error!', response.responseJSON.error.message, 'error');
            }
        });
    });

});

</script>

@endsection