<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Prop;
use App\Models\Alias;
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
            'subject_id'    =>  'required|integer|min:0',
            'character_id'  =>  'integer|min:0',
        ]);

        $relations = Relation::where(['referer' => $request->input('referer', 'bgm'), 'subject_id' => $request->input('subject_id')]);
        if (!empty($request->input('character_id'))) {
            $relations = $relations->where('character_id', $request->input('character_id'));
        }
        $relations = $relations->pluck('prop_id');

        $props = [];
        if (!empty($relations)) {
            $props = Prop::whereIn('id', $relations)->orderBy('id', 'desc')->get();
        }

        return $this->response->array(['data' => $props]);
    }

    public function show($prop_id)
    {
        $prop = Prop::with('aliases')->findOrFail($prop_id);

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
