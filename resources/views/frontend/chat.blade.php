@extends('frontend.layouts', ['activeMenu' => 'CHAT', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top p-b60" id="sectionChat">
    <div class="container">
        <div class="swiper chat-swiper">
            <div class="swiper-wrapper owner-love"></div>
        </div>
        <div class="title-bar">
            <h6 class="title">Message</h6>
        </div>
        <ul class="message-list all-message">

        </ul>
    </div>    
</div>
<!-- Page Content End -->
<script>var my_id = "{{ $data['user_session']['id'] }}";</script>
@endsection