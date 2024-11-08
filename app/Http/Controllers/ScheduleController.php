<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function redirect;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'schedules' => Schedule::where('start_time', '>=', Carbon::today())->get()
        ]);
    }

    public function fullCalendar()
    {
        $events = [];

        $schedules = Schedule::all();

        foreach ($schedules as $schedule) {
            $events[] = [
                'title' => $schedule->description,
                'start' => $schedule->start_time,
                'end' => $schedule->end_time,
                'className' => $schedule->class
            ];
        }

        return view('calendar.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ucode' => 'nullable',
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'description' => 'required|string|max:255',
            'class' => 'required|string',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal harus berupa format tanggal.',
            'time_start.required' => 'Jam mulai harus diisi.',
            'time_start.date_format' => 'Jam mulai harus berupa format HH:MM.',
            'time_end.required' => 'Jam selesai harus diisi.',
            'time_end.date_format' => 'Jam selesai harus berupa format HH:MM.',
            'time_end.after' => 'Jam selesai harus setelah jam mulai.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.string' => 'Deskripsi harus berupa string.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'class.required' => 'Warna harus diisi.',
            'class.string' => 'Warna harus berupa string.',
        ]);

        // Check if the day already has 3 schedules
        $scheduleCount = Schedule::whereDate('start_time', $validatedData['date'])->count();
        if ($scheduleCount >= 3) {
            return redirect()->back()->withErrors(['error' => 'Hari Yang Dipilih Sudah Memiliki 3 Jadwal.']);
        }

        // Check for schedule collision
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validatedData['date'] . ' ' . $validatedData['time_start']);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $validatedData['date'] . ' ' . $validatedData['time_end']);

        $collidingSchedules = Schedule::whereDate('start_time', $startTime->toDateString())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        if ($collidingSchedules) {
            return redirect()->back()->withErrors(['error' => 'Jam Jadwal Bentrok Dengan Jadwal Yang Sudah Ada']);
        }

        do {
            $validatedData['ucode'] = Str::random(20);
            $ucodeCheck = Schedule::where('ucode', $validatedData['ucode'])->exists();
        } while ($ucodeCheck);

        $validatedData['start_time'] = $startTime;
        $validatedData['end_time'] = $endTime;

        if($validatedData['class'] === '#d4e5e3') {
            $validatedData['class'] = 'event-primary border-primary';
        }elseif($validatedData['class'] === '#ead8e1'){
            $validatedData['class'] = 'event-secondary border-secondary';
        }elseif($validatedData['class'] === '#fbd6db'){
            $validatedData['class'] = 'event-danger border-danger';
        }elseif($validatedData['class'] === '#fdead9') {
            $validatedData['class'] = 'event-warning border-warning';
        }elseif($validatedData['class'] === '#e3f1f9') {
            $validatedData['class'] = 'event-info border-info';
        }else{
            $validatedData['class'] = 'event-dark border-dark';
        }

        $schedule = [
            'ucode' => $validatedData['ucode'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'description' => $validatedData['description'],
            'class' => $validatedData['class']
        ];
        Schedule::create($schedule);

        return redirect()->route('dashboard')->with('success', 'Data Jadwal & Agenda Berhasil Ditambahkan');
    }

    public function show(Schedule $schedule)
    {
        $data = Schedule::where('ucode', $schedule->ucode)->first();
        return response()->json($data);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'description' => 'required|string|max:255',
            'class' => 'required|string',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal harus berupa format tanggal.',
            'time_start.required' => 'Jam mulai harus diisi.',
            'time_start.date_format' => 'Jam mulai harus berupa format HH:MM.',
            'time_end.required' => 'Jam selesai harus diisi.',
            'time_end.date_format' => 'Jam selesai harus berupa format HH:MM.',
            'time_end.after' => 'Jam selesai harus setelah jam mulai.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.string' => 'Deskripsi harus berupa string.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'class.required' => 'Warna harus diisi.',
            'class.string' => 'Warna harus berupa string.',
        ]);

        // Check if the day already has 3 schedules and check for schedule collision if the date is changed
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validatedData['date'] . ' ' . $validatedData['time_start']);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $validatedData['date'] . ' ' . $validatedData['time_end']);

        if ($schedule->start_time->toDateString() !== $startTime->toDateString()) {
            $scheduleCount = Schedule::whereDate('start_time', $startTime->toDateString())->count();
            if ($scheduleCount >= 3) {
                return redirect()->back()->withErrors(['error' => 'Hari Yang Dipilih Sudah Memiliki 3 Jadwal.']);
            }

            $collidingSchedules = Schedule::whereDate('start_time', $startTime->toDateString())
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<', $endTime)
                            ->where('end_time', '>', $startTime);
                    });
                })
                ->exists();

            if ($collidingSchedules) {
                return redirect()->back()->withErrors(['error' => 'Jam Jadwal Bentrok Dengan Jadwal Yang Sudah Ada']);
            }
        }

        $validatedData['start_time'] = $startTime;
        $validatedData['end_time'] = $endTime;

        if($validatedData['class'] === '#d4e5e3') {
            $validatedData['class'] = 'event-primary border-primary';
        }elseif($validatedData['class'] === '#ead8e1'){
            $validatedData['class'] = 'event-secondary border-secondary';
        }elseif($validatedData['class'] === '#fbd6db'){
            $validatedData['class'] = 'event-danger border-danger';
        }elseif($validatedData['class'] === '#fdead9') {
            $validatedData['class'] = 'event-warning border-warning';
        }elseif($validatedData['class'] === '#e3f1f9') {
            $validatedData['class'] = 'event-info border-info';
        }else{
            $validatedData['class'] = 'event-dark border-dark';
        }

        $schedule = [
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'description' => $validatedData['description'],
            'class' => $validatedData['class']
        ];
        $schedule->update($schedule);

        return redirect()->route('dashboard')->with('success', 'Data Jadwal & Agenda Berhasil Diubah');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('dashboard')->with('success', 'Data Jadwal & Agenda Berhasil Dihapus');
    }
}
