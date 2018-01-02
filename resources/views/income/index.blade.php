@extends('layouts/default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					Income
					&nbsp;
					<button class="btn btn-small btn-primary" type="button" data-toggle="modal" 
						data-target="#add_income_modal">Add Income</button>
				</h2>
				<div class="table-responsive">
					<table id="income_table" class="table table-bordered table-striped table-hover table-order-column">
						<thead>
							<th>ID</th>
							<th>Name</th>
							<th>Value</th>
							<th>Type</th>
							<th>Frequency</th>
							<th>Status</th>
							<th>Created On</th>
							<th>Actions</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="add_income_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="add_income_modal_title"></h4>
	        </div>
	        <div class="modal-body">
	            <form id="add_income_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <div class"form-group">
	                    <label>Name</label>
	                    <input id="income_name" class="form-control" name="income_name" type="text" required>
	                </div>
	                <div class"form-group">
	                    <label>Value (INR)</label>
	                    <input id="income_value" class="form-control" 
	                    	name="income_value" type="number" required>
	                </div>
	                <div class"form-group">
	                    <label>Type</label>
	                    <select id="income_type_dropdown" class="form-control" name="income_type" required>
	                    </select>
	                </div>
	                <div class"form-group">
	                    <label>Repeat Frequency</label>
	                    <select id="income_frequency_dropdown" class="form-control" 
	                    	name="income_frequency" required>
	                    </select>
	                </div>
	                <br><div class"form-group">
	                    <label>Active</label> &nbsp;
	                    <input value="active" name="status" 
	                    	required type="checkbox" checked>
	                </div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" id="save_folder_button" class="btn btn-outline btn-default pull-right">Add</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	
var incomeTable, incomeTableData = [];
var incomes = [];
var editIncome = false, editIncomeId;

$(document).ready(function() {

	incomeTable = $('#income_table').DataTable({
		"order": [[ 6, "asc" ]]
	});

	getIncome();

	getIncomeTypes();
	getIncomeFrequencies();

	$('#add_income_modal_title').text('Add Income');

	$('#add_income_form').on('submit', function(e) {

		e.preventDefault();

		var formData = getFormObj('add_income_form');
		
		$('#add_income_form').block({
			message: 'Adding income...'
		});

		var options = {
			requestUrl: 'api/income',
			method: 'POST',
			data: formData
		};

		callApi(options).then(function(response) {

			$('#add_income_form').unblock();

			if(response.status == 1) {
				getIncome();
				swal('Added', 'Income added successfully!', 'success');
				$('#income_name').val('');
				$('#income_value').val('');
				$('#add_income_modal').modal('hide');
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(error) {
			$('#add_income_form').unblock();
			console.error(error);
			swal('Error!', error.error.message, 'error');
		});
	});

});

function getIncomeTypes() {

	var options = {
		requestUrl: 'api/incomeType'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			var options = [];

			response.data.data.incomeTypes.forEach(function(incomeType) {

				var option = [];

				option.text = incomeType.income_type;
				option.value = incomeType.id;

				options.push(option);
			});

			populateDropDown('income_type_dropdown', options);
		} else {
			console.error(response.error.message);
		}
	}).catch(function(error) {
		console.error(error);
	});
}

function getIncomeFrequencies() {

	var options = {
		requestUrl: 'api/incomeFrequency'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			var options = [];

			response.data.data.incomeFrequencies.forEach(function(incomeFrequency) {

				var option = [];

				option.text = incomeFrequency.frequency;
				option.value = incomeFrequency.id;

				options.push(option);
			});

			populateDropDown('income_frequency_dropdown', options);
		} else {
			console.error(response.error.message);
		}
	}).catch(function(error) {
		console.error(error);
	});
}

function getIncome() {

	$('#income_table').block({
		message: 'Getting income...'
	});

	var options = {
		requestUrl: 'api/income'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			incomes = response.data.data.incomes;
			fillIncomeTable(incomes);
		} else {
			$('#income_table').unblock();
			swal('Error!', response.error.message, 'error');
		}
	}).catch(function(error) {
		$('#income_table').unblock();
		console.error(error);
		swal('Error!', error.error.message, 'error');
	});
}

function fillIncomeTable(incomesData) {
	
	incomeTableData = [];

	incomesData.forEach(function(income) {
		var row = [];

		row[0] = income.id;
		row[1] = income.income_name;
		row[2] = income.income_value;
		row[3] = income.income_type.income_type;
		row[4] = income.income_frequency.frequency;
		row[5] = income.status;
		row[6] = getFormattedDate(income.created_at);

		var editIncomeHtml = "<a href='#' onClick='showEditIncomeModal(\"" 
				+ income.id + "\");'>Edit Income</a>";

		var deleteIncomeHtml = "<a href='#' onClick='showDeleteIncomeModal(\"" 
				+ income.id + "\");'>Delete Income</a>";

		row[7] = editIncomeHtml + ' | ' + deleteIncomeHtml;

		incomeTableData.push(row);
	});

	incomeTable.clear().rows.add(incomeTableData).draw();
	$('#income_table').unblock();
}

function showEditIncomeModal(incomeId) {

	var income = findIncomeById(incomeId);

	if (income == undefined) {
		swal('Error!', 'Income doesnot exist.', 'error');
	}

	$('#income_name').val(income.income_name);
	$('#income_value').val(income.income_value);
	$('#income_type_dropdown').val(income.income_type.id);
	$('#income_frequency_dropdown').val(income.income_frequency.id);

	if (income.status == 'active') {
		$('#status').prop('checked', true);
	} else {
		$('#status').prop('checked', false);
	}

	editIncome = true;
	editIncomeId = income.id;

	$('#add_income_modal_title').text('Edit Income');
	$('#add_income_modal').modal('show');
}

function showDeleteIncomeModal(incomeId) {

	swal({
		title: "Are you sure?",
		text: "You will not be able to recover this later.",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false
	},
	function(){
		callApi({
			method: 'DELETE',
			requestUrl: 'api/income/' + incomeId
		}).then(function(response) {
			if(response.status == 1) {
				getIncome();
				swal("Deleted!", response.data.message, "success");
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(response) {
			swal('Error!', response.error.message, 'error');
		});
	});
}

function findIncomeById(incomeId) {

	var income;

	incomes.forEach(incomeObj => {

		if (incomeObj.id == incomeId) {
			income = incomeObj;
		}
	});

	return income;
}

</script>
@endsection