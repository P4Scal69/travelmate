$(function () {
    'use strict';

    var WMO = {
        0: '☀️ Clear sky', 1: '🌤️ Mainly clear', 2: '⛅ Partly cloudy', 3: '☁️ Overcast',
        45: '🌫️ Fog', 48: '🌫️ Rime fog', 51: '🌦️ Light drizzle', 53: '🌦️ Drizzle', 55: '🌧️ Dense drizzle',
        61: '🌧️ Light rain', 63: '🌧️ Rain', 65: '🌧️ Heavy rain', 71: '🌨️ Light snow', 73: '❄️ Snow', 75: '❄️ Heavy snow',
        80: '🌦️ Showers', 81: '🌦️ Showers', 82: '⛈️ Violent showers', 95: '⛈️ Thunderstorm', 99: '⛈️ Thunderstorm'
    };

    function toast(msg) {
        var $t = $('#toast');
        $t.text(msg).addClass('show');
        clearTimeout(toast._t);
        toast._t = setTimeout(function () { $t.removeClass('show'); }, 2600);
    }

    function renderWeather($box, data) {
        var emoji = (WMO[data.weather_code] || '🌡️').split(' ')[0];
        var desc = data.description || 'Unknown';
        $box.html(
            '<div class="weather-big"><span style="font-size:40px">' + emoji + '</span>' +
            '<div><div class="weather-temp">' + Math.round(data.temperature) + '°C</div>' +
            '<div class="weather-desc">' + desc + '</div></div></div>' +
            '<div class="weather-sub"><span>💧 ' + Math.round(data.humidity) + '% humidity</span>' +
            '<span>💨 ' + Math.round(data.wind) + ' km/h wind</span></div>'
        );
    }

    function fetchWeather(lat, lon, $box) {
        $box.html('<p class="muted">Loading weather…</p>');
        $.getJSON('api/weather.php', { lat: lat, lon: lon })
            .done(function (data) { renderWeather($box, data); })
            .fail(function () { $box.html('<p class="muted">Could not load weather.</p>'); });
    }

    $('#showRegister').on('click', function () {
        $('#loginForm').addClass('hidden');
        $('#registerForm').removeClass('hidden');
    });
    $('#showLogin').on('click', function () {
        $('#registerForm').addClass('hidden');
        $('#loginForm').removeClass('hidden');
    });

    if (new URLSearchParams(location.search).get('saved')) {
        toast('Trip saved ✓');
    }

    var $search = $('#searchInput');
    if ($search.length) {
        $search.on('input', function () {
            var q = $(this).val().toLowerCase();
            $('.trip-item').each(function () {
                var hit = $(this).data('search').indexOf(q) !== -1;
                $(this).toggle(hit);
            });
        });
    }

    $(document).on('click', '[data-delete]', function () {
        var id = $(this).data('delete');
        if (!confirm('Delete this trip? This cannot be undone.')) return;
        var $btn = $(this);
        $.post('api/delete.php', { id: id }, function (res) {
            if (res.success) {
                toast('Trip deleted');
                $btn.closest('.trip-item').remove();
            } else {
                toast('Delete failed');
            }
        }, 'json').fail(function () { toast('Delete failed'); });
    });

    if ($('#gpsBtn').length) {
        $('#gpsBtn').on('click', function () {
            if (!navigator.geolocation) { toast('GPS not supported'); return; }
            toast('Locating…');
            navigator.geolocation.getCurrentPosition(function (pos) {
                $('#latitude').val(pos.coords.latitude.toFixed(6));
                $('#longitude').val(pos.coords.longitude.toFixed(6));
                toast('Location captured 📍');
            }, function () { toast('Could not get location'); }, { enableHighAccuracy: true });
        });
    }

    if ($('#photo').length) {
        $('#photo').on('change', function () {
            var file = this.files[0];
            if (!file) return;
            var url = URL.createObjectURL(file);
            if ($('.preview').length) { $('.preview').attr('src', url); }
            else { $('<img class="preview" alt="preview">').attr('src', url).insertAfter(this); }
        });
    }

    if ($('#refreshWeather').length) {
        $('#refreshWeather').on('click', function () {
            if (!navigator.geolocation) { toast('GPS not supported'); return; }
            navigator.geolocation.getCurrentPosition(function (pos) {
                fetchWeather(pos.coords.latitude, pos.coords.longitude, $('#weatherBody'));
            }, function () { toast('Location denied'); }, { enableHighAccuracy: true });
        });
    }

    if ($('#weatherBtn').length) {
        $('#weatherBtn').on('click', function () {
            if (window.__TRIP_LAT != null) {
                fetchWeather(window.__TRIP_LAT, window.__TRIP_LON, $('#detailWeather'));
            } else {
                toast('No coordinates for this trip');
            }
        });
    }

    if ($('#map').length && window.__TRIP_LAT != null) {
        var map = L.map('map').setView([window.__TRIP_LAT, window.__TRIP_LON], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap', maxZoom: 19
        }).addTo(map);
        L.marker([window.__TRIP_LAT, window.__TRIP_LON]).addTo(map)
            .bindPopup(window.__TRIP_NAME || 'Trip location').openPopup();
    }

    if ($('#budgetChart').length && window.__CHART_LABELS) {
        new Chart($('#budgetChart'), {
            type: 'bar',
            data: {
                labels: window.__CHART_LABELS,
                datasets: [{
                    label: 'Budget (RM)',
                    data: window.__CHART_VALUES,
                    backgroundColor: '#0e7c66',
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    var dark = localStorage.getItem('tm_dark') === '1';
    var budgetFirst = localStorage.getItem('tm_budget') === '1';
    if (dark) $('body').addClass('dark');
    $('#darkToggle').prop('checked', dark);
    $('#budgetToggle').prop('checked', budgetFirst);

    $('#darkToggle').on('change', function () {
        var on = $(this).is(':checked');
        $('body').toggleClass('dark', on);
        localStorage.setItem('tm_dark', on ? '1' : '0');
    });
    $('#budgetToggle').on('change', function () {
        localStorage.setItem('tm_budget', $(this).is(':checked') ? '1' : '0');
        toast('Preference saved');
    });

    if ($('#deviceInfo').length) {
        var info = 'Platform: ' + (navigator.platform || 'unknown') +
            '<br>User agent: ' + navigator.userAgent +
            '<br>Online: ' + (navigator.onLine ? 'Yes' : 'No') +
            '<br>Screen: ' + screen.width + '×' + screen.height;
        if (navigator.getBattery) {
            navigator.getBattery().then(function (b) {
                $('#deviceInfo').html(info + '<br>Battery: ' + Math.round(b.level * 100) + '%');
            }).catch(function () { $('#deviceInfo').html(info); });
        } else {
            $('#deviceInfo').html(info);
        }
    }
});
