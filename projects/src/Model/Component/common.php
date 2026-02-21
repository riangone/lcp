<?php

namespace App\Model\Component;

// use Cake\Log\Log;

class common
{
    public function getJSONData($filename)
    {
        // $filepath = dirname(__DIR__).$filename;
        // $handle = fopen($filepath, 'r');
        // $contents = fread($handle, filesize($filepath));
        // fclose($handle);
        // $file = new Filesystem(dirname(__DIR__) . $filename);
        $json = file_get_contents(dirname(__DIR__) . $filename);
        // Log::debug(gettype($json));
        $contents = json_decode($json, true);

        return $contents;
    }

    public function searchArray($tbl, $search, $tblCol)
    {
        // require_once dirname(dirname(__DIR__)).'/Vendor/Ginq.php';
        // $xs = Ginq::from($tbl)->where(function ($v, $k) use ($search, $tblCol) {
        //     return $v[$tblCol] == $search;
        // })->toArray();
        // Log::debug(gettype($tbl));
        $returnVal = '';
        foreach ($tbl as $value) {
            if ($value[$tblCol] == $search) {
                $returnVal = $value;
            }
        }

        // if (0 != count($xs)) {
        //     $index = array_keys($xs);
        //     $returnVal = $xs[$index[0]];
        // }

        return $returnVal;
    }
}
