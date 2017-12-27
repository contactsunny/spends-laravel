@extends('layouts/default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					Families
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

@endsection

@section('script')
<script type="text/javascript">
	
var familiesTable, familiesTableData = [];

$(document).ready(function() {

	familiesTable = $('#families_table').DataTable({
		"order": [[ 4, "asc" ]]
	});

	getFamilies();

});

function getFamilies() {

	$('#families_table').block({
		message: 'Getting families...'
	});

	var options = {
		requestUrl: 'api/family/all'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			fillfamiliesTable(response.data.data);
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
	
	families.forEach(function(family) {
		var row = [];

		row[0] = family.id;
		row[1] = family.family_name;
		row[2] = family.owner.name;
		row[3] = getFormattedDate(family.created_at);
		row[4] = 'Edit | Delete';

		familiesTableData.push(row);
	});

	familiesTable.clear().rows.add(familiesTableData).draw();
	$('#families_table').unblock();
}

</script>
@endsection