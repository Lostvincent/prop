<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Prop;
use App\Models\Alias;
use App\Models\Location;
use App\Models\Relation;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;

class PropController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'string|in:bgm,mal',
            'subject_id'    =>  'integer|min:1',
            'character_id'  =>  'required_without:subject_id|integer|min:1',
        ]);

        $relations = Relation::where(['referer' => !empty($request->input('referer')) ? $request->input('referer') : 'bgm']);
        foreach (['subject_id', 'character_id'] as $field) {
            if (!empty($request->input($field))) {
                $relations = $relations->where($field, $request->input($field));
            }
        }
        $relations = $relations->pluck('prop_id');

        $props = [];
        if (!empty($relations)) {
            $props = Prop::whereIn('id', $relations)->orderBy('id', 'desc')->get()->keyBy('id');

            $points = [];
            if (!empty($request->input('subject_id'))) {
                $locations = Location::where(['referer' => !empty($request->input('referer')) ? $request->input('referer') : 'bgm', 'subject_id' => $request->input('subject_id')])->whereIn('prop_id', $props->pluck('id'))->get();
            }
            foreach ($locations as $location) {
                if (empty($points[$location->prop_id][$location->ep_id])) {
                    $points[$location->prop_id][$location->ep_id] = 0;
                }
                $points[$location->prop_id][$location->ep_id] ++;
            }
            foreach ($points as $prop_id => $point) {
                $props[$prop_id]['points'] = $point;
            }
            $props = $props->values();
        }

        return $this->response->array(['data' => $props]);
    }

    public function show(Request $request, $prop_id)
    {
        $this->validate($request, [
            'referer'   =>  'string|in:bgm,mal',
        ]);

        $prop = Prop::with('aliases')->findOrFail($prop_id);

        $locations = [];
        $raw_locations = Location::where(['referer' => !empty($request->input('referer')) ? $request->input('referer') : 'bgm', 'prop_id' => $prop->id])->get();
        foreach ($raw_locations as $raw_location) {
            if (!isset($locations[$raw_location['subject_id']])) {
                $locations[$raw_location['subject_id']] = [
                    'subject_id'    =>  $raw_location['subject_id'],
                    // 'name'          =>  '',
                    // 'cover'         =>  '',
                    'eps'           =>  []
                ];
            }

            if (!isset($locations[$raw_location['subject_id']]['eps'][$raw_location['ep_id']])) {
                $locations[$raw_location['subject_id']]['eps'][$raw_location['ep_id']] = [
                    'ep_id'     =>  $raw_location['ep_id'],
                    // 'name'      =>  '',
                    'points'    =>  []
                ];
            }

            $locations[$raw_location['subject_id']]['eps'][$raw_location['ep_id']]['points'][] = [
                'id'        =>  $raw_location['id'],
                'min'       =>  $raw_location['min'],
                'sec'       =>  $raw_location['sec'],
                'length'    =>  $raw_location['length'],
                'image'     =>  $raw_location['image'],
            ];
        }

        sort($locations);
        foreach ($locations as &$location) {
            sort($location['eps']);
        }

        $prop['locations'] = $locations;

        return $this->response->array(['data' => $prop]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'category_id'   =>  'required|integer|min:0|exists:categories,id',
            'name'          =>  'required|string',
            'alias'         =>  'array',
            'alias.*.name'  =>  'required_with:alias|string',
            'alias.*.lang'  =>  'string',
            'image'         =>  'required|image',
            'description'   =>  'string',
        ]);

        $file = $request->file('image');
        if (!$file->isValid()) {
            throw new ValidationHttpException(['image' => 'Cover image invalid.']);
        }
        $path = with(new ImageService($file, 250))->save();

        $prop = Prop::create([
            'category_id'   =>  0,
            'name'          =>  $request->input('name'),
            'image'         =>  $path,
            'description'   =>  $request->input('description'),
        ]);

        // add alias
        if (!empty($request->input('alias'))) {
            $new_alias = [];
            foreach ($request->input('alias') as $alias) {
                $new_alias[] = [
                    'prop_id'   =>  $prop->id,
                    'name'      =>  $alias['name'],
                    'lang'      =>  !empty($alias['lang']) ? $alias['lang'] : 'cn'
                ];
            }
            if (!empty($new_alias)) {
                Alias::insert($new_alias);
            }
        }

        // $relation = Relation::create([
        //     'referer'       =>  $request->input('referer'),
        //     'subject_id'    =>  $request->input('subject_id'),
        //     'character_id'  =>  $request->input('character_id', 0),
        //     'prop_id'       =>  $prop->id
        // ]);

        return $this->response()->array(['data' => $prop])->setStatusCode(201);
    }

    public function update(Request $request, $prop_id)
    {
        $this->validate($request, [
            // 'category_id'   =>  'required|integer|min:0|exists:categories,id',
            'name'          =>  'required|string',
            'description'   =>  'string'
        ]);

        $prop = Prop::findOrFail($prop_id);
        $prop->update([
            'name'          =>  $request->input('name'),
            'description'   =>  $request->input('description'),
        ]);

        return $this->response()->array(['data' => $prop])->setStatusCode(201);
    }

    public function destroy($prop_id)
    {
        $prop = Prop::findOrFail($prop_id);
        // do not delete data like relation, alias, etc
        $prop->delete();

        return $this->response->noContent();
    }

    public function updateCover(Request $request, $prop_id)
    {
        $this->validate($request, [
            'image' =>  'required|image',
        ]);

        $prop = Prop::findOrFail($prop_id);

        $file = $request->file('image');
        if (!$file->isValid()) {
            throw new ValidationHttpException(['image' => '封面图片格式不正确。']);
        }
        $path = with(new ImageService($file, 250))->save();
        with(new ImageService($prop->getOriginal('image')))->delete();

        $prop->update(['image' => $path]);

        return $this->response->array(['data' => ['path' => $prop->image]])->setStatusCode(201);
    }
}
