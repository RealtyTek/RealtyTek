<?php
namespace App\Http\Controllers;
use App\Models\{Buyer,AppContent,AppFaq,User};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function updateTourHome(){
        $data = Buyer::updateTourHomeStatus();
        if ($data == 1) {
            return response()->json(['status'=>"update tour home status"]);
        }else{
            return response()->json(['status'=>"no record found to update"]);
        }
    }


    public function getAppContent($slug)
    {
        $content = AppContent::where('slug',$slug)->first();
        if(empty($content->id)){
            return redirect('/');
        }else{
            return view('app-content.app_content',compact('content'));
        }
    }


    public function getAppContentFaqWeb()
    {
        $content['title'] = "Faq";
        $content['data'] = AppFaq::where('status',1)->get();
        // print_r($content['data']); die;
        // return view('app-content.faq',compact('content'));
        return view('app-content.faq_duplicate');
    }

    public function getAppSupport()
    {
        return view('app-content.support');
    }


    public function submitSupportEmail(Request $request)
    {
        // $request = \Request::all();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'description' => 'required',
        ]);


        User::sendSupportEmail($request->all());

        return back()->with('success','Support email has been sent.');
    }

}
