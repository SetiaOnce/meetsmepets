<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ChatPersonal;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Owners;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Start Chat',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/frontend/chat.init.js',
        );
        return view('frontend.chat', compact('data'));
    }
    public function startChat($owner_id)
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        $iduserSesIdp = Auth::guard('owner')->user()->id;
        // get key chat for check
        $check = Chat::where(function ($query) use ($iduserSesIdp, $owner_id) {
            $query->where('from', $iduserSesIdp)
            ->where('to', $owner_id);
        })
        ->orWhere(function ($query) use ($iduserSesIdp, $owner_id) {
            $query->where('from', $owner_id)
            ->where('to', $iduserSesIdp);
        })
        ->first();
        $keyChat = md5(uniqid());
        if(!empty($check)){
            $keyChat = $check->key_chat;
        }
        // owner chat info
        $owner = Owners::whereId($owner_id)->first();
        $file_name = $owner->thumb;
        if($file_name==''){
            $owner->thumb_url = asset('dist/img/default-user-img.jpg');
        } else {
            if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                $owner->thumb_url = asset('dist/img/default-user-img.jpg');
            }else{
                $owner->thumb_url = url('dist/img/users-img/'.$file_name);
            }
        }
        $owner->last_login = Shortcut::timeago($owner->last_login);
        //Data WebInfo
        $data = array(
            'title' => 'Chat',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'owner_info' => $owner,
            'key_chat' => $keyChat,
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/frontend/start_chat.init.js',
        );
        return view('frontend.start_chat', compact('data'));
    }
    public function ChatLove()
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $getRow =  SuperLike::select(
            'owners.id',
            'owners.name',
            'owners.thumb',
            'owners.last_login',
        )
        ->join('owners', 'owners.id', '=', 'super_like.fid_owner')
        ->whereNot('owners.id', $iduserSesIdp)->where('owners.is_active', 'Y')
        ->orderBy('owners.last_login', 'DESC')
        ->get();

        $allowChat = [];
        foreach($getRow as $row){
            $file_name = $row->thumb;
            if($file_name==''){
                $thumb_url = asset('dist/img/default-user-img.jpg');
            } else {
                if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                    $thumb_url = asset('dist/img/default-user-img.jpg');
                }else{
                    $thumb_url = url('dist/img/users-img/'.$file_name);
                }
            }
            if($row->last_login < time() - (2 * 60)){
                $statusActive = 'Y';
            }else{
                $statusActive = 'N';
            }
            $allowChat[] = [
                'id' => $row->id,
                'name' => Str::limit($row->name, 10),
                'thumb_url' => $thumb_url,
                'is_active' => $statusActive
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $allowChat);
    }
    public function allMessage()
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  

        $incomingChats = Chat::where('from', $iduserSesIdp)
        ->orderBy('created_at', 'desc')
        ->get();
        $outgoingChats = Chat::where('to', $iduserSesIdp)
        ->orderBy('created_at', 'desc')
        ->get();
        $allChats = $incomingChats->merge($outgoingChats)->sortByDesc('created_at');
        $groupedData = [];
        // grouping the key chat
        foreach ($allChats as $item) {
            $keyChat = $item['key_chat'];
            if (!isset($groupedData[$keyChat])) {
                $groupedData[$keyChat] = [];
            }
            $groupedData[$keyChat][] = $item;
        }
        // getting the key chat
        $keyChats = [];      
        foreach ($groupedData as $keyChat => $chat) {
            $keyChats[] = $keyChat;
        }
        // getting the newer chat
        $filterChats = [];
        foreach($keyChats as $key){
            $chat = Chat::whereKeyChat($key)->orderByDesc('id')->first();
            $chatNotRead = Chat::whereKeyChat($key)->whereTo($iduserSesIdp)->whereIsRead('N')->count();
            $statusRead = $chat->is_read;

            // Tentukan apakah pesan ini adalah pesan yang Anda kirimkan atau yang Anda terima
            if($chat->from == $iduserSesIdp){
                $receiver = Owners::find($chat->to);
                $isSentByYou = 'Y';
                $file_name = $receiver->thumb;
                // status login
                if($receiver->last_login < time() - (2 * 60)){
                    $statusActive = 'Y';
                }else{
                    $statusActive = 'N';
                }
                $idp = $receiver->id;
                $name = $receiver->name;
            }else{
                $sender = Owners::find($chat->from);
                $isSentByYou = 'N';
                $file_name = $sender->thumb;
                // status login
                if($sender->last_login < time() - (2 * 60)){
                    $statusActive = 'Y';
                }else{
                    $statusActive = 'N';
                }
                $idp = $sender->id;
                $name = $sender->name;
            }

            if($file_name==''){
                $thumb_url = asset('dist/img/default-user-img.jpg');
            } else {
                if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                    $thumb_url = asset('dist/img/default-user-img.jpg');
                }else{
                    $thumb_url = url('dist/img/users-img/'.$file_name);
                }
            }

            $filterChats[] = [
                'id' => $idp,
                'name' => $name,
                'is_sendby_you' => $isSentByYou,
                'thumb_url' => $thumb_url,
                'message' => Str::limit($chat->message, 20),
                'last_chat' => Shortcut::timeago($chat->created_at),
                'is_active' => $statusActive,
                'is_read' => $statusRead,
                'chat_notread' => $chatNotRead,
            ];
        }
        // dd($filterChats);
        return Shortcut::jsonResponse(true, 'Success', 200, $filterChats);
    }
    public function personalChat(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $idpOtherOwner = $request->idp_owner;
        $getRow = Chat::where(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
            $query->where('from', $iduserSesIdp)
                  ->where('to', $idpOtherOwner);
        })
        ->orWhere(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
            $query->where('from', $idpOtherOwner)
                  ->where('to', $iduserSesIdp);
        })
        ->orderBy('created_at', 'ASC')
        ->get();
        $personalMessage = [];
        foreach($getRow as $row){
            Chat::whereKeyChat($row->key_chat)->whereTo($iduserSesIdp)->update([
                'is_read' => 'Y'
            ]);
            $isMe = '';
            if($row->from == $iduserSesIdp){
                $isMe = 'user';
            }
            $personalMessage[] = [
                'id' => $row->id,
                'message' => $row->message,
                'timechat' => date("H:i", strtotime($row->created_at)),
                'is_me' => $isMe,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $personalMessage);
    }
    public function sendMessage(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $idpOtherOwner = $request->idp_owner;
        DB::beginTransaction();
        try {
            // get key chat for check
            $check = Chat::where(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
                $query->where('from', $iduserSesIdp)
                      ->where('to', $idpOtherOwner);
            })
            ->orWhere(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
                $query->where('from', $idpOtherOwner)
                      ->where('to', $iduserSesIdp);
            })
            ->first();
            $keyChat = $request->key_chat;
            if(!empty($check)){
                $keyChat = $check->key_chat;
            }
            Chat::insertGetId([
                'from' => $iduserSesIdp,
                'to' => $idpOtherOwner,
                'message' => $request->message,
                'key_chat' => $keyChat,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
            event(new ChatPersonal(Auth::guard('owner')->user()->id, $idpOtherOwner, $keyChat, $request->message));
            return Shortcut::jsonResponse(true, 'Like or unlike successfully', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}