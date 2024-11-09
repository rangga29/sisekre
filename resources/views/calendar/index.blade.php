<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/rsck_logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}" id="app-style" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}" />
</head>
<body data-menu-color="light" data-sidebar="hidden">
    <div id="app-layout"  style="background-color: #00aeef">
        <div class="topbar-custom" style="background-color: #00aeef">
            <div class="container-fluid">
                <div class="d-flex justify-content-between">
                    <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                        <li>
                            <a class="button-toggle-menu nav-link mt-2" href="#">
                                <img src="{{ asset('assets/images/healthcarelogo.png') }}" alt="Healthcare Logo">
                            </a>
                        </li>
                        <li class="d-none d-lg-block mt-4 mx-5">
                            <h1 class="mb-0 text-uppercase fw-bolder" style="color: black">Jadwal & Agenda Kegiatan Direksi</h1>
                        </li>
                    </ul>

                    <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <div id="live-clock"></div>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="py-2 d-flex align-items-sm-center flex-sm-row flex-column"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body app-calendar">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        "use strict";

        // Declare the calendar variable in the global scope
        let calendar;

        document.addEventListener("DOMContentLoaded", function() {
            let e = document.getElementById("calendar");

            // Initialize FullCalendar and assign to the global calendar variable
            calendar = new FullCalendar.Calendar(e, {
                timeZone: "local",
                locale: "id",
                initialView: "timeGridWeek",
                themeSystem: "bootstrap5",
                allDaySlot: false,
                height: 'auto',
                aspectRatio: 1.5,
                hiddenDays: [0],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,listMonth'
                },
                titleFormat: {
                    month: 'long',  // Full month name, e.g., "Oktober"
                    day: '2-digit', // Two-digit day number, e.g., "20"
                    year: 'numeric' // Four-digit year, e.g., "2024"
                },
                dayHeaderFormat: { // Customize date format for day headers
                    weekday: 'long', // Full weekday name, e.g., "Senin"
                    day: '2-digit',  // Two-digit day, e.g., "24"
                    month: 'long' // Full month name, e.g., "Oktober"
                },
                slotLabelFormat: { // Customize the hour slot format to 24-hour with minutes
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false // Disable AM/PM
                },
                eventTimeFormat: { // Set time format to 24-hour without AM/PM
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false // Disable AM/PM
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '17:00:00',
                events: @json($events),
            });

            // Render the calendar
            calendar.render();
        });

        function updateClock() {
            const now = new Date();
            // Format date in Indonesian (e.g., Rabu, 6 November 2024)
            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });

            // Format time in 24-hour format (e.g., 15:05:10)
            const time = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            // Update the clock element with formatted date and time
            document.getElementById('live-clock').innerHTML = `${date}<br>${time}`;
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Initial call to display clock immediately on page load
        updateClock();

        // Listen for the SendMessageEvent and refresh calendar events
        window.Echo.channel('schedules')
            .listen('SendMessageEvent', () => {
                calendar.refetchEvents(); // Refresh calendar events
            });
    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
