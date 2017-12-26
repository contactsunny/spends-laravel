function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires     = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function deleteCookie(name) {
    $.removeCookie(name, { path: '/' });
}

function getFormObj(formId) {
    var formObj = {};
    var inputs = $('#'+formId).serializeArray();
    $.each(inputs, function (i, input) {
        formObj[input.name] = input.value;
    });
    return formObj;
}

function populateDropDown(dropDownId, options) {

    $('#' + dropDownId).find('option').remove();

    $.each(options, function (i, option) {
        $('#' + dropDownId).append($('<option>', { 
            value: option.value,
            text : option.text 
        }));
    });
}

function sendErrorResponse(message) {

    return {
        error: {
            message: message
        }
    };
}

function getDefaultHeaders() {
    return {
        Authorization: getCookie('authToken')
    };
}

function callApi(options) {

    var method = 'GET',
        data = {},
        headers = {}
        ;

    var requiredFields = [
        'requestUrl'
    ];

    var promise = new Promise(
        function(resolve, reject) {
            requiredFields.forEach(function(requiredField) {
                if(!(requiredField in options)) {
                    reject(sendErrorResponse(requiredField + ' is required!'));
                }
            });

            if('method' in options) {
                method = options.method;
            }
            
            if(!('includeDefaultHeaders' in options)) {
                headers = getDefaultHeaders();
            } else if(options.includeDefaultHeaders == true) {
                headers = getDefaultHeaders();
            }

            if('data' in options) {
                data = options.data;
            }

            $.ajax({
                url: options.requestUrl,
                method: method,
                data: data,
                headers: headers,
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error.responseJSON);
                }
            });
        }
    );

    return promise;
}

function getFormattedDate(rawDate) {

    var formattedDate = new Date(rawDate);
    formattedDate = formattedDate.getDate() + ' ' + months[formattedDate.getMonth()]
        + ' ' + formattedDate.getFullYear();

    return formattedDate;
}