<?php

namespace App\Enum;

enum ResourceType: string
{
    case OUTPASS_ATTACHMENT = 'outpass_attachment';
    case OUTPASS_DOCUMENT = 'outpass_document';
    case OUTPASS_QR = 'outpass_qr';
    case REPORT_EXPORT = 'report_export';
    case STUDENT_FILE = 'student_file';
    case VERIFIER_FILE = 'verifier_file';
    case GENERAL = 'general';
}
