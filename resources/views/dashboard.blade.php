<x-app-layout>
    @push('style')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}" id="app-style" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}" />
    @endpush

    <div class="py-12">
        <div class="max-h-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>SUCCESS - </strong>{{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>ERROR : </strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title">DATA JADWAL & AGENDA</h5>
                        <button class="btn btn-sm btn-primary fw-bolder sc-add" data-bs-toggle="modal" data-bs-target="#add-modal" data-max-date="{{ \Carbon\Carbon::now() }}">
                            TAMBAH DATA
                        </button>
                    </div>
                    <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                        <thead>
                        <tr class="text-uppercase text-center">
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Keterangan</th>
                            <th>Warna</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->getFormattedDateAttribute() }}</td>
                                <td>{{ $schedule->getFormattedTimeAttribute() }}</td>
                                <td>{{ $schedule->description }}</td>
                                @if(str_contains($schedule->class, 'primary'))
                                    <td class="table-primary" style="width: 50px;"></td>
                                @elseif(str_contains($schedule->class, 'secondary'))
                                    <td class="table-secondary" style="width: 50px;"></td>
                                @elseif(str_contains($schedule->class, 'danger'))
                                    <td class="table-danger" style="width: 50px;"></td>
                                @elseif(str_contains($schedule->class, 'warning'))
                                    <td class="table-warning" style="width: 50px;"></td>
                                @elseif(str_contains($schedule->class, 'info'))
                                    <td class="table-info" style="width: 50px;"></td>
                                @else
                                    <td class="table-dark" style="width: 50px;"></td>
                                @endif
                                <td style="width: 50px;">
                                    <div class="btn-group">
                                        {{--<button type="button" class="btn btn-sm btn-warning fw-bolder" title="EDIT DATA" data-bs-toggle="modal" data-bs-target="#edit-modal" data-schedule-id="{{ $schedule->id }}">UBAH</button>--}}
                                        <form method="POST" action="{{ route('schedules.destroy', $schedule->ucode) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bolder" title="DELETE DATA" onclick="return confirm('Yakin Ingin Menghapus Data?')">HAPUS</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="add-modal" class="modal modal-lg fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title text-uppercase" id="dark-header-modalLabel">Tambah Data Jadwal & Agenda</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ps-3 pe-3 mt-2 mb-4" action="{{ route('schedules.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="add_date" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" name="date" id="add_date" placeholder="Tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_time" class="form-label">Jam</label>
                            <div class="input-group">
                            <input type="text" class="form-control" name="time_start" id="add_time_start" placeholder="Jam Awal" required>
                            <input type="text" class="form-control" name="time_end" id="add_time_end" placeholder="Jam Akhir" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add_description" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="description" id="add_description" placeholder="Keterangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_class" class="form-label">Warna</label>
                            <select class="form-select" name="class" id="add_class" required>
                                <option style="background-color: #ffffff; color: black;" selected disabled>Pilih Warna</option>
                                <option value="#d4e5e3" style="background-color: #d4e5e3; color: black;">Warna 1</option>
                                <option value="#ead8e1" style="background-color: #ead8e1; color: black;">Warna 2</option>
                                <option value="#fbd6db" style="background-color: #fbd6db; color: black;">Warna 3</option>
                                <option value="#fdead9" style="background-color: #fdead9; color: black;">Warna 4</option>
                                <option value="#e3f1f9" style="background-color: #e3f1f9; color: black;">Warna 5</option>
                                <option value="#ced4da" style="background-color: #ced4da; color: black;">Warna 6</option>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary" type="submit">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-modal" class="modal modal-lg fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title text-uppercase" id="dark-header-modalLabel">Ubah Data Jadwal & Agenda</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ps-3 pe-3 mt-2 mb-4" action="#" method="POST" id="editForm">
                        @method('PATCH')
                        @csrf
                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" name="date" id="edit_date" placeholder="Tanggal">
                        </div>
                        <div class="mb-3">
                            <label for="edit_time" class="form-label">Jam</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="time_start" id="edit_time_start" placeholder="Jam Awal">
                                <input type="text" class="form-control" name="time_end" id="edit_time_end" placeholder="Jam Akhir">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="description" id="edit_description" placeholder="Keterangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class" class="form-label">Warna</label>
                            <select class="form-select" name="class" id="edit_class">
                                <option style="background-color: #ffffff; color: black;" selected disabled>Pilih Warna</option>
                                <option value="#d4e5e3" style="background-color: #d4e5e3; color: black;">Warna 1</option>
                                <option value="#ead8e1" style="background-color: #ead8e1; color: black;">Warna 2</option>
                                <option value="#fbd6db" style="background-color: #fbd6db; color: black;">Warna 3</option>
                                <option value="#fdead9" style="background-color: #fdead9; color: black;">Warna 4</option>
                                <option value="#e3f1f9" style="background-color: #e3f1f9; color: black;">Warna 5</option>
                                <option value="#ced4da" style="background-color: #ced4da; color: black;">Warna 6</option>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary" type="submit">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/libs/flatpickr/l10n/id.js') }}"></script>
        <script>
            const colorSelect = document.getElementById('add_class');
            colorSelect.addEventListener('change', function() {
                colorSelect.style.backgroundColor = colorSelect.value;
            });

            $(document).on('click', '.sc-add', function () {
                let maxDate = $(this).data('max-date');
                flatpickr("#add_date", {
                    altInput: true,
                    locale: "id",
                    altFormat: "j F Y",
                    dateFormat: "Y-m-d",
                    minDate: maxDate,
                    defaultDate: maxDate,
                    disable: [
                        function(date) {
                            return (date.getDay() === 0);
                        }
                    ],
                }),
                flatpickr("#add_time_start", {
                    noCalendar: !0,
                    enableTime: !0,
                    time_24hr: !0,
                    locale: "id",
                    dateFormat: "H:i",
                    minTime: "07:00",
                    maxTime: "17:00",
                    defaultDate: "07:00"
                }),
                flatpickr("#add_time_end", {
                    noCalendar: !0,
                    enableTime: !0,
                    time_24hr: !0,
                    locale: "id",
                    dateFormat: "H:i",
                    minTime: "07:00",
                    maxTime: "17:00",
                    defaultDate: "07:00"
                }),$(document).off(".datepicker.data-api");
            });
        </script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
    @endpush
</x-app-layout>
