@extends('layouts/withoutAuth')

@section('content') 

    <div class="login-box-body">
        <p class="login-box-msg">Enter your email to get password reset code</p>

        <div class="social-auth-links text-center">
            
            <form id="forgot_password_form">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Send Email</button>
                </div>
            </form>    

            <a href="{{ url('/') }}">Login</a><br>
            <a href="{{ url('register') }}" class="text-center">Register</a>

        </div>

    </div>

@endsection

@section('script')

<script type="text/javascript">
    
$(document).ready(function() {

    $('#forgot_password_form').on('submit', function(e) {
        e.preventDefault();
        $('#forgot_password_form').block({
            message: 'Sending email...'
        });
        var data = getFormObj('forgot_password_form');
        
        $.ajax({
            method: 'POST',
            url: '/api/forgot-password',
            data: data,
            success: function(response) {
                if(response.status == 1) {
                    $('#forgot_password_form').unblock();
                    swal('Success', response.data.message);
                } else {
                    $('#forgot_password_form').unblock();
                    swal('Error!', response.error.message, 'fail');
                }
            },
            error: function(response) {
                $('#forgot_password_form').unblock();
                swal('Error!', response.responseJSON.error.message, 'error');
            }
        });
    });

});

</script>

@endsection