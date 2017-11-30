<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Relation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RelationController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'subject_id'    =>  'required|integer|min:1',
            'character_id'  =>  'integer|min:1',
            'prop_id'       =>  'required|integer|min:1|exists:props,id'
        ]);

        $relation = Relation::where(['subject_id' => $request->input('subject_id'), 'prop_id' => $request->input('prop_id')]);
        if (!empty($request->input('character_id'))) {
            $relation = $relation->where('character_id', $request->input('character_id'));
        } else {
            $relation = $relation->whereNull('character_id');
        }
        $relation = $relation->first();

        if (empty($relation)) {
            $relation = Relation::create([
                'referer'       =>  $request->input('referer'),
                'subject_id'    =>  $request->input('subject_id'),
                'character_id'  =>  $request->input('character_id'),
                'prop_id'       =>  $request->input('prop_id'),
            ]);
        }

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'referer'       =>  'required|string|in:bgm,mal',
            'subject_id'    =>  'required|integer|min:1',
            'character_id'  =>  'integer|min:1',
            'prop_id'       =>  'required|integer|min:1'
        ]);

        $relation = Relation::where(['subject_id' => $request->input('subject_id'), 'prop_id' => $request->input('prop_id')]);
        if (!empty($request->input('character_id'))) {
            $relation = $relation->where('character_id', $request->input('character_id'));
        } else {
            $relation = $relation->whereNull('character_id');
        }
        $relation = $relation->first();
        $relation->delete();

        return $this->response->noContent();
    }
}
