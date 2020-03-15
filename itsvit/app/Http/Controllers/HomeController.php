<?php


namespace App\Http\Controllers;

use App\Jobs\ParseFile;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function home()
    {

        $avgAge = $this->userRepository->getAverageAge();
        $avgAgeByGender = $this->userRepository->getAverageAgeByGender();
        $usersCount = $this->userRepository->getUsersCount();
        $usersCountByGender = $this->userRepository->getUsersCountByGender();
        $usersCountByCountry = $this->userRepository->getUsersCountByCountry();
        return view('home', [
            'avgAge' => $avgAge,
            'usersCount' => $usersCount,
            'usersCountByGender' => $usersCountByGender,
            'usersCountByCountry' => $usersCountByCountry,
            'avgAgeByGender' => $avgAgeByGender,
        ]);
    }

    public function create()
    {
        return view('uploadFrom');
    }


    public function upload(Request $request)
    {
        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => $request->has('file') ? strtolower($request->file->getClientOriginalExtension()) : null,
            ],
            [
                'file' => 'required|file|max:20000',
                'extension' => 'required|in:xlsx,xls,csv',
            ]
        );
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fileName = Str::random(40) . '_' . $request->file->getClientOriginalName();
        $request->file->storeAs('files', $fileName);
        $job = (new ParseFile($fileName, $request->is_conatains_header_row));
        $this->dispatch($job);
        return redirect()->back()->with('success', 'File uploaded.');
    }
}
