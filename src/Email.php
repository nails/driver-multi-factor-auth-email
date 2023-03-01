<?php

namespace Nails\MFA\Driver\Authentication;

use Nails\Common\Driver\Base;
use Nails\Common\Helper\Strings;
use Nails\Common\Service\UserFeedback;
use Nails\Factory;
use Nails\MFA\Constants;
use Nails\MFA\Driver\Authentication\Email\Settings\Settings;
use Nails\MFA\Exception\MfaException;
use Nails\MFA\Exception\InvalidCodeException;
use Nails\MFA\Factory\Email\Code;
use Nails\MFA\Interfaces\Authentication\Driver;
use Nails\MFA\Resource\Token;

class Email extends Base implements Driver
{
    public function getLabel(): string
    {
        return 'Email';
    }

    // --------------------------------------------------------------------------

    public function getDescription(): string
    {
        return 'Sends a code to the user\'s email address on login.';
    }

    // --------------------------------------------------------------------------

    public function preForm(Token $oToken, UserFeedback $oUserFeedback): void
    {
        $sExistingCode = $oToken->getData(static::getCodeKey());
        if (!empty($sExistingCode)) {
            $oUserFeedback->warning($this->getSetting(Settings::KEY_FEEDBACK_ALREADY_SENT));
            return;
        }

        $sCode = Strings::generateToken($this->getSetting(Settings::KEY_CODE_FORMAT));
        $oToken->setData((object) [
            static::getCodeKey() => $sCode,
        ]);

        $oUser = $oToken->user();
        if (empty($oUser)) {
            throw new MfaException('Token is not associated with a user.');
        }

        /** @var Code $oEmail */
        $oEmail = Factory::factory('EmailCode', Constants::MODULE_SLUG);
        $oEmail
            ->to($oUser)
            ->data('code', $sCode)
            ->send();

        $oUserFeedback->success($this->getSetting(Settings::KEY_FEEDBACK_SENT));
    }

    // --------------------------------------------------------------------------

    public function postForm(Token $oToken): void
    {
        //  Not required
    }

    // --------------------------------------------------------------------------

    public function validate(Token $oToken, string $sCode): void
    {
        $sStoredCode = $oToken->getData(static::getCodeKey());

        if ($oToken->getData(static::getCodeKey()) !== $sCode) {
            throw new InvalidCodeException($this->getSetting(Settings::KEY_FEEDBACK_INVALID));
        }
    }

    // --------------------------------------------------------------------------

    public function canTryAgain(): bool
    {
        return true;
    }

    // --------------------------------------------------------------------------

    protected static function getCodeKey(): string
    {
        return static::class . '.code';
    }
}
