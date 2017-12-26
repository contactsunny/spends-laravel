@extends('layouts/default')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-header">Profile</h3>
                </div>
                <form role="form" id="update_profile_form">
                    <div class="box-body">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" readonly>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-header">Change Password</h3>
                </div>
                <form role="form" id="change_password_form">
                    <div class="box-body">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" class="form-control" id="current_password" 
                            name="current_password" minlength="8" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" class="form-control" id="new_password" 
                            name="new_password" minlength="8" required>
                        </div>
                        <div class="form-group">
                            <label>Repeat New Password</label>
                            <input type="password" class="form-control" id="repeat_password" 
                            name="repeat_password" minlength="8" required>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script type="text/javascript">

    $(document).ready(function () {

        var cookieUser = JSON.parse(getCookie('user'));

        $('#email').val(cookieUser.email);
        $('#name').val(cookieUser.name);

        $('#update_profile_form').on('submit', function(e) {
            e.preventDefault();
            $('#udpate_profile_form').block({
                message: 'Updating profile...'
            });

            var formData = getFormObj('update_profile_form');

            var options = {
                requestUrl: 'api/user/' + cookieUser.id,
                method: 'PUT',
                data: formData
            };

            callApi(options).then(function(response) {
                if(response.status == 1) {
                    swal('Success!', 'Profile updated successfully!', 'success');
                    cookieUser.name = response.data.user.name;
                    setCookie('user', JSON.stringify(cookieUser));
                } else {
                    swal('Error!', response.error.message, 'error');
                }

                $('#update_profile_form').unblock();
            }).catch(function(error) {
                $('#update_profile_form').unblock();
                swal('Error!', response.error.message, 'error');
            });
        });

        $('#change_password_form').on('submit', function(e) {
            e.preventDefault();
            $('#change_password_form').block({
                message: 'Updating password...'
            });

            var formData = getFormObj('change_password_form');

            if(formData.new_password != formData.repeat_password) {
                $('#change_password_form').unblock();
                swal('Error!', 'New passwords don\'t match!', 'error');
                return;
            }

            var options = {
                requestUrl: 'api/user/password/' + cookieUser.id,
                method: 'PUT',
                data: formData
            };

            callApi(options).then(function(response) {
                if(response.status == 1) {
                    swal('Success!', 'Password updated successfully!', 'success');
                } else {
                    swal('Error!', response.error.message, 'error');
                }

                $('#change_password_form').unblock();
            }).catch(function(error) {
                $('#change_password_form').unblock();
                swal('Error!', response.error.message, 'error');
            });
        });

    });

</script>
@endsection