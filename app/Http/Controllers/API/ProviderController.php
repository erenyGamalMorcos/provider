<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;


class ProviderController extends Controller
{
    public $final_result = [];
    public function getResults(Request $request){
        try{
            $provider = new Provider();
            $all_data = $provider->readJsonContent();
            if(! count ($request->all())){
                $this->final_result = collect($all_data)->all();
            }else{
                $this->filterByQueryStrings($request, $all_data);
            }

        }catch (\Exception $e){
            return response()->json(['message' => 'error reading files'], 400);
        }
        return response()->json(['message' => 'success', 'data' => $this->final_result], 200);

    }

    private function filterByQueryStrings($request, $all_data){
        $collection = collect($all_data);
        $filter = $collection->map(function ($item, $key) use ($request){
            if ($request->has('provider') && $item['file_name'] == $request->query('provider')) {
                return $item ;
            }
            if ($request->has('statusCode') && $item['status_name'] == $request->query('statusCode')) {
                return $item ;
            }
            if ($request->has('currency') && $item['currency'] == $request->query('currency')) {
                return $item ;
            }
            if ($request->has('balanceMin') && $request->has('balanceMax') &&
                $item['amount'] >= $request->query('balanceMin') &&
                $item['amount'] <= $request->query('balanceMax')) {
                return $item ;
            }
        });
        $this->final_result = $filter->reject(null);
    }

}
