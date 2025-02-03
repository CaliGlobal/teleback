<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsCastRequest;
use App\Traits\ApiResponses;
use App\Helpers\FileHelper;

use App\Models\NewsCast;
use Illuminate\Http\Request;

class NewsCastController extends Controller
{
    use ApiResponses;

    private $storage_path = 'NewsCast/thumbnailPath';
    public function index(Request $request)
    {

    
       if ($request->has('perPage') && is_numeric($request->perPage)) {

        $NewsCast = NewsCast::paginate($request->perPage);
       }
       else {
        $NewsCast = NewsCast::all();
       }

       $NewsCast->each(function ($item) {
        $item->thumbnailPath = url('' . $item->thumbnailPath);
    });

        return $this->successResponse(['news' => $NewsCast]);
    }

 
    public function store(StoreNewsCastRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
            $path = FileHelper::saveFile($request->validated('thumbnailPath'), $this->storage_path);
            $validated['thumbnailPath'] = 'storage/' . $path;
        }
        $news = NewsCast::create($validated);
        return $this->successResponse(['message' => 'News Cast Created succssefuly' , 'news-cast' => $news]);
    
    }


    public function show(string $id)
    {
        $NewsCast = NewsCast::find($id);
        return $this->successResponse(['news-cast' => $NewsCast]);
    }




    public function update(StoreNewsCastRequest $request, string $id)
    {

        $validated = $request->validated();

        $NewsCast = NewsCast::findOrFail($id);
    
        if ($request->hasFile('thumbnailPath') && $request->file('thumbnailPath')->isValid()) {
            $path = FileHelper::updateFile($request->file('thumbnailPath'), $this->storage_path);
            $validated['thumbnailPath'] = 'storage/' . $path;
        }
    
        $NewsCast->update($validated);
    
        return $this->successResponse(['message' => 'News Cast Updated successfully', 'news-cast' => $NewsCast]);
       
    }


    public function destroy(string $id)
    {

        $NewsCast = NewsCast::findOrFail($id);
        $NewsCast->delete();
        return $this->successResponse(['message' => 'News Cast deleted successfully' , 'news-cast' => $NewsCast]);
       
    }
}
