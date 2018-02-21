$(function(){
	$('.changepwd-left').click(function() {
      window.history.back();
  });

  $('.send').click(function() {

      $('.passwordold-block').removeClass('spacesError');
      $('.passwordnew-block').removeClass('spacesError');
      $('.passwordnewconfirm-block').removeClass('spacesError');
      $('.passwordold-block').removeClass('nullError');
      $('.passwordnew-block').removeClass('nullError');
      $('.passwordnewconfirm-block').removeClass('nullError');
      var passwordold = $('.passwordold').val();
      var passwordnew = $('.passwordnew').val();
      var passwordnewconfirm = $('.passwordnewconfirm').val();
      var isError = false;

      //空白字元判斷
      if (passwordold.indexOf(" ") !== -1) {
          $('.passwordold-block').addClass('spacesError');
          isError = true;
      }
      if (passwordnew.indexOf(" ") !== -1) {
          $('.passwordnew-block').addClass('spacesError');
          isError = true;
      }
      if (passwordnewconfirm.indexOf(" ") !== -1) {
          $('.passwordnewconfirm-block').addClass('spacesError');
          isError = true;
      }

      //填寫判斷
      if (passwordold === "") {
          $('.passwordold-block').addClass('nullError');
          isError = true;
      }
      if (passwordnew === "") {
          $('.passwordnew-block').addClass('nullError');
          isError = true;
      }
      if (passwordnewconfirm === "") {
          $('.passwordnewconfirm-block').addClass('nullError');
          isError = true;
      }

      if (passwordnew != '' && passwordnewconfirm != '') {
          //密碼確認判斷
          if (passwordnew === passwordnewconfirm) {
              //強度判斷
              //利用match函數去比較密碼是否符合指定條件：最少一個數字，最少一個小階英文，長度限制為8。
              var chkPwdStength = passwordnew.match(/((?=.*\d)(?=.*[a-z]).{8})/);

              //若match回傳的值為null，跳出警告並阻止表單送出。
              if (chkPwdStength == null) {
                  $('.modal-in').find('.modal-text').text('密碼強度不足，需包含英文字母、數字、大於八位數。', stringObj.text.warn);
                  $('.modal-in').show();
                  isError = true;
              } else {
                  if (!isError) {
                      $('.changepwd-form').submit(); //送出密碼重置表單
                  }
              }
          } else {
              $('.modal-overlay').show();
              $('.modal-in').find('.modal-text').text(stringObj.text.pass_check_error, stringObj.text.warn);
              $('.modal-in').show();
          }
      }
  });
  $('.without_error').on('click',function(){
      $('.modal-overlay').hide();
      $('.modal-in').hide();
  })
  $('.have_error').on('click',function(){
    $('.modal-overlay').hide();
    $('.modal-in').hide();
    window.history.go(-1);
  })
  if($('.error').val() != null){
    $('.modal-overlay').show();
    $('.modal-in').find('.modal-text').text($('.error').val());
    $('.modal-in').show();
  }
})