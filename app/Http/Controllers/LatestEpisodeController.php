<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLatestEpisodeRequest;
use App\Models\LatestEpisode;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Traits\ApiResponses;

class LatestEpisodeController extends Controller
{
    use ApiResponses;
    private $storage_path = 'LatestEpisode/thumbnailPath';

    public function index(Request $request)
    {
        if ($request->has('perPage') && is_numeric($request->perPage)) {

        $latestEpisode = LatestEpisode::paginate($request->perPage);
        }
        else {
            $latestEpisode = LatestEpisode::all();

        }
        $latestEpisode->load('show');

        $latestEpisode->each(function ($item) {
            $item->thumbnailPath = url('' . $item->thumbnailPath);
        });
       
        return $this->successResponse(['latestEpisode' => $latestEpisode]);
    }

    public function store(StoreLatestEpisodeRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
            $path = FileHelper::saveFile($request->validated('thumbnailPath'), $this->storage_path);
            $validated['thumbnailPath'] = 'storage/' . $path;
        }
        $news = LatestEpisode::create($validated);
        return $this->successResponse(['message' => 'Latest Episode Created succssefuly' , 'latest-episode' => $news]);
    
    }


    public function show(string $id)
    {
        $latestEpisode = LatestEpisode::find($id);
        return $this->successResponse(['latest-episode' => $latestEpisode]);
    }


    public function update(StoreLatestEpisodeRequest $request, string $id)
    {
        $validated = $request->validated();

        $latestEpisode = LatestEpisode::findOrFail($id);
    
        if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
            $path = FileHelper::updateFile($request->file('thumbnailPath'), $this->storage_path);
            $validated['thumbnailPath'] = 'storage/' . $path;
        }
    
        $latestEpisode->update($validated);
    
        return $this->successResponse(['message' => 'Latest Episode Updated successfully', 'latest-episode' => $latestEpisode]);
       
    }


    public function destroy(string $id)
    {
        $latestEpisode = LatestEpisode::findOrFail($id);
        $latestEpisode->delete();
        return $this->successResponse(['message' => 'Latest Episode deleted successfully' , 'latest-episode' => $latestEpisode]);
       
    }
}
