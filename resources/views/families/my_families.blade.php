@extends('layouts/default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					My Families
					&nbsp;
					<button class="btn btn-small btn-primary" type="button" data-toggle="modal" 
						data-target="#create_family_modal">Create Family</button>
				</h2>
				<div class="table-responsive">
					<table id="families_table" class="table table-bordered table-striped table-hover table-order-column">
						<thead>
							<th>ID</th>
							<th>Name</th>
							<th>Owner Name</th>
							<th>Created On</th>
							<th>Actions</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="invite_to_family_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="invite_to_family_modal_title"></h4>
	        </div>
	        <div class="modal-body">
	            <form id="invite_to_family_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <input type="hidden" name="family_id" id="family_id">
	                <div class"form-group">
	                    <label>Name</label>
	                    <input id="name" class="form-control" name="name" type="text" required>
	                </div>
	                <div class"form-group">
	                    <label>Email</label>
	                    <input id="email" class="form-control" name="email" type="email" required>
	                </div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" id="save_folder_button" class="btn btn-outline btn-default pull-right">Invite</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

<div id="create_family_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="create_family_modal_title"></h4>
	        </div>
	        <div class="modal-body">
	            <form id="create_family_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <div class"form-group">
	                    <label>Family Name</label>
	                    <input id="family_name" class="form-control" name="family_name" type="text" required>
	                </div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" id="save_folder_button" class="btn btn-outline btn-default pull-right">Create</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	
var familiesTable, familiesTableData = [];

$(document).ready(function() {

	$('#invite_to_family_modal_title').text('Invite To Family');
	$('#create_family_modal_title').text('Create Family');

	familiesTable = $('#families_table').DataTable({
		"order": [[ 4, "asc" ]]
	});

	getFamilies();

	$('#invite_to_family_form').on('submit', (e) => {

		e.preventDefault();	

		var formData = getFormObj('invite_to_family_form');
		
		$('#invite_to_family_form').block({
			message: 'Inviting to family...'
		});

		var options = {
			requestUrl: 'api/family/invite',
			method: 'POST',
			data: formData
		};

		callApi(options).then(function(response) {

			$('#invite_to_family_form').unblock();

			if(response.status == 1) {
				swal('Invited', 'User inited to family!', 'success');
				$('#name').val('');
				$('#email').val('');
				$('#invite_to_family_modal').modal('hide');
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(error) {
			$('#invite_to_family_form').unblock();
			console.error(error);
			swal('Error!', error.error.message, 'error');
		});
	});

	$('#create_family_form').on('submit', (e) => {

		e.preventDefault();	

		var formData = getFormObj('create_family_form');
		
		$('#create_family_form').block({
			message: 'Creating family...'
		});

		var options = {
			requestUrl: 'api/family',
			method: 'POST',
			data: formData
		};

		callApi(options).then(function(response) {

			$('#create_family_form').unblock();

			if(response.status == 1) {
				getFamilies();
				swal('Invited', 'Family created!', 'success');
				$('#family_name').val('');
				$('#create_family_modal').modal('hide');
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(error) {
			$('#create_family_form').unblock();
			console.error(error);
			swal('Error!', error.error.message, 'error');
		});
	});

});

function getFamilies() {

	$('#families_table').block({
		message: 'Getting families...'
	});

	var options = {
		requestUrl: 'api/family'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			fillfamiliesTable(response.data.data.families);
		} else {
			$('#families_table').unblock();
			swal('Error!', response.error.message, 'error');
		}
	}).catch(function(error) {
		$('#families_table').unblock();
		console.error(error);
		swal('Error!', error.error.message, 'error');
	});
}

function fillfamiliesTable(families) {

	familiesTableData = [];
	
	families.forEach(function(family) {
		var row = [];

		row[0] = family.id;
		row[1] = "<a href='/family/" + family.id + "'>" + family.family_name + "</a>";
		row[2] = family.owner.name;
		row[3] = getFormattedDate(family.created_at);

		var cookieUser = getCookie('user');

		if (cookieUser == null || cookieUser == '') {
			window.location = '/';
		}

		var user = JSON.parse(cookieUser);

		if (user.id == family.owner_id) {

			var inviteToFamilyHtml = "<a href='#' onClick='showInviteToFamilyModal(\"" 
				+ family.id + "\");'>Invite To Family</a>";

			var deleteFamilyHtml = "<a href='#' onClick='showDeleteFamilyModal(\"" 
				+ family.id + "\");'>Delete Family</a>";

			row[4] = inviteToFamilyHtml + ' | Edit | ' + deleteFamilyHtml;
		} else {
			row[4] = 'NA';
		}

		familiesTableData.push(row);
	});

	familiesTable.clear().rows.add(familiesTableData).draw();
	$('#families_table').unblock();
}

function showInviteToFamilyModal(familyId) {

	$('#family_id').val(familyId);

	$('#invite_to_family_modal').modal('show');
}

function showDeleteFamilyModal(familyId) {

	swal({
		title: "Are you sure?",
		text: "You will not be able to recover this later.",
		type: "warning",
		showCancelButton: true,
		// confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false
	},
	function(){
		callApi({
			method: 'DELETE',
			requestUrl: 'api/family/' + familyId
		}).then(function(response) {
			if(response.status == 1) {
				getFamilies();
				swal("Deleted!", response.data.message, "success");
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(response) {
			swal('Error!', response.error.message, 'error');
		});
	});
}

</script>
@endsection