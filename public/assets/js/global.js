
/* AJAX */
function launchAJAX(script, data, callback) {
    const xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (xhttp.readyState !== 4) return;
        let res = xhttp.responseText;
        if (xhttp.responseText.startsWith('{')) res = JSON.parse (xhttp.responseText);
        if (!callback(res, xhttp.status, xhttp.statusText)) {
            showAlertDanger('AJAX Error', 'Unexpected <code>' + xhttp.status + ' - ' + xhttp.statusText + '</code> @ <code>' + script + '</code> | Please report this error.');
        }
    };

    xhttp.open('POST', ROOT + script);
    xhttp.setRequestHeader('Content-Type', 'application/json');
    xhttp.send(JSON.stringify(data));
}
/* AJAX */





/* Show Alert */
function showAlertDanger(title, description) {
    let html = '';
    html += '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
    html += '<strong class="mr-2">' + title + '</strong>';
    html += description;
    html += '<button class="close" data-dismiss="alert">&times;</button>';
    html += '</div>';
    document.getElementById('error_container').innerHTML += html;
}
/* Show Alert */





/* Remove Error Border */
function removeErrorBorder(e) {
    e.classList.remove('border-danger');
}
/* Remove Error Border */





/* Utility */
function isEmail(str) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(str);
}

function checkValid(element, valid) {
    if (valid) {
        element.classList.remove('border-danger');
        return false;
    } else {
        element.classList.add('border-danger');
        return true;
    }
}
/* Utility */





/* Cookies */
function setCookie(name, value) {
    const d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    const expires = 'expires=' + d.toUTCString();
    document.cookie = name + '=' + value + ';' + expires + ';path=/';
}

function getCookie(name) {
    name += '=';
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

function unsetCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}
/* Cookies */





/* Clipboard */
function copyToClipboard(text) {
    var input = document.createElement('INPUT');
    input.type = 'text';
    input.value = text;
    document.body.append(input);

    input.select();
    input.setSelectionRange(0, 99999);

    document.execCommand('copy');

    input.remove();
}
/* Clipboard */
