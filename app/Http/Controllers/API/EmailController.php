<?php

namespace App\Http\Controllers\API;
use Exception;
use App\Models\User;
use App\Models\Email;
use App\Models\EmailCc;
use Illuminate\Http\Request;
use App\Exports\importExport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;

class EmailController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(EmailCc::latest()->get());
    }

    public function StoreCCEmail(Request $request)
    {

        $request->validate([
            'email_cc' => 'required|email|max:255',
        ]);

        return EmailCc::create([
            'email_cc' => $request['email_cc']
        ]);
    }

    

    public function update(Request $request,$id){
        $request->validate([
            'email_cc' => 'required|email|max:255',
        ]);

        return EmailCc::find($id)->update([
            'email_cc' => $request['email_cc']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emailImport = Email::upsert($request->all(),['area_code']);
        return  $emailImport;

    }

    public function SendEmail(request $request){
        $extension = explode('/', explode(':', substr($request->link, 0, strpos($request->link, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($request->link, 0, strpos($request->link, ',')+1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $request->link);
        $image = str_replace(' ', '+', $image);

        foreach ($request->data['email_details'] as $email){
        $data = [
            'email' => $email['email'],
            'date' => $request->date,
            'type' => "Statement of Account", //to fix
            'arena_name' => $request->data['arena'],
            'subject' => "KIOSK SALES REPORT FOR".' '.$request->date,
            'file' =>  $image
        ];

        $files = [
           base64_decode($data["file"])
        ];


        Mail::send('email.emailsoa', $data, function($message)use($data, $files) {
            $message->to($data["email"])
                    ->subject($data["subject"]);

            foreach ($files as $file){
                $message->attachData($file, $data['arena_name'].".png",['mime'=>'image/jpg']);
            }

        });
            // dump($email['email']);

        }

        return response()->json('Email suucessfully send');
    }


    public function sendZipEmail(request $request){

        foreach ($request->emails as $email){
        $data = [
            'email' => $email,
            'date' => $request->date,
            'type' => "Statement of Account", //to fix
            'arena_name' => $request->operator,
            'subject' => "KIOSK SALES REPORT FOR".' '.$request->date,
            'file' =>  $request->link
        ];

        $files = [
           base64_decode($data["file"])
        ];


        Mail::send('email.emailviazipsoa', $data, function($message)use($data, $files) {
            $message->to($data["email"])
                    ->subject($data["subject"]);

            foreach ($files as $file){
                $message->attachData($file, $data['arena_name'].".zip",['mime' => 'application/zip']);
            }

        });


        }

        return response()->json('Email suucessfully send');
    }

    public function MultisendEmail(request $request){

        $extension = explode('/', explode(':', substr($request->link, 0, strpos($request->link, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($request->link, 0, strpos($request->link, ',')+1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $request->link);
        $image = str_replace(' ', '+', $image);
            // dump($request->all());
            foreach ($request->emails as $email){
            $data = [
                'group' => $request->group,
                'email' => $email,
                'date' => $request->date,
                'type' => "Statement of Account", //to fix
                'arena_name' => $request->arena_name,
                'subject' => "KIOSK SALES REPORT FOR".' '.$request->date,
                'file' =>  $image
            ];

            $files = [
               base64_decode($data["file"])
            ];


            Mail::send('email.emailsoa', $data, function($message)use($data, $files) {
                $message->to($data["email"])
                        ->subject($data["subject"]);

                foreach ($files as $file){
                    $message->attachData($file, $data['arena_name'].".png",['mime'=>'image/jpg']);
                }

            });


        }

            return response()->json('Email suucessfully send');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return EmailCc::find($id)->delete();
    }



}
