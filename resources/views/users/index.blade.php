@extends('layouts/default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					Users
				</h2>
				<div class="table-responsive">
					<table id="users_table" class="table table-bordered table-striped table-hover table-order-column">
						<thead>
							<th>ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>User Group</th>
							<th>Created On</th>
							<th>Actions</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	
var usersTable, usersTableData = [];

$(document).ready(function() {

	usersTable = $('#users_table').DataTable({
		"order": [[ 4, "asc" ]]
	});

	getUsers();

});

function getUsers() {

	$('#users_table').block({
		message: 'Getting users...'
	});

	var options = {
		requestUrl: 'api/user'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			fillUsersTable(response.data.users);
		} else {
			$('#users_table').unblock();
			swal('Error!', response.error.message, 'error');
		}
	}).catch(function(error) {
		$('#users_table').unblock();
		swal('Error!', error.error.message, 'error');
	});
}

function fillUsersTable(users) {

	users.forEach(function(user) {
		var row = [];

		row[0] = user.id;
		row[1] = user.name;
		row[2] = user.email;
		row[3] = user.user_group;
		row[4] = getFormattedDate(user.created_at);

		var cookieUser = JSON.parse(getCookie('user'));

		if(user.id == cookieUser.id) {
			row[5] = 'No actions available';
		} else {
			row[5] = 'Edit | Delete';
		}

		usersTableData.push(row);
	});

	usersTable.clear().rows.add(usersTableData).draw();
	$('#users_table').unblock();
}

</script>
@endsection