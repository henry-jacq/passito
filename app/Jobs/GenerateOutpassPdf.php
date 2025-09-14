<?php

namespace App\Jobs;

use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Core\Storage;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;

class GenerateOutpassPdf implements JobInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Storage $storage,
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload)
    {
        if (empty($payload['directory']) || empty($payload['prefix']) || empty($payload['outpass_id'])) {
            throw new \InvalidArgumentException(
                'Invalid payload for GeneratePdfJob ' . json_encode($payload)
            );
        }

        $qrCodeFile = null;
        if (!empty($payload['dependencies'])) {
            foreach ($payload['dependencies'] as $dep) {
                if (!empty($dep['qrCodePath'])) {
                    $qrCodeFile = basename($dep['qrCodePath']);
                    break;
                }
            }
        }

        $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);

        $args = [
            'outpass' => $outpass,
            'qrCodeFile' => $qrCodeFile
        ];
        
        // Render Outpass document content as HTML
        $html = $this->view->renderEmail('outpass/document', $args ?? []);

        // Change the working directory to storage path
        $options = new Options();
        // To get warden signature image
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath(STORAGE_PATH));

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF
        $output = $dompdf->output();

        // Generate unique file name with path
        $pdfPath = $this->storage->generateFileName($payload['directory'], 'pdf', $payload['prefix'] ?? 'document_');

        // Save the PDF to a file
        $this->storage->write($pdfPath, $output);

        return ['pdfPath' => $pdfPath];
    }
}
