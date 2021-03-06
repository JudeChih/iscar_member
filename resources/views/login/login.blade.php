<div class="view view-main">
    <div class="navbar animated fadeIn">
        <div class="navbar-inner">
            @if($redirect_uri != '')
                <div class="left sliding backBtn" data-src="{{$redirect_uri}}">
                    <a href="#" class="link icon-only">

                    </a>
                </div>
            @else
                <div class="left sliding backBtn" data-src="">
                    <a href="#" class="link icon-only">

                    </a>
                </div>
            @endif
            <div class="center sliding">登入</div>
            <div class="right">

            </div>
        </div>
    </div>
    <div class="pages navbar-fixed toolbar-fixed">
        <div data-page="login" class="page" data-href="app/image/carbon_bg.jpg">
            <!-- 內文 -->
            <div class="page-content login-content animated fadeIn">

                {{ Form::open(array('url' => 'login/'.$parameter, 'method' => 'post', 'class' => 'login-form')) }}

                <img src="app/image/logo.png" class="logo animated lightSpeedIn">
                <div class="list-block">
                    <ul>
                        <li class="align-top account-block">
                            <div class="item-content">
                                <div class="item-media"><img src="app/image/mem_ic_mail.png"></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="account" class="account" type="text" placeholder="請輸入電子郵件">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top password-block">
                            <div class="item-content">
                                <div class="item-media"><img src="app/image/mem_ic_passwd.png"></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input class="password" name="password" type="password" placeholder="請輸入密碼">
                                    </div>
                                    <div class="showpwd" style="font-size: 1.3em;">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- <li class="align-top password-block" style="background: none;">
                            <div class="item-content">
                                <div class="item-media" style="height: 44px;">
                                    <input id="showpwd" type="checkbox"/>
                                </div>
                                <div class="item-inner showpwd">
                                    <div class="item-input" style="line-height: 44px;color: #ccc;">
                                        顯示密碼
                                    </div>
                                </div>
                            </div>
                        </li> --}}
                        {{-- <li class="noUse">

                        </li> --}}
                    </ul>
                </div>
                <div>
                    <a class="button button-big login-btn">登入</a>
                    <div class="registered_foget">
                        <a class="now_registered">立即註冊</a>
                        <a class="forget_password">忘記密碼</a>
                    </div>
                </div>

                {{ Form::close() }}

                <div class="other-login-note">
                    <span>────</span>
                    <span> 其他登入方式 </span>
                    <span>────</span>
                    {{-- <div><img src="app/image/line.png"></div><div>其他登入方式</div><div><img src="app/image/line.png"></div> --}}
                </div>

                <div class="other-login-block">
                    <!--<div class="col-33 google_login noUse"><img src="app/image/google_icon.png"></div>
                    <div class="col-33 fb_login"><img src="app/image/facebook_icon.png"></div>
                    <div class="col-33 wechat_login noUse"><img src="app/image/wechat_icon.png"></div>-->
                    {{-- <a class="button button-big fb_login">Facebook 登入</a> --}}
                    {!! $button !!}
                </div>

                <div class="bottom-block">
                    <a href="#" class="aboutLink">關於我們</a>
                    <span>|</span>
                    <a href="#" class="privacyLink">隱私政策</a>
                </div>
            </div>
        </div>
    </div>
</div>
