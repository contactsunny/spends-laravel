$('#logout_link').on('click', function(e) {
	logout();
});

function logout() {

	$.ajax({
		url: '/logout',
		method: 'DELETE',
		success: function(response) {
			if(response.status == 1) {
				$.ajax({
					url: 'api/logout',
					method: 'DELETE',
					headers: {
						Authorization: response.data.authToken
					},
					success: function(response) {
					}
				});
				window.location.href = '/';
			} else {
				swal('Error!', response.error.message, 'error');
			}
		},
		error: function(response) {
			swal('Error!', response.responseJSON.error.message, 'error');
		}
	})
}