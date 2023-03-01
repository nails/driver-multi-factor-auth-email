<?php

namespace Nails\MFA\Driver\Authentication\Email\Settings;

use Nails\Common\Helper\Form;
use Nails\Common\Interfaces;
use Nails\Common\Service\FormValidation;
use Nails\Components\Setting;
use Nails\Factory;

/**
 * Class Settings
 *
 * @package Nails\MFA\Driver\Authentication\Email\Settings
 */
class Settings implements Interfaces\Component\Settings
{
    const KEY_CODE_FORMAT           = 'code_format';
    const KEY_FEEDBACK_SENT         = 'feedback_sent';
    const KEY_FEEDBACK_ALREADY_SENT = 'feedback_already_sent';
    const KEY_FEEDBACK_INVALID      = 'feedback_invalid';

    // --------------------------------------------------------------------------]

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return 'MFA: Email';
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return [];
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        /** @var Setting $oFormat */
        $oFormat = Factory::factory('ComponentSetting');
        $oFormat
            ->setKey(static::KEY_CODE_FORMAT)
            ->setLabel('Format')
            ->setDefault('DDDDDD')
            ->setInfo('The format of the code; D[igit] = 0-9, C[haracter] = A-Z, A = any. e.g. DDDDDD = 123456');

        /** @var Setting $oFeedbackSent */
        $oFeedbackSent = Factory::factory('ComponentSetting');
        $oFeedbackSent
            ->setKey(static::KEY_FEEDBACK_SENT)
            ->setLabel('Code has been sent')
            ->setFieldset('User Feedback')
            ->setDefault('A code has been sent to your email address. Please enter it below.');

        /** @var Setting $oFeedbackAlreadySent */
        $oFeedbackAlreadySent = Factory::factory('ComponentSetting');
        $oFeedbackAlreadySent
            ->setKey(static::KEY_FEEDBACK_ALREADY_SENT)
            ->setLabel('Code has already been sent')
            ->setFieldset('User Feedback')
            ->setDefault('A code has already been sent to your email address. Please enter it below.');

        /** @var Setting $oFeedbackInvalid */
        $oFeedbackInvalid = Factory::factory('ComponentSetting');
        $oFeedbackInvalid
            ->setKey(static::KEY_FEEDBACK_INVALID)
            ->setLabel('Invalid Code Entered')
            ->setFieldset('User Feedback')
            ->setDefault('Invalid code entered. Please try again.');

        return [
            $oFormat,
            $oFeedbackSent,
            $oFeedbackAlreadySent,
            $oFeedbackInvalid,
        ];
    }
}
