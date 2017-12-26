@extends('layouts/default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<h2>
					Folders
					&nbsp;
					<button class="btn btn-small btn-primary" type="button" data-toggle="modal" 
						data-target="#create_folder_modal">Add Folder</button>
				</h2>
				<div class="table-responsive">
					<table id="folders_table" class="table table-bordered table-striped table-hover table-order-column">
						<thead>
							<th>Name</th>
							<th>No. of Links</th>
							<th>Created On</th>
							<th>Actions</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="create_folder_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="create_folder_modal_title"></h4>
	        </div>
	        <div class="modal-body">
	            <form id="create_folder_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <div class"form-group">
	                    <label>Name</label>
	                    <input id="folder_name" class="form-control" name="folder_name" type="text" required>
	                </div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" id="save_folder_button" class="btn btn-outline btn-default pull-right">Save</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

<div id="create_link_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title" id="create_edit_link_modal_title">
	                Create Link
	            </h4>
	        </div>
	        <div class="modal-body" id="modal-body">
	            <form id="create_link_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <div class="form-group">
	                    <label>Folder</label>
	                    <input type="text" id="edit_link_folder_name" class="form-control" readonly/>
	                    <select id="folders_list" class="form-control" name="folder_id" required>
	                    </select>
	                </div>
	                <div class="form-group">
	                    <label>Name</label>
	                    <input id="link_name" class="form-control" name="link_name" type="text">
	                </div>
	                <div class="form-group">
	                    <label>Link</label>
	                    <input id="link_url" class="form-control" name="link_url" type="text" required>
	                </div>
	        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" id="save_link_button" class="btn btn-outline btn-default pull-right">Save</button>
			        </div>
	        	</form>
	        </div>
	    </div>
    </div>
</div>

<div id="links_table_modal" class="modal" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog modal-lg">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title">
	                Links in <span id="links_modal_title"></span>
	            </h4>
	        </div>
	        <div class="modal-body">
	            <table id="links_table" class="table table-bordered table-striped table-hover table-order-column" table-width="fixed">
	            	<thead>
	            		<th>Name</th>
	            		<th>Link URL</th>
	            		<th>Created At</th>
	            		<th>Actions</th>
	            	</thead>
	            </table>
	        </div>
        </div>
    </div>
</div>

<div id="change_link_folder_modal" class="modal modal-primary" tabindex="-1" data-focus-on="input:first">
    <div class="modal-dialog modal-lg">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">×</span></button>
	            <h4 class="modal-title">
	                Change Folder
	            </h4>
	        </div>
	        <div class="modal-body">
	            <form id="change_link_folder_form">
	                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	                <input type="hidden" id="current_folder_id" name="current_folder_id" />
	                <input type="hidden" id="change_link_folder_form_link_id" name="change_link_folder_form_link_id"/>
	                <div class="form-group">
	                	<label>Current Folder</label>
	                	<input type="text" readonly="" class="form-control" id="current_folder" />
	                </div>
	                <div class"form-group">
	                    <label>New Folder</label>
	                    <select id="new_folders_list" class="form-control" name="folderId" required>
	                    </select>
	                </div>
	        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
			            <button type="submit" class="btn btn-outline btn-default pull-right">Save</button>
			        </div>
	        	</form>
	        </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">

var windowFolders = [], foldersTableData = [], foldersForDropDown = [];
var foldersTable = null, linksTable = null;

var createEditLinkModalTitle = 'Create Link', windowFolderName = '';
var createLink = 1, editLinkId = null, createFolder = 1, editFolderId = null;

$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).ready(function() {

	foldersTable = $('#folders_table').DataTable();
	linksTable = $('#links_table').DataTable();

	$('#create_edit_link_modal_title').val(createEditLinkModalTitle);
	$('#create_folder_modal_title').text('Create Folder');

	getFolders();

	$('#create_folder_form').on('submit', function(e) {
		e.preventDefault();

		var requestUrl = 'api/folder',
			method = 'POST',
			blockMessage = 'Creating folder...';
		
		if(!createFolder) {
			requestUrl = 'api/folder/' + editFolderId;
			method = 'PUT';
			blockMessage = 'Updating folder...';
		}

		$('#create_folder_form').block({
			message: blockMessage
		});

		var formData = getFormObj('create_folder_form');

		callApi({
			method: method,
			requestUrl: requestUrl,
			data: formData
		}).then(function(response) {
			if(response.status == 1) {
				swal('Success!', response.data.message, 'success');
				$('#create_folder_modal').modal('hide');
				$('#create_folder_form').unblock();
				getFolders();
				$('#create_folder_form')[0].reset();

				createFolder = 1;
				$('#create_folder_modal_title').text('Create Folder');
			} else {
				$('#create_folder_form').unblock();
				swal('Error!', response.error.message, 'error');	
			}
		}).catch(function(response) {
			$('#create_folder_form').unblock();
			swal('Error!', response.error.message, 'error');
		});
	});

	$('#create_link_form').on('submit', function(e) {
		e.preventDefault();
		$('#create_link_modal').block({
			message: 'Creating link...'
		});

		var formData = getFormObj('create_link_form');

		var selectedFolderId = formData.folder_id;
		var url = 'api/folder/' + formData.folder_id + '/link';
		var method = 'POST';

		if(createLink == 0) {
			url = 'api/folder/' + formData.folder_id + '/link/' + editLinkId;
			method = 'PUT';
		}

		var options = {
			requestUrl: url,
			method: method,
			data: formData
		}

		callApi(options).then(function(response) {
			if(response.status == 1) {
				$('#create_link_modal').unblock();
				$('#create_link_modal').modal('hide');
				swal('Success!', response.data.message, 'success');
				$('#create_link_form')[0].reset();
				getFolders();
				populateDropDown('folders_list', foldersForDropDown);
				populateDropDown('new_folders_list', foldersForDropDown);

				if(createLink == 0) {
					createLink = 1;
					$('#link_name').val('');
					$('#link_url').val('');
					$('#folders_list').show();
					$('#edit_link_folder_name').hide();
				}

				showLinksTable(selectedFolderId, windowFolderName);

			} else {
				$('#create_link_modal').unblock();
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(error) {
			$('#create_link_form').unblock();
			swal('Error!', error.error.message, 'error');
		});
	});

	$('#change_link_folder_form').on('submit', function(e) {

		e.preventDefault();

		$('#change_link_folder_form').block({
			message: 'Updating link...'
		});

		var formData = getFormObj('change_link_folder_form');
		var currentFolderId = formData.current_folder_id;

		var options = {
			requestUrl: 'api/folder/' + currentFolderId 
				+ '/link/' + formData.change_link_folder_form_link_id + '/folder',
			method: 'PUT',
			includeDefaultHeaders: true
		};

		delete formData.current_folder_id;
		delete formData.change_link_folder_form_link_id;

		options.data = formData;

		callApi(options).then(function(response) {
			if(response.status == 1) {
				$('#change_link_folder_modal').modal('hide');
				$('#links_table_modal').modal('hide');
				$('#change_link_folder_form').unblock();
				getFolders();
				showLinksTable(currentFolderId, $('#current_folder').val());
				swal('Success!', response.data.message, 'success');
			} else {
				$('#change_link_folder_form').unblock();
				swal('Error!', response.error.message, 'error');	
			}
		}).catch(function(error) {
			$('#change_link_folder_form').unblock();
			swal('Error!', error.error.message, 'error');
		});
	});

});

function getFolders() {

	$('#folders_table').block({
		message: 'Fetching data...'
	});

	var options = {
		requestUrl: 'api/folder'
	};
	callApi(options).then(function(response) {
		if(response.status == 1) {
			fillFoldersTable(response.data.folders);
		} else {
			$('#folders_table').unblock();
			swal('Error!', response.error.message, 'error');	
		}
	}).catch(function(error) {
		$('#folders_table').unblock();
		swal('Error!', error.error.message, 'error');
	});
}

function fillFoldersTable(folders) {
	foldersForDropDown = [];
	foldersTableData = [];
	
	folders.forEach(function(folder) {
		var row = [];

		row[0] = '<a href="#" onClick="showLinksTable(\'' + folder.id 
			+ '\', \'' + folder.folder_name + '\');" >' + folder.folder_name + '</a>';
		
		if(folder.links != undefined) {
			row[1] = folder.links.length;
		} else {
			row[1] = '0';
		}

		// var formattedDate = new Date(folder.created_at);
		// formattedDate = formattedDate.getDate() + ' ' + months[formattedDate.getMonth() + 1]
		// 	+ ' ' + formattedDate.getFullYear();
		row[2] = getFormattedDate(folder.created_at);

		var addLinkHtml = "<a href='#' onClick='showAddLinkModal(\"" + folder.id + "\");'>Add Link</a>";
		var editFolderHtml = '<a href="#" onClick="showEditFolderModal(' + folder.id + ')";>Edit</a>';
		var deleteFolderHtml = '<a href="#" onClick="showDeleteFolderModal(\'' + folder.id + '\');">Delete</a>';
		row[3] = addLinkHtml + ' | ' + editFolderHtml + ' | ' + deleteFolderHtml;

		foldersTableData.push(row);

		var option = [];

		option.value = folder.id;
		option.text = folder.folder_name;

		foldersForDropDown.push(option);
		windowFolders[folder.id] = folder;
	});
	
	foldersTable.clear().rows.add(foldersTableData).draw();
	populateDropDown('folders_list', foldersForDropDown);
	populateDropDown('new_folders_list', foldersForDropDown);

	$('#folders_table').unblock();
}

function showEditFolderModal(folderId) {
	var folder = windowFolders[folderId];

	if(folder == undefined) {
		swal('Error!', 'Folder not found!', 'error');
		return;
	}

	createFolder = 0;
	editFolderId = folderId;
	$('#folder_name').val(folder.folder_name);
	$('#create_folder_modal_title').text('Edit Folder');
	$('#create_folder_modal').modal('show');
}

function showAddLinkModal(folderId) {

	$('#folders_list').show();
	$('#edit_link_folder_name').hide();
	createLink = 1;
	$('#link_name').val('');
	$('#link_url').val('');
	$('#create_edit_link_modal_title').text('Add Link');
	$('#create_link_modal').modal('show');
}

function showLinksTable(folderId, folderName) {
	
	windowFolderName = folderName;
	$('#links_modal_title').text(folderName);
	$('#links_table').block({
		message: 'Fetching links...'
	});

	callApi({
		requestUrl: 'api/folder/' + folderId + '/link'
	}).then(function(response) {
		if(response.status == 1) {
			fillLinksTable(response.data.links);
		} else {
			swal('Error!', response.error.message, 'error');
			$('#links_table').unblock();
		}
	}).catch(function(error) {
		$('#folders_table').unblock();
		swal('Error!', error.error.message, 'error');
		$('#links_table').unblock();
	});
}

function fillLinksTable(links) {

	var linksTableData = [];

	links.forEach(function(link) {
		var row = [];

		row[0] = link.link_name;
		row[1] = '<a href="' + link.link_url + '" target="_blank">' + link.link_url + '</a>';

		var formattedDate = new Date(link.created_at);
		formattedDate = formattedDate.getDate() + ' ' + months[formattedDate.getMonth() + 1]
			+ ' ' + formattedDate.getFullYear() + ' ' + formattedDate.getHours() + ':' + formattedDate.getMinutes();
		row[2] = formattedDate;

		var editLinkHtml = '<a href=\'#\' onClick=\'showEditLinkModal(' + JSON.stringify(link) + ');\'>Edit</a>';
		var deleteLinkHtml = '<a href=\'#\' onClick=\'showDeleteLinkModal(' + JSON.stringify(link) + ');\'>Delete</a>';
		var changeFolderLinkHtml = '<a href=\'#\' onClick=\'showChangeFolderLinkModal(' + JSON.stringify(link) + ');\'>Change Folder</a>';
		row[3] = editLinkHtml + ' | ' + deleteLinkHtml + ' | ' + changeFolderLinkHtml;

		linksTableData.push(row);
	});

	linksTable.clear().rows.add(linksTableData).order([ 2, 'desc' ]).draw();
	$('#links_table').unblock();

	$('#links_table_modal').modal('show');
}

function showChangeFolderLinkModal(link) {

	$('#change_link_folder_form_link_id').val(link.id);
	$('#current_folder_id').val(link.folder_id);
	$('#current_folder').val(link.folder.folder_name);

	$('#change_link_folder_modal').modal('show');
}
    
function showEditLinkModal(link) {
	
	$('#link_name').val(link.link_name);
	$('#link_url').val(link.link_url);
	$('#folders_list').hide();
	$('#edit_link_folder_name').val(windowFolderName);
	$('#edit_link_folder_name').show();
	createLink = 0;
	editLinkId = link.id;

	$('#create_edit_link_modal_title').text('Edit Link');
	$('#create_link_modal').modal('show');
}

function showDeleteLinkModal(link) {
	swal({
		title: "Are you sure?",
		text: "You will not be able to recover this later!",
		type: "warning",
		showCancelButton: true,
		// confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false
	},
	function(){
		callApi({
			method: 'DELETE',
			requestUrl: 'api/folder/' + link.folder_id + '/link/' + link.id
		}).then(function(response) {
			if(response.status == 1) {
				swal("Deleted!", response.data.message, "success");
				getFolders();
				showLinksTable(link.folder_id, link.folder.folder_name);	
			} else {
				swal('Error!', response.error.message, 'error');
			}
		}).catch(function(response) {
			swal('Error!', responseJSON.error.message, 'error');
		});
	});
}

function showDeleteFolderModal(folderId) {

	swal({
		title: "Are you sure?",
		text: "You will not be able to recover this later. All links in this folder will be deleted as well.",
		type: "warning",
		showCancelButton: true,
		// confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false
	},
	function(){
		callApi({
			method: 'DELETE',
			requestUrl: 'api/folder/' + folderId
		}).then(function(response) {
			if(response.status == 1) {
				swal("Deleted!", response.data.message, "success");
				getFolders();	
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