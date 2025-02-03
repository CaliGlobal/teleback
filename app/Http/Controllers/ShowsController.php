<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponses;
use App\Helpers\FileHelper;
use App\Http\Requests\StoreShowRequest;
use App\Models\Schedule;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowsController extends Controller
{
    protected $storage_path = 'shows/thumbnails'; // Define the directory where the thumbnail will be stored
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $shows = Show::with('schedules')->get();

      $shows->each(function ($show) {
          $show->logo_path = url('storage/' . str_replace('storage/', '', $show->logo_path));
      });
  
      return $this->successResponse([
          'message' => 'Shows and schedules retrieved successfully',
          'shows' => $shows
      ]);
    }

    public function getNearestPrograms()
    {
        // Get the current date and time
        $currentDateTime = now();
    
        // Get all shows and their schedules
        $shows = Show::with('schedules')->get();
    
        $nearestProgramsToday = [];
        $nearestProgramsFuture = [];
    
        // Iterate over each show and its schedules
        foreach ($shows as $show) {
            foreach ($show->schedules as $schedule) {
                // Handle daily frequency
                if ($schedule->frequency == 'daily') {
                    $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day);
    
                    // If it's for today and in the future
                    if ($programTime > $currentDateTime) {
                        $nearestProgramsToday[] = [
                            'show_name' => $show->name,
                            'program_time' => $programTime->toDateTimeString(),
                            'frequency' => $schedule->frequency,
                            'days' => null,
                            'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                        ];
                    }
                }
    
                // Handle weekly frequency
                elseif ($schedule->frequency == 'weekly') {
                    $currentDayOfWeek = $currentDateTime->format('l');
                    if (strtolower($schedule->day) == strtolower($currentDayOfWeek)) {
                        $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day);
    
                        // If it's today and after the current time
                        if ($programTime > $currentDateTime) {
                            $nearestProgramsToday[] = [
                                'show_name' => $show->name,
                                'program_time' => $programTime->toDateTimeString(),
                                'frequency' => $schedule->frequency,
                                'days' => null,
                                'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                            ];
                        }
                    }
                }
    
                // Handle specific days frequency
                elseif ($schedule->frequency == 'specific') {
                    $specificDays = $schedule->days;
                    foreach ($specificDays as $specificDay) {
                        if (strtolower($specificDay) == strtolower($currentDateTime->format('l'))) {
                            $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day);
                            
                            // If it's today and after the current time
                            if ($programTime > $currentDateTime) {
                                $nearestProgramsToday[] = [
                                    'show_name' => $show->name,
                                    'program_time' => $programTime->toDateTimeString(),
                                    'frequency' => $schedule->frequency,
                                    'days' => $schedule->days,
                                    'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                                ];
                            }
                        }
                    }
                }
            }
        }
    
        // If no nearest program is found for today, get the closest program for the future
        if (empty($nearestProgramsToday)) {
            // Get future programs (those scheduled for the next day or later)
            foreach ($shows as $show) {
                foreach ($show->schedules as $schedule) {
                    // Same logic applies for future programs (same frequency)
                    if ($schedule->frequency == 'daily') {
                        $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day)->addDay();
    
                        if ($programTime > $currentDateTime) {
                            $nearestProgramsFuture[] = [
                                'show_name' => $show->name,
                                'program_time' => $programTime->toDateTimeString(),
                                'frequency' => $schedule->frequency,
                                'days' => null,
                                'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                            ];
                        }
                    } elseif ($schedule->frequency == 'weekly') {
                        // Future weekly schedules
                        $currentDayOfWeek = $currentDateTime->format('l');
                        if (strtolower($schedule->day) != strtolower($currentDayOfWeek)) {
                            $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day);
    
                            if ($programTime > $currentDateTime) {
                                $nearestProgramsFuture[] = [
                                    'show_name' => $show->name,
                                    'program_time' => $programTime->toDateTimeString(),
                                    'frequency' => $schedule->frequency,
                                    'days' => null,
                                    'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                                ];
                            }
                        }
                    } elseif ($schedule->frequency == 'specific') {
                        // Future specific day schedules
                        foreach ($schedule->days as $specificDay) {
                            if (strtolower($specificDay) != strtolower($currentDateTime->format('l'))) {
                                $programTime = Carbon::createFromFormat('H:i:s', $schedule->time)->setDate($currentDateTime->year, $currentDateTime->month, $currentDateTime->day);
    
                                if ($programTime > $currentDateTime) {
                                    $nearestProgramsFuture[] = [
                                        'show_name' => $show->name,
                                        'program_time' => $programTime->toDateTimeString(),
                                        'frequency' => $schedule->frequency,
                                        'days' => $schedule->days,
                                        'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Include the logo path with the domain
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
    
        // Merge today and future programs and sort them by time
        $nearestPrograms = array_merge($nearestProgramsToday, $nearestProgramsFuture);
        usort($nearestPrograms, function ($a, $b) {
            return strtotime($a['program_time']) - strtotime($b['program_time']);
        });
    
        return $this->successResponse([
            'message' => 'Nearest programs retrieved successfully',
            'nearest_programs' => $nearestPrograms,
        ]);
    }
    
    public function getAllShowsWithSchedules(Request $request)
    {
        $now = now(); // Get current date and time
        $today = $now->format('l'); // Get current day (e.g., "Monday")
        $currentTime = $now->format('H:i:s'); // Get current time
    
        // Retrieve all shows with schedules
        $shows = Show::with('schedules')->get();
    
        // Map and sort shows based on the nearest program
        $formattedShows = $shows->map(function ($show) use ($today, $currentTime) {
            $nearestSchedule = $show->schedules->sortBy(function ($schedule) use ($today, $currentTime) {
                if ($schedule->frequency === 'daily') {
                    return strtotime($schedule->time) >= strtotime($currentTime) ? strtotime($schedule->time) : PHP_INT_MAX;
                }
    
                if ($schedule->frequency === 'weekly') {
                    return ($schedule->day === $today && strtotime($schedule->time) >= strtotime($currentTime)) 
                        ? strtotime($schedule->time) 
                        : PHP_INT_MAX;
                }
    
                if ($schedule->frequency === 'specific') {
                    return (in_array($today, (array) $schedule->days) && strtotime($schedule->time) >= strtotime($currentTime)) 
                        ? strtotime($schedule->time) 
                        : PHP_INT_MAX;
                }
    
                return PHP_INT_MAX;
            })->first();
    
            return [
                'id' => $show->id,
                'name' => $show->name,
                'description' => $show->description,
                'logo_path' => url('storage/' . str_replace('storage/', '', $show->logo_path)), // Full image URL
                'frequency' => $nearestSchedule ? $nearestSchedule->frequency : null,
                'nearest_schedule' => $nearestSchedule ? [
                    'id' => $nearestSchedule->id,
                    'day' => $nearestSchedule->day,
                    'days' => $nearestSchedule->days,
                    'time' => $nearestSchedule->time,
                ] : null,
            ];
        })->sortBy('nearest_schedule.time')->values();
    
        // Apply pagination if perPage is provided and is numeric
        if ($request->has('perPage') && is_numeric($request->perPage)) {
            $perPage = (int) $request->perPage;
    
            $paginatedShows = new \Illuminate\Pagination\LengthAwarePaginator(
                $formattedShows->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), $perPage),
                $formattedShows->count(),
                $perPage,
                \Illuminate\Pagination\Paginator::resolveCurrentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );
    
            return $this->successResponse([
                'message' => 'All shows sorted by nearest program (paginated)',
                'shows' => $paginatedShows,
            ]);
        }
    
        return $this->successResponse([
            'message' => 'All shows sorted by nearest program',
            'shows' => $formattedShows,
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShowRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            $path = FileHelper::saveFile($request->validated('logo_path'), $this->storage_path);
            $validated['logo_path'] = 'storage/' . $path; // Save the relative path in the database
        }

        // Create the Show record
        $show = Show::create($validated);

        // Create the schedule based on frequency
        $schedule = new Schedule();
        $schedule->show_id = $show->id;
        $schedule->frequency = $validated['schedule_frequency'];
        $schedule->time = $validated['schedule_time'];

        // Store days for specific frequency
        if ($validated['schedule_frequency'] == 'specific') {
            $schedule->days = $validated['specific_days'];
        }

        // Store the day for weekly frequency
        if ($validated['schedule_frequency'] == 'weekly') {
            $schedule->day = $validated['weekly_day'];
        }

        $schedule->save();

        return $this->successResponse([
            'message' => 'Show Created Successfully',
            'show' => $show,
            'schedule' => $schedule 
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
