@extends('layouts/withoutAuth')

@section('content') 

        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <div class="social-auth-links text-center">
                
                <form id="registration_form">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                </form>    

                <a href="{{ url('forgot-password') }}">Forgot Password?</a><br>
                <a href="{{ url('/') }}">Login</a><br>

            </div>

        </div>

@endsection

@section('script')
<script type="text/javascript">
    
$(document).ready(function() {

    $('#registration_form').on('submit', function(e) {
        e.preventDefault();
        $('#registration_form').block({
            message: 'Registering...'
        });

        var data = getFormObj('registration_form');
        
        $.ajax({
            method: 'POST',
            url: '/api/register',
            data: data,
            success: function(response) {
                if(response.status == 1) {
                    swal('Success!', response.data.message, 'success');
                    window.location.href = '/';
                } else {
                    $('#registration_form').unblock();
                    swal('Error!', response.error.message, 'fail');
                }
            },
            error: function(response) {
                $('#registration_form').unblock();
                swal('Error!', response.responseJSON.error.message, 'error');
            }
        });
    });

});

</script>

@endsection