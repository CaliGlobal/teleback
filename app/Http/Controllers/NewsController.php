<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Http\Requests\StoreNewsRequest;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponses;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use ApiResponses;
    private $storage_path = 'News/thumbnailPath';

    public function index(Request $request)
    {
       if ($request->has('perPage') && is_numeric($request->perPage)) {
        $news = News::paginate($request->perPage);
    } else {
        $news = News::all();
    }

    $news->each(function ($item) {
        $item->thumbnailPath = url('' . $item->thumbnailPath);
    });

    return $this->successResponse(['news' => $news]);

    }

    public function newsflash() {

        $news = News::select('title')->get();
        return $this->successResponse(['news' => $news]);
    }


    public function store(StoreNewsRequest $request)
    {
    $validated = $request->validated();

    if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
        $path = FileHelper::saveFile($request->validated('thumbnailPath'), $this->storage_path);
        $validated['thumbnailPath'] = 'storage/' . $path;
    }
    $news = News::create($validated);
    return $this->successResponse(['message' => 'News Created succssefuly' , 'news' => $news]);

    }


    public function show(string $id)
    {

        $News = News::find($id);
        return $this->successResponse(['news' => $News]);
    }


    public function update(StoreNewsRequest $request, string $id)
    {
        $validated = $request->validated();

        $news = News::findOrFail($id);
    
        if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
            $path = FileHelper::updateFile($request->file('thumbnailPath'), $this->storage_path);
            $validated['thumbnailPath'] = 'storage/' . $path;
        }
    
        $news->update($validated);
    
        return $this->successResponse(['message' => 'News Updated successfully', 'news' => $news]);
    }


    public function destroy(string $id)
    {

    $news = News::findOrFail($id);
    $news->delete();
    return $this->successResponse(['message' => 'News deleted successfully' , 'news' => $news]);
     
    }
}
