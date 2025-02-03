<?php
namespace App\Http\Controllers;

use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCarouselRequest;
use App\Helpers\FileHelper;

class CarouselController extends Controller
{
    /**
     * Display a listing of the carousel images.
     */
    public function index()
    {
           $carousels = Carousel::all();

        $carousels->each(function ($carousel) {
        $carousel->image = url('storage/' . str_replace('storage/', '', $carousel->image));
    });

    return $this->successResponse(['carousels' => $carousels]);
    }

    /**
     * Store a newly uploaded carousel image.
     */
    public function store(StoreCarouselRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = FileHelper::saveFile($request->validated('image'), 'carousels');
            $validated['image'] = 'storage/' . $path;
        }

        $carousel = Carousel::create($validated);

        return $this->successResponse([
            'message' => 'Carousel image uploaded successfully',
            'carousel' => $carousel
        ], 201);
    }

    /**
     * Display the specified carousel image.
     */
    public function show(Carousel $carousel)
    {
        return $this->successResponse(['carousel' => $carousel]);
    }

    /**
     * Remove the specified carousel image from storage.
     */
    public function destroy($id)
{
    $carousel = Carousel::find($id);

    if (!$carousel) {
        return response()->json(['error' => 'Carousel not found'], 404);
    }

    Storage::disk('public')->delete(str_replace('storage/', '', $carousel->image));
    $carousel->delete();

    return $this->successResponse(['message' => 'Carousel image deleted successfully']);
}

    /**
     * Common success response format.
     */
    protected function successResponse($data, int $status = 200)
    {
        return response()->json($data, $status);
    }
}
