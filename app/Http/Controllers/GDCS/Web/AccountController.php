<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Traits\HasMessage;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function resendVerificationEmail()
    {
        $account = Auth::guard('gdcs')->user();

        if ($account->hasVerifiedEmail()) {
            throw new WebException(__('gdcn.web.error.account_verification_email_resend_failed_already_verified'));
        } else {
            $account->sendEmailVerificationNotification();
            $this->pushSuccessMessage(__('gdcn.web.action.account_verification_email_resend_success'));
        }

        return back();
    }
}
