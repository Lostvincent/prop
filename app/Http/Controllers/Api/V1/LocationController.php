<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Transformers\DataTransformer;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'string|in:bgm,mal',
            'subject_id'    =>  'integer|min:1',
            'ep_id'         =>  'integer|min:1',
            'prop_id'       =>  'integer|min:1'
        ]);

        $search = array_filter($request->only('subject_id', 'ep_id', 'prop_id'), function ($var) {
            return !empty($var);
        });

        $locations = Location::where(['referer' => !empty($request->input('referer')) ? $request->input('referer') : 'bgm']);
        foreach ($search as $field => $value) {
            $locations = $locations->where($field, $value);
        }
        $locations = $locations->orderBy('id', 'DESC')->paginate(20);

        return $this->response()->paginator($locations, new DataTransformer);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'subject_id'    =>  'required|integer|min:1',
            'ep_id'         =>  'required|integer|min:1',
            'prop_id'       =>  'required|integer|min:1|exists:props,id',
            'min'           =>  'required|integer|min:0|max:255',
            'sec'           =>  'required|integer|min:0|max:60',
            'length'        =>  'required|integer|min:1',
            'image'         =>  'required|image'
        ]);

        $this->check($request);

        $file = $request->file('image');
        if (!$file->isValid()) {
            throw new ValidationHttpException(['image' => 'Cover image invalid.']);
        }
        $path = with(new ImageService($file, 250))->save();

        $location = Location::create([
            'referer'       =>  $request->input('referer'),
            'subject_id'    =>  $request->input('subject_id'),
            'ep_id'         =>  $request->input('ep_id'),
            'prop_id'       =>  $request->input('prop_id'),
            'min'           =>  $request->input('min'),
            'sec'           =>  $request->input('sec'),
            'length'        =>  $request->input('length'),
            'image'         =>  $path
        ]);

        return $this->response->array(['data' => $location])->setStatusCode(201);
    }

    public function update(Request $request, $location_id)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'min'           =>  'required|integer|min:0|max:255',
            'sec'           =>  'required|integer|min:0|max:60',
            'length'        =>  'required|integer|min:1',
            'image'         =>  'image'
        ]);

        $location = Location::findOrFail($location_id);

        $this->check($request, $location);

        if (!empty($request->file('image'))) {
            $file = $request->file('image');
            if (!$file->isValid()) {
                throw new ValidationHttpException(['image' => 'Cover image invalid.']);
            }
            $path = with(new ImageService($file, 250))->save();
        }

        $location->update([
            'min'       =>  $request->input('min'),
            'sec'       =>  $request->input('sec'),
            'length'    =>  $request->input('length'),
            'image'     =>  !empty($path) ? $path : $location->getOriginal('image'),
        ]);

        return $this->response->array(['data' => $location])->setStatusCode(201);
    }

    public function destroy(Request $request, $location_id)
    {
        $location = Location::findOrFail($location_id);
        // code...
        $location->delete();

        return $this->response->noContent();
    }

    public function getBar(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'string|in:bgm,mal',
            'subject_id'    =>  'required|integer|min:1',
            'ep_id'         =>  'required|integer|min:1',
            'prop_id'       =>  'required|integer|min:1'
        ]);

        $locations = Location::where(['referer' => !empty($request->input('referer')) ? $request->input('referer') : 'bgm']);
        foreach (['subject_id', 'ep_id', 'prop_id'] as $field) {
            $locations = $locations->where($field, $request->input($field));
        }
        $locations = $locations->get();

        return $this->response->array(['data' => $locations]);
    }

    private function check(Request $request, $location = null)
    {
        $exists = Location::where([
            'referer'       =>  !empty($location) ? $location->referer : $request->input('referer'),
            'subject_id'    =>  !empty($location) ? $location->subject_id : $request->input('subject_id'),
            'ep_id'         =>  !empty($location) ? $location->ep_id : $request->input('ep_id'),
            'prop_id'       =>  !empty($location) ? $location->prop_id : $request->input('prop_id'),
        ])->get()->keyBy('id');

        if (!empty($location)) {
            unset($exists[$location->id]);
        }

        $point_min = $request->input('min') * 60 + $request->input('sec');
        $point_max = $point_min + $request->input('length');

        // check if in ep range (in future, maybe...)

        foreach ($exists as $exist) {
            $loc_min = $exist->min * 60 + $exist->sec;
            $loc_max = $loc_min + $exist->length;
            if (!($point_min > $loc_max || $point_max < $loc_min)) {
                return $this->response->errorForbidden('Duplicate or cross location.');
            }
        }
    }
}
