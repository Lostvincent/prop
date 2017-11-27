<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'subject_id'    =>  'required|integer|min:0',
            'ep_id'         =>  'required|integer|min:0',
            'prop_id'       =>  'required|integer|min:0|exists:props,id',
            'min'           =>  'required|integer|min:0|max:255',
            'sec'           =>  'required|integer|min:0|max:60',
            'length'        =>  'required|integer|min:0',
        ]);

        $this->check($request);

        $location = Location::create([
            'referer'       =>  $request->input('referer'),
            'subject_id'    =>  $request->input('subject_id'),
            'ep_id'         =>  $request->input('ep_id'),
            'min'           =>  $request->input('min'),
            'sec'           =>  $request->input('sec'),
            'length'        =>  $request->input('length'),
        ]);

        return $this->response()->array(['data' => $location])->setStatusCode(201);
    }

    public function update(Request $request, $location_id)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'min'           =>  'required|integer|min:0|max:255',
            'sec'           =>  'required|integer|min:0|max:60',
            'length'        =>  'required|integer|min:0',
        ]);

        $location = Location::findOrFail($location_id);

        $this->check($request, $location);

        $location->update([
            'min'       =>  $request->input('min'),
            'sec'       =>  $request->input('sec'),
            'length'    =>  $request->input('length'),
        ]);

        return $this->response()->array(['data' => $location])->setStatusCode(201);
    }

    public function destroy(Request $request, $location_id)
    {
        Location::where('id', $location_id)->delete();

        return $this->response->noContent();
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
