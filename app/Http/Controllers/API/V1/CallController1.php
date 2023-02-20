<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CallCreateRequest1;
use App\Http\Requests\API\V1\CallUpdateRequest1;
use App\Http\Resources\API\V1\CallResource1;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CallController1 extends Controller
{
    public function index(Request $request)
    {
        return CallResource1::collection(Call::all());
    }
    public function create(CallCreateRequest1 $request)
    {
        $call = Call::create($request->all());

        return $call ? $call->uuid : null; //FIXME: put in resource
    }
    public function read(Call $call): CallResource1
    {
        return new CallResource1($call);
    }
    public function update(Call $call, CallUpdateRequest1 $request)
    {
        $call->update($request->all());

        return response()->json(['message' => 'success by update'], Response::HTTP_OK);
    }
    public function delete(Call $call)
    {
        $call->delete();

        return response()->json(['message' => 'success by delete'], Response::HTTP_OK);
    }
}
