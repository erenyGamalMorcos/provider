<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    public $files_parameters = array(
        array(
            'file_name'  => 'DataProviderX',
            'amount'     => 'parentAmount',
            'currency'   => 'Currency',
            'status'     => 'statusCode',
            'date'       => 'registerationDate',
            'identifier' => 'parentIdentification',
            'code'       => array('authorised' => 1, 'decline'=> 2, 'refunded' => 3)
        ),
        array(
            'file_name'  => 'DataProviderY',
            'amount'     => 'balance',
            'currency'   => 'currency',
            'status'     => 'status',
            'date'       => 'created_at',
            'identifier' => 'id',
            'code'       => array('authorised' => 100, 'decline'=> 200, 'refunded' => 300)
        )
    );

    public function readJsonContent()
    {
        $result = [];
        foreach ($this->files_parameters as $file_parameter) {
            $path = storage_path() . "/json/{$file_parameter['file_name']}.json";
            $json_content = json_decode(file_get_contents($path), true);
            foreach ($json_content as $key => $row) {
                /** build my array to be able to filter it*/
                $result[] = array(
                    'file_name' => $file_parameter['file_name'],
                    'amount' => $row[$file_parameter['amount']],
                    'currency' => $row[$file_parameter['currency']],
                    'status_name' => array_search($row[$file_parameter['status']], $file_parameter['code']),
                );
            }
        }

        return $result;
    }
}
