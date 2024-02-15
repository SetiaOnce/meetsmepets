@extends('frontend.layouts', ['activeMenu' => 'CHAT', 'activeSubMenu' => '', 'pageChat' => 'Y'])
@section('content')
@section('header-chat')
<!-- Header -->
<header class="header header-fixed bg-white">
    <div class="container">
        <div class="header-content">
            <div class="left-content me-3">
                <a href="{{ url('/chat') }}" class="back-btn">
                    <i class="icon feather icon-chevron-left"></i>
                </a>
            </div>
            <div class="mid-content d-flex align-items-center text-start">
                <a href="javascript:void(0);" class="media media-40 rounded-circle me-3">
                    <img src="{{ $data['owner_info']['thumb_url'] }}" alt="/">
                </a>
                <div>
                    <h6 class="title">{{ $data['owner_info']['name'] }}</h6>
                    <span>
                        @if ($data['owner_info']['last_login'] == 'Just now')
                            online
                        @else
                            {{ $data['owner_info']['last_login'] }}
                        @endif
                    </span>
                </div>	
            </div>
            {{-- <div class="right-content d-flex align-items-center">
                <a href="javascript:void(0);" class="dz-icon btn btn-primary light">
                    <i class="fa-solid fa-phone"></i>
                </a>
                <a href="javascript:void(0);" class="dz-icon me-0 btn btn-primary light">
                    <i class="fa-solid fa-video"></i>
                </a>
            </div> --}}
        </div>
    </div>
</header>
<!-- Header -->
@stop

<!-- Page Content Start -->
<div class="page-content space-top p-b60 message-content" id="sectionChat">
    <div class="container"> 
        <div class="chat-box-area chat-message{{ $data['key_chat'] }}" id="{{ $data['key_chat'] }}"> 
            {{-- this area for a message --}}
        </div>
    </div> 
</div>
<!-- Page Content End -->

@section('footer-chat')
<footer class="footer border-top fixed bg-white">
    <div class="container p-2">
        <div class="chat-footer">
            <form>
                <div class="form-group">
                    <div class="input-wrapper message-area">
                        <div class="append-media"></div>
                        <input type="text" class="form-control" name="message" id="message" placeholder="Send message..." autocomplete="off">
                        <a href="javascript:void(0);" class="btn-chat" id="btn-send">
                           <i class="icon feather icon-send"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>    
    </div>
</footer>
@stop
<script>var idp_owner = "{{ $data['owner_info']['id'] }}";</script>
<script>var my_id = "{{ $data['user_session']['id'] }}";</script>
<script>var key_chat = "{{ $data['key_chat'] }}";</script>
@endsection