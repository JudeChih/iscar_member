<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding regiestiscar-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">註冊新帳號</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="regiestiscar">

            {{ Form::open(array('url' => 'regiestiscar/'.$parameter, 'method' => 'post', 'class' => 'regiestiscar-form')) }}

            <!-- 內容 -->
            <div class="page-content regiestiscar-content animated fadeIn">
                <div class="font_style">填寫會員資料</div>
                <div class="list-block">
                    <ul>
                        <li class="align-top account-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            <input name="account" class="account" type="text" placeholder="電子郵件" value="{{$regData['account']}}" readonly>
                                        @else
                                            <input name="account" class="account" type="text" placeholder="電子郵件" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top password-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            <input name="password" class="password" type="password" placeholder="密碼" value="{{$regData['password']}}" readonly>
                                        @else
                                            <input name="password" class="password" type="password" placeholder="密碼" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top passwordconfirm-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            <input name="passwordconfirm" class="passwordconfirm" type="password" placeholder="密碼確認" value="{{$regData['passwordconfirm']}}" readonly>
                                        @else
                                            <input name="passwordconfirm" class="passwordconfirm" type="password" placeholder="密碼確認" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top nickname-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            <input name="nickname" class="nickname" type="text" placeholder="暱稱" onkeydown="input_limit(this, 15);" value="{{$regData['nickname']}}" onkeyup="input_limit(this, 15);" readonly>
                                        @else
                                            <input name="nickname" class="nickname" type="text" placeholder="暱稱" onkeydown="input_limit(this, 15);" onkeyup="input_limit(this, 15);" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top countrycode-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            @if($regData['countrycode'] == 1)
                                                <input class="countrycode-picker" type="text" value="台灣" readonly>
                                            @elseif($regData['countrycode'] == 2)
                                                <input class="countrycode-picker" type="text" value="大陸" readonly>
                                            @endif
                                            <input name="countrycode" class="countrycode" type="hidden" placeholder="地區" readonly value='{{$regData['countrycode']}}'>
                                        @else
                                            <input class="countrycode-picker" type="text" value="台灣" readonly>
                                            <input name="countrycode" class="countrycode" type="hidden" placeholder="地區" readonly value='1'>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top cellphone-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        @if(isset($regData))
                                            <input name="cellphone" class="cellphone" type="number" value="{{$regData['cellphone']}}" placeholder="手機" readonly>
                                        @else
                                            <input name="cellphone" class="cellphone" type="number" placeholder="手機" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">

                        </li>
                    </ul>
                    <div class="iscarpolicy_style">
                        <div class="item-content">
                            {{-- <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div> --}}
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="checkbox" name="">我已明瞭「 <a class='query_iscarpolicy'>服務條款</a>」所載內容及其意義，並同意該條款規定
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="registered">
                    {{-- <div class="toolbar-inner"> --}}
                        <a class="button button-big regiest_btn">註冊</a>
                    {{-- </div> --}}
                </div>
            </div>

            

            {{ Form::close() }}

        </div>
    </div>
</div>
