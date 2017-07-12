<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LangData;

class LangController extends Controller
{
    /**
     * Выводим шаблон для DataTables редактора языков приложения
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.langs.index', [
                'langs' => config('qwertcms.locales'),
            ]);
    }
    
    /**
     * Отдает все языковые строки по модели db и uid = app
     * @param  Request $request 
     * @return JSON 
     */
    public function ajax(Request $request)
    {
        $langs = [];
        $ret = ['data' => [], 'options' => [], 'files' => []];
        $dbLangs = LangData::where('model', 'db')
                            // ->where('uid', 'app')
                            ->get();
        if ($dbLangs) {
            foreach ($dbLangs as $row) {
                $data = json_decode($row->json, true);
                foreach ($data as $key => $value) {
                    $langs[$key][$row->lang] = $value;
                    $langs[$key]['ids'][] = $row->id;
                }
            }
        }

        $iter = 1;
        foreach ($langs as $key => $langAr) {
            $tmpAr = [
                'DT_RowId' => 'row_' . $langAr['ids'][0],
                'key' => $key,
                'ids' => $langAr['ids'][0],
            ];

            foreach (config('qwertcms.locales') as $lKey => $lName) {
                if ($lKey == 'ids') continue;
                if (isset($langAr[$lKey])) {
                    $tmpAr[$lKey] = $langAr[$lKey];
                } else {
                    $tmpAr[$lKey] = '';
                }
            }
            $ret['data'][] = $tmpAr;
            unset($tmpAr);
            $iter++;
        }

        return response()->json($ret);
    }

    /**
     * Метод-диспетчер
     * @param  Request $request 
     * @return [type]           [description]
     */
    public function saveState(Request $request)
    {
        switch ($request->action) {
            case 'create':
                return $this->create($request);
            break;
            
            case 'edit':
                return $this->update($request);
            break;
            
            case 'remove':
                return $this->delete($request);
            break;
            
            default:
                return response()->json(['status' => 'error', 'message' => 'Unknown action']);
            break;
        }
    }

    public function create(Request $request)
    {
        $data = $request->data[0];
        $tmpAr = [
            // 'DT_RowId' => 'row_' . $newId,
            'key' => $data['key'],
        ];

        foreach ($data as $key => $value) {
            if ($key == 'key') continue;

            $lData = LangData::updateOrCreate(
                ['lang' => $key, 'model' => 'db', 'uid' => $data['key']],
                ['json' => json_encode([$data['key'] => $value,]), 'uid' => $data['key']]
            );
            $tmpAr[$key] = $value;
        }

        $tmpAr['DT_RowId'] = 'row_' . $lData->id;
        return response()->json(['data' => [$tmpAr,]]);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        /*
        action    "edit"
        data[row_15][key]   "21"
        data[row_15][en]    "243"
        */
        $saveLang = false;
        $saveKey = false;
        foreach ($data as $rowKey => $rowAr) {
            list($nn, $oneId) = explode('_', $rowKey);
            foreach ($rowAr as $key => $value) {
                if ($key == 'key') {
                    $saveKey = $value;
                } else {
                    $saveLang = $key;
                    $saveVal = $value;
                }
            }
        }

        // получаем по id любую из записей 
        $etalonRow = LangData::find($oneId);
        if (!$etalonRow) {
            return response()->json(['status' => 'error', 'message' => 'Unknown row']);
        }

        /**
         * @todo  могут приходить множественные правки из модального окна!
         */
        $etalonKey = $etalonRow->uid;

        $targetRows = LangData::where('uid', $etalonKey)
                                ->where('model', 'db')
                                ->get();

        if ($saveKey && $etalonKey != $saveKey) {
            // редактирование ключевого поля
            foreach ($targetRows as $targetRow) {
                $targetRow->uid = $saveKey;
                $json = json_decode($targetRow->json, true);
                $json[$saveKey] = $json[$etalonKey];
                unset($json[$etalonKey]);
                $targetRow->json = json_encode($json);
                $targetRow->save();
                $tmpAr[$targetRow->lang] = $json[$saveKey];
            }
            $tmpAr['key'] = $saveKey;
            $etalonKey = $saveKey;
        }  

        if ($saveLang) {
            // редактирование языков
            foreach ($targetRows as $targetRow) {
                if ($targetRow->lang == $saveLang) {
                    $targetRow->json = json_encode([$targetRow->uid => $saveVal]);
                    $targetRow->save();
                }
                $tmpAr[$targetRow->lang] = json_decode($targetRow->json, true)[$etalonKey];
            }
            $tmpAr['key'] = $etalonKey;
        } 
        // else {
        //     return response()->json(['status' => 'error', 'message' => 'Unknown edit']);
        // }

        $tmpAr['DT_RowId'] = 'row_' . $oneId;
        

        return response()->json(['data' => [$tmpAr,]]);
    }

    public function delete(Request $request)
    {
        $data = $request->data;
        /*
        action  "remove"
        data[row_12][DT_RowId]  "row_12"
        data[row_12][key]   "12"
        data[row_12][ids]   "12"
        data[row_12][ru]    "13"
        data[row_12][ua]    "14"
        data[row_12][en]    "15"
        */
        foreach ($data as $rowKey => $rowAr) {
            list($nn, $oneId) = explode('_', $rowKey);
            // foreach ($rowAr as $key => $value) {
            //     if ($key == 'key') {
            //         $saveKey = $value;
            //     } else {
            //         $saveLang = $key;
            //         $saveVal = $value;
            //     }
            // }
        }

        // получаем по id любую из записей 
        $etalonRow = LangData::find($oneId);
        if (!$etalonRow) {
            return response()->json(['status' => 'error', 'message' => 'Unknown row']);
        }

        $etalonKey = $etalonRow->uid;
        LangData::where('model', 'db')
                ->where('uid', $etalonKey)
                ->delete();
        return response()->json([[],]);
    }
}
