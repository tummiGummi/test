<?php


namespace App\Http\Controllers;

use App\Jobs\ParseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function create()
    {
        return view('uploadFrom');
    }


    public function upload(Request $request)
    {
        $validator = \Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator, $this->buildFailedValidationResponse(
                $request, $this->formatValidationErrors($validator)
            ));
        }

        $fileName = Str::random(40) . '_' . $request->file->getClientOriginalName();
        $request->file->storeAs('files', $fileName);
        $job = (new ParseFile($fileName))->delay(10);
        $this->dispatch($job);
        return Redirect::back();
    }
}
