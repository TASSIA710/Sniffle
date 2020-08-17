
/* Relative Time */
function relativeTime(timestamp) {
    const now = Date.now();
    let diff = timestamp - now;
    if (diff < 0) diff = -diff;
    if (diff < 1000) return 'just now';

    let days = 0;
    let hours = 0;
    let minutes = 0;
    let seconds = 0;

    if (diff >= 1000*60*60*24) {
        days = Math.floor(diff / (1000*60*60*24));
        diff = diff % (1000*60*60*24);
    }
    if (diff >= 1000*60*60) {
        hours = Math.floor(diff / (1000*60*60));
        diff = diff % (1000*60*60);
    }
    if (diff >= 1000*60) {
        minutes = Math.floor(diff / (1000*60));
        diff = diff % (1000*60);
    }
    if (diff >= 1000) {
        seconds = Math.floor(diff / (1000));
        diff = diff % (1000);
    }

    let str = '';
    let str2;
    let count = 0;
    const limit = 2;
    const concat = function (str, amount, count, limit, singleString, multiString) {
        if (amount === 0 || count >= limit) return null;
        if (count !== 0) str += ', ';
        str += amount + ' ' + (amount === 1 ? singleString : multiString);
        return str;
    };

    str2 = concat(str, days, count, limit, 'day', 'days');
    if (str2) { str = str2; count++; }

    str2 = concat(str, hours, count, limit, 'hour', 'hours');
    if (str2) { str = str2; count++; }

    str2 = concat(str, minutes, count, limit, 'minute', 'minutes');
    if (str2) { str = str2; count++; }

    str2 = concat(str, seconds, count, limit, 'second', 'seconds');
    if (str2) { str = str2; count++; }

    const i = str.lastIndexOf(', ');
    if (i !== -1) str = str.substring(0, i) + ' and ' + str.substring(i + 2);

    if (now > timestamp) str = str + ' ago';
    else str = 'in ' + str;
    return str;
}
/* Relative Time */



/* Update Timestamps */
function updateTimestamps() {
    document.querySelectorAll('.timestamped').forEach(function(e) {
        if (!e.getAttribute('data-timestamp')) {
            e.innerHTML = 'ERROR';
            e.classList.remove('timestamped');
            return;
        }
        e.innerHTML = relativeTime(e.getAttribute('data-timestamp'));
    });
}

setInterval(function() {
    updateTimestamps();
}, 1000);

window.addEventListener('load', function() {
    updateTimestamps();
});
/* Update Timestamps */
