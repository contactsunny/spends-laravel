@extends('layouts/withoutAuth')

@section('content')    
    
    <div class="login-box-body">
        <p class="login-box-msg">Enter new password</p>

        <div class="social-auth-links text-center">
            
            <form id="reset_password_form">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" id="token" value="{{ $token }}">
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" 
                        minlength="8" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="repeat_password" 
                        minlength="8" placeholder="Repeat Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
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

    $('#reset_password_form').on('submit', function(e) {
        e.preventDefault();
        $('#reset_password_form').block({
            message: 'Resetting password...'
        });
        var data = getFormObj('reset_password_form');

        if(data.password != data.repeat_password) {
            $('#reset_password_form').unblock();
            swal('Error!', 'Passwords don\'t match!', 'error');
            return;
        }
        
        $.ajax({
            method: 'POST',
            url: '/api/reset-password',
            data: data,
            success: function(response) {
                if(response.status == 1) {
                    $('#reset_password_form').unblock();
                    $('#reset_password_form').get(0).reset();
                    swal('Success', response.data.message);
                } else {
                    $('#reset_password_form').unblock();
                    swal('Error!', response.error.message, 'error');
                }
            },
            error: function(response) {
                $('#reset_password_form').unblock();
                swal('Error!', response.responseJSON.error.message, 'error');
            }
        });
    });

});

</script>

@endsection