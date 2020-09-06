<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TranslatorCotroller extends Controller
{
    public function index()
    {
        $rows = [];
        $results = scandir(resource_path() . '/lang/en');
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }
            $rows[] = substr($result, 0, -4);
        }
        return view('dashboard.translators.index', compact('rows'));
    }

    public function edit($id)
    {
        if (!is_array(trans($id))) {
            return abort(404);
        }
        foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)   {
            $rows[$localeCode] = trans($id, [], $localeCode);
        }
        return view('dashboard.translators.edit', compact('rows', 'id'));
    }

    public function update(Request $request, $id)
    {
        foreach(LaravelLocalization::getSupportedLocales() as $lang => $properties) {
            exec('chmod -R 0755 ' . resource_path() . '/lang/');
            $text = "<?php \n return [\n";
            foreach (request($lang) as $key => $value) {
                if (!File::exists(resource_path() . '/lang/' . $lang)) {
                    $mask = umask(0);
                    File::makeDirectory(resource_path() . '/lang/' . $lang . '/', 0755);
                    umask($mask);
                }
                $text .= "'{$key}' => '{$value}',\n";
            }

            $text .= "];";
            file_put_contents(resource_path() . '/lang/' . $lang . '/' . $id . '.php', $text);
            exec('chmod -R 0777 ' . resource_path() . '/lang/' . $lang . '/' . $id . '.php');
        }

        return redirect()->route('translators.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }
}
