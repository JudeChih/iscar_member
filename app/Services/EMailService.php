<?php

namespace App\Services;

use \Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

define('RESET_PASSWORD_URI', config('global.reset_password_uri'));

class EMailService extends Mailable {

    /**
     * 寄送郵件-密碼重置通知
     * @param type $md_id
     * @param type $rpv_serno
     */
    public static function send_ResetPassword($md_id, $rpv_serno) {
        try {
            //取得會員資料
            $memRepo = new \App\Repositories\MemberDataRepository();
            $memData = $memRepo->getData($md_id);
            if (!isset($memData) || count($memData) !== 1) {
                return false;
            }

            //取得驗證碼資料
            $rpvRepo = new \App\Repositories\ResetPwdVerifyRepository();
            $rpvData = $rpvRepo->getData($rpv_serno);
            if (!isset($rpvData) || count($rpvData) !== 1) {
                return false;
            }

            $email = $memData->md_contactmail;

            $resetPwdUri = RESET_PASSWORD_URI . \App\Library\CommonTools::encodeResetPwdHashParameter($rpvData->rpv_serno, $rpvData->rpv_hash);
            $subject = '密碼重置通知';
            Mail::send('emails.ResetPassword', compact('resetPwdUri'), function ($message)use($subject, $email) {
                $message->to($email)
                        ->subject($subject);
            });
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

}
