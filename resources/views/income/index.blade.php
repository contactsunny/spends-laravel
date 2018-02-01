@extends('layouts/default')

@section('content')

@include('income/income_management')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					Recurring Income
					&nbsp;
					<button class="btn btn-small btn-primary" type="button" data-toggle="modal" 
						data-target="#add_recurring_income_modal">Add Recurring Income</button>
				</h2>
				<div class="table-responsive">
					<table id="recurring_income_table" class="table table-bordered table-striped table-hover table-order-column">
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

<div id="add_recurring_income_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="add_recurring_income_modal_title"></h4>
	        </div>
	        <div class="modal-body">
	            <form id="add_recurring_income_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <div class"form-group">
	                    <label>Name</label>
	                    <input id="recurring_income_name" class="form-control" name="income_name" type="text" required>
	                </div>
	                <div class"form-group">
	                    <label>Value (INR)</label>
	                    <input id="recurring_income_value" class="form-control" 
	                    	name="income_value" type="number" required>
	                </div>
	                <div class"form-group">
	                    <label>Type</label>
	                    <select id="recurring_income_type_dropdown" class="form-control" name="income_type" required>
	                    </select>
	                </div>
	                <div class"form-group">
	                    <label>Repeat Frequency</label>
	                    <select id="recurring_income_frequency_dropdown" class="form-control" 
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
			            <button type="submit" id="save_recurring_income_button" class="btn btn-outline btn-default pull-right">Add</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	
var recurringIncomeTable, recurringIncomeTableData = [];
var recurringIncomes = [];
var editRecurringIncome = false, editRecurringIncomeId;

$(document).ready(function() {

	recurringIncomeTable = $('#recurring_income_table').DataTable({
		"order": [[ 6, "asc" ]]
	});

	getRecurringIncome();

	getIncomeTypes();
	getIncomeFrequencies();

	$('#add_recurring_income_modal_title').text('Add Recurring Income');

	$('#add_recurring_income_form').on('submit', function(e) {

		e.preventDefault();

		var formData = getFormObj('add_recurring_income_form');
		
		var blockMessage = 'Adding recurring income...';
		var successMessage = 'Recurring Income added successfully!';
		var successTitle = 'Added';

		var options = {
			requestUrl: 'api/recurringIncome',
			method: 'POST',
			data: formData
		};

		if (editRecurringIncome == true) {
			blockMessage = 'Updaing recurring income...';

			options = {
				requestUrl: 'api/recurringIncome/' + editRecurringIncomeId,
				method: 'PUT',
				data: formData
			};

			successMessage = 'Income updated successfully!';
			successTitle = 'Updated';
		}

		$('#add_recurring_income_form').block({
			message: blockMessage
		});

		callApi(options).then(function(response) {

			$('#add_recurring_income_form').unblock();

			if(response.status == 1) {
				getRecurringIncome();
				swal(successTitle, successMessage, 'success');
				$('#recurring_income_name').val('');
				$('#recurring_income_value').val('');
				$('#add_recurring_income_modal').modal('hide');
				editRecurringIncome = false;
				editReuccingIncomeId = undefined;
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(error) {
			$('#add_recurring_income_form').unblock();
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

			populateDropDown('recurring_income_type_dropdown', options);
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

			populateDropDown('recurring_income_frequency_dropdown', options);
		} else {
			console.error(response.error.message);
		}
	}).catch(function(error) {
		console.error(error);
	});
}

function getRecurringIncome() {

	$('#recurring_income_table').block({
		message: 'Getting recurring income...'
	});

	var options = {
		requestUrl: 'api/recurringIncome'
	};

	callApi(options).then(function(response) {
		if(response.status == 1) {
			recurringIncomes = response.data.data.incomes;
			fillRecurringIncomeTable(recurringIncomes);
		} else {
			$('#recurring_income_table').unblock();
			swal('Error!', response.error.message, 'error');
		}
	}).catch(function(error) {
		$('#recurring_income_table').unblock();
		console.error(error);
		swal('Error!', error.error.message, 'error');
	});
}

function fillRecurringIncomeTable(incomesData) {
	
	recurringIncomeTableData = [];

	incomesData.forEach(function(income) {
		var row = [];

		row[0] = income.id;
		row[1] = income.income_name;
		row[2] = income.income_value;
		row[3] = income.income_type.income_type;
		row[4] = income.income_frequency.frequency;
		row[5] = income.status;
		row[6] = getFormattedDate(income.created_at);

		var editIncomeHtml = "<a href='#' onClick='showEditRecurringIncomeModal(\"" 
				+ income.id + "\");'>Edit Income</a>";

		var deleteIncomeHtml = "<a href='#' onClick='showDeleteRecurringIncomeModal(\"" 
				+ income.id + "\");'>Delete Income</a>";

		row[7] = editIncomeHtml + ' | ' + deleteIncomeHtml;

		recurringIncomeTableData.push(row);
	});

	recurringIncomeTable.clear().rows.add(recurringIncomeTableData).draw();
	$('#recurring_income_table').unblock();
}

function showEditRecurringIncomeModal(incomeId) {

	var income = findRecurringIncomeById(incomeId);

	if (income == undefined) {
		swal('Error!', 'Recurring income doesnot exist.', 'error');
	}

	$('#recurring_income_name').val(income.income_name);
	$('#recurring_income_value').val(income.income_value);
	$('#recurring_income_type_dropdown').val(income.income_type.id);
	$('#recurring_income_frequency_dropdown').val(income.income_frequency.id);

	if (income.status == 'active') {
		$('#status').prop('checked', true);
	} else {
		$('#status').prop('checked', false);
	}

	editRecurringIncome = true;
	editRecurringIncomeId = income.id;

	$('#add_recurring_income_modal_title').text('Edit Recurring Income');
	$('#add_recurring_income_modal').modal('show');
}

function showDeleteRecurringIncomeModal(incomeId) {

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
			requestUrl: 'api/recurringIncome/' + incomeId
		}).then(function(response) {
			if(response.status == 1) {
				getRecurringIncome();
				swal("Deleted!", response.data.message, "success");
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(response) {
			swal('Error!', response.error.message, 'error');
		});
	});
}

function findRecurringIncomeById(incomeId) {

	var income;

	recurringIncomes.forEach(incomeObj => {

		if (incomeObj.id == incomeId) {
			income = incomeObj;
		}
	});

	return income;
}

</script>
@endsection