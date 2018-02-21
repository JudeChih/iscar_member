<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding regiestthirdparty-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">會員註冊</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="regiestthirdparty">

            {{ Form::open(array('url' => 'regiestthirdparty/'.$parameter, 'method' => 'post', 'class' => 'regiestthirdparty-form')) }}

            <!-- 內容 -->
            <div class="page-content regiestthirdparty-content animated fadeIn">
                <div class="font_style">填寫會員資料</div>
                <div class="list-block">
                    <ul>
                        <li class="align-top account-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="account" class="account" type="text" placeholder="電子信箱" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top nickname-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="nickname" class="nickname" type="text" placeholder="暱稱" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top countrycode-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input class="countrycode-picker" type="text" placeholder="地區" value="台灣" readonly>
                                        <input name="countrycode" class="countrycode" type="hidden" placeholder="地區" readonly value="1">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top cellphone-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="cellphone" class="cellphone" type="number" placeholder="手機" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top ssodata-block noUse">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="ssodata" class="ssodata" type="hidden" readonly>
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
