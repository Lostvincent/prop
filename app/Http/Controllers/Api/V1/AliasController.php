<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Prop;
use App\Models\Alias;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AliasController extends Controller
{
    public function store(Request $request, $prop_id)
    {
        $this->validate($request, [
            'name'  =>  'required|string',
            'lang'  =>  'string'
        ]);

        $prop = Prop::findOrFail($prop_id);
        $exist = Alias::where(['prop_id' => $prop->id, 'name' => $request->input('name'), 'lang' => $request->input('lang', 'cn')])->first();

        if (!empty($exist)) {
            return $this->response->errorForbidden('Duplicate alias.');
        }

        $alias = Alias::create([
            'prop_id'   =>  $prop->id,
            'name'      =>  $request->input('name'),
            'lang'      =>  $request->input('lang', 'cn')
        ]);

        return $this->response()->array(['data' => $alias])->setStatusCode(201);
    }

    public function destroy(Request $request, $prop_id, $alias_id)
    {
        $prop = Prop::findOrFail($prop_id);
        $alias = Alias::where(['id' => $alias_id, 'prop_id' => $prop->id])->first();
        // code...
        $alias->delete();

        return $this->response->noContent();
    }
}
