<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    public function index()
    {
        return view('index');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'script_token' => 'required|string|exists:brands,script_token',
            'form_data' => 'required|array',
            'device_info' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $brand = Brand::where('script_token', $request->script_token)->first();

        $userAgent = $request->userAgent();

        $deviceInfo = array_merge($request->device_info ?? [], [
            'ip' => $request->ip(),
            'user_agent' => $userAgent,
            'browser_name' => $this->getBrowserName($userAgent),
        ]);

        $formData = $request->form_data;

        $fieldMapping = [
            'first_name' => ['fname','name','firstname','first-name','first_name','fullname','full_name','yourname','your-name'],
            'last_name' => ['lname','lastname','last-name','last_name'],
            'email' => ['email'],
            'phone' => ['tel','tele','phone','telephone','your-phone','phone-number','phonenumber'],
            'message' => ['message']
        ];


        $dataToSave = [];

        foreach ($fieldMapping as $column => $possibleFields) {
            foreach ($possibleFields as $field) {
                if (isset($formData[$field])) {
                    $dataToSave[$column] = $formData[$field];
                    break;
                }
            }

            // If the field is not found, assign null
            if (!isset($dataToSave[$column])) {
                $dataToSave[$column] = null;
            }
        }

        $lead = Lead::create([
            'brand_name' => $brand->name,
            'first_name' => $dataToSave['first_name'],
            'last_name' => $dataToSave['last_name'],
            'email' => $dataToSave['email'],
            'phone' => $dataToSave['phone'],
            'message' => $dataToSave['message'],
            'device_info' => $deviceInfo,

        ]);

        return response()->json(['lead'=> '$lead','message' => 'Lead captured successfully.'], 201);
    }

    private function getBrowserName($userAgent)
    {
        $browsers = [
            'Edge' => 'Edg',
            'Opera' => 'OPR',
            'Vivaldi' => 'Vivaldi',
            'Brave' => 'Brave',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Internet Explorer' => 'MSIE|Trident'
        ];

        foreach ($browsers as $browser => $pattern) {
            if (preg_match("/$pattern/i", $userAgent)) {
                return $browser;
            }
        }

        return 'Unknown';
    }



}
