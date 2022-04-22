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

        $emailcc = EmailCc::create([
            'email_cc' => $request['email_cc']
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('created',$emailcc->email_cc,'emailcc',$emailcc->id);
    }



    public function update(Request $request,$id){
        $request->validate([
            'email_cc' => 'required|email|max:255',
        ]);

        $update = EmailCc::find($id);
        $update->update([
            'email_cc' => $request['email_cc']
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$request->emailcc,'emailcc',$id);
        
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
        dd($request->data);
        $extension = explode('/', explode(':', substr($request->link, 0, strpos($request->link, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($request->link, 0, strpos($request->link, ',')+1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $request->data['link']);
        $image = str_replace(' ', '+', $image);

        foreach ($emails as $email){
    
        $data = [
            'email_cc' => $request->cc,
            'arena_name' => $request->data['arena'],
            'subject' => "KIOSK SALES REPORT FOR".' '.$request->data['date'],
            'file' =>  $image
        ];

        $files = [
           base64_decode($image)
        ];
     
        Mail::send($data['group'] == "Statement of Account" ? 'email.emailsoa' :' email.reflenishment' , $data, function($message)use($data, $files) {
            $message->to($data["email"])
                    ->cc($data['email_cc'])
                    ->subject($data["subject"]);

            foreach ($files as $file){
                $message->attachData($file, $data['arena_name'].".png",['mime'=>'image/jpg']);
            }

        });

        }

        return response()->json('Email suucessfully send');
    }


    public function sendZipEmail(request $request){
       
        $data = [
            'email' => $request->email,
            'email_cc' => $request->cc,
            'date' => $request->date,
            'arena_name' => $request->arena,
            'subject' => "KIOSK SALES REPORT FOR".' '.$request->date,
            'file' =>  $request->link
        ];

        $files = [
           base64_decode($data["file"])
        ];


        Mail::send('email.emailviazipsoa', $data, function($message)use($data, $files) {

            $message->to($data["email"])
                    ->cc($data['email_cc'])
                    ->subject($data["subject"]);

            foreach ($files as $file){
                $message->attachData($file, $data['arena_name'].".zip",['mime' => 'application/zip']);
            }

        });



        return response()->json('Email suucessfully send');
    }

    public function MultisendEmail(request $request){
        // dd($request->group);
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
                'email_cc' => $request->cc,
                'date' => $request->date,
                'arena_name' => $request->arena_name,
                'subject' => "KIOSK SALES REPORT FOR".' '.$request->date,
                'file' =>  $image
            ];

            $files = [
               base64_decode($data["file"])
            ];


            Mail::send($data['group'] =='Deposit' ? 'email.emailsoa' :' email.reflenishment', $data, function($message)use($data, $files) {
                $message->to($data["email"])
                        ->cc($data['email_cc'])
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
        $deleteEmail = EmailCc::find($id);
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted',$deleteEmail->email,'email',$deleteEmail->id);
        $deleteEmail->delete();
    }

    public function isUsedUpdate( $id){
        $email = EmailCc::find($id);
        // dd($email->isUse);
        $email->update([
            'isUse' => $email->isUse === 0 ? true : false
        ]);
    }

}
