import Ajax from './libs/ajax';
import Toast from './libs/toast';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('manual-verifier-form');
    if (!form) return;

    const scanPanel = document.getElementById('qr-scan-panel');
    const video = document.getElementById('qr-video');
    const stopBtn = document.getElementById('qr-stop');
    const scanButton = document.getElementById('start-qr-scan');
    const resultPanel = document.getElementById('qr-result');
    const checkoutBtn = document.getElementById('qr-checkout');
    const checkinBtn = document.getElementById('qr-checkin');
    const manualIdInput = document.getElementById('manual-outpass-id');
    const manualUseBtn = document.getElementById('manual-use-id');
    const studentName = document.getElementById('qr-student-name');
    const outpassType = document.getElementById('qr-type');
    const destination = document.getElementById('qr-destination');
    const statusBadge = document.getElementById('qr-status');
    const depart = document.getElementById('qr-depart');
    const ret = document.getElementById('qr-return');
    const checkoutStatus = document.getElementById('qr-checkout-status');
    const checkinStatus = document.getElementById('qr-checkin-status');
    let activeStream = null;
    let zxingControls = null;
    let activeScan = false;
    let activeOutpassId = null;
    let lastPayload = null;
    let latestRequestId = 0;
    let actionInFlight = false;
    let lastData = null;
    let manualLookupTimer = null;
    let lastManualLookupId = null;
    let qrScanInFlight = false;
    let qrScanDetector = null;

    const hideResultPanel = () => {
        if (resultPanel) {
            resultPanel.classList.add('hidden');
        }
        lastData = null;
        setButtonState(checkoutBtn, false);
        setButtonState(checkinBtn, false);
        setStatusBadge('', 'neutral');
    };

    const normalizeQrPayload = (value) => {
        if (typeof value !== 'string') return '';
        return value.trim();
    };

    const setStatusBadge = (label, variant) => {
        if (!statusBadge) return;
        const variants = {
            neutral: 'bg-gray-100 text-gray-600',
            success: 'bg-green-100 text-green-800',
            warning: 'bg-amber-100 text-amber-800',
            info: 'bg-blue-100 text-blue-800',
            danger: 'bg-red-100 text-red-800'
        };
        const classes = variants[variant] || variants.neutral;
        statusBadge.textContent = label;
        statusBadge.className = `px-2 py-1 text-xs font-medium rounded-full ${classes}`;
    };

    const isResultVisible = () => resultPanel && !resultPanel.classList.contains('hidden');
    const showResultPanel = () => {
        if (resultPanel && resultPanel.classList.contains('hidden')) {
            resultPanel.classList.remove('hidden');
        }
    };

    const updateButtons = (data) => {
        showResultPanel();
        if (checkoutBtn) checkoutBtn.classList.remove('hidden');
        if (checkinBtn) checkinBtn.classList.remove('hidden');

        if (data?.has_checkin) {
            setButtonState(checkoutBtn, false);
            setButtonState(checkinBtn, false);
        } else if (data?.has_checkout) {
            setButtonState(checkoutBtn, false);
            setButtonState(checkinBtn, true);
        } else {
            setButtonState(checkoutBtn, true);
            setButtonState(checkinBtn, false);
        }
    };

    const setLoadingState = () => {
        if (isResultVisible() && !lastData) {
            setStatusBadge('Loading...', 'info');
        }
        setButtonState(checkoutBtn, false);
        setButtonState(checkinBtn, false);
    };

    const renderResult = (data) => {
        lastData = data;
        activeOutpassId = data?.id || null;
        if (studentName) studentName.textContent = data?.student_name || '';
        if (outpassType) outpassType.textContent = data?.type ? `Type: ${data.type}` : '';
        if (destination) destination.textContent = data?.destination ? `Destination: ${data.destination}` : '';
        if (checkoutStatus) checkoutStatus.textContent = data?.checkout_time || 'Pending';
        if (checkinStatus) checkinStatus.textContent = data?.checkin_time || 'Pending';
        if (depart) depart.textContent = `${data?.depart_date || '—'} ${data?.depart_time || ''}`.trim();
        if (ret) ret.textContent = `${data?.return_date || '—'} ${data?.return_time || ''}`.trim();

        const statusVariant = (() => {
            switch (data?.status_color) {
                case 'green':
                    return 'success';
                case 'yellow':
                    return 'warning';
                case 'red':
                    return 'danger';
                case 'gray':
                    return 'neutral';
                default:
                    return 'info';
            }
        })();

        if (data?.has_checkin) {
            setStatusBadge('Expired', 'danger');
        } else if (data?.has_checkout) {
            setStatusBadge('Checked Out', 'warning');
        } else {
            setStatusBadge(data?.status || 'Ready', statusVariant);
        }

        updateButtons(data);
    };

    const setButtonState = (button, enabled) => {
        if (!button) return;
        button.disabled = !enabled;
        if (enabled) {
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            button.classList.add('opacity-50', 'cursor-not-allowed');
        }
    };

    const stopScan = () => {
        qrScanInFlight = false;
        qrScanDetector = null;
        if (zxingControls) {
            zxingControls.stop();
            zxingControls = null;
        }
        if (activeStream) {
            activeStream.getTracks().forEach(track => track.stop());
            activeStream = null;
        }
        if (video) {
            video.pause();
            video.srcObject = null;
        }
        if (scanPanel) scanPanel.classList.add('hidden');
        activeScan = false;

        if (stopBtn) {
            stopBtn.disabled = true;
            stopBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    };

    const startScan = async () => {
        try {
            // Ensure only one scan session at a time.
            stopScan();
            activeScan = true;
            activeOutpassId = null;
            if (stopBtn) {
                stopBtn.disabled = false;
                stopBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            activeStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            if (video) {
                video.srcObject = activeStream;
                await video.play();
            }
            if (scanPanel) scanPanel.classList.remove('hidden');

            if (!('BarcodeDetector' in window)) {
                try {
                    const { BrowserQRCodeReader } = await import('@zxing/browser');
                    const reader = new BrowserQRCodeReader();
                    zxingControls = await reader.decodeFromVideoDevice(undefined, video, (result, error) => {
                        if (result?.getText()) {
                            const payload = result.getText();
                            stopScan();
                            submitScan(payload);
                        }
                    });
                    return;
                } catch (error) {
                    stopScan();
                    const toast = new Toast();
                    toast.create({ message: 'QR scanning fallback is not available. Please install dependencies.', position: "bottom-right", type: "warning", duration: 5000 });
                    return;
                }
            }

            qrScanDetector = new BarcodeDetector({ formats: ['qr_code'] });
            const scanLoop = async () => {
                if (!video || !activeStream || !activeScan) return;
                try {
                    const barcodes = await qrScanDetector.detect(video);
                    if (barcodes.length > 0) {
                        if (qrScanInFlight) return;
                        const payload = normalizeQrPayload(barcodes[0].rawValue);
                        if (!payload) return;
                        qrScanInFlight = true;
                        stopScan();
                        await submitScan(payload);
                        qrScanInFlight = false;
                        return;
                    }
                } catch (err) {
                    console.error('QR scan error:', err);
                }
                requestAnimationFrame(scanLoop);
            };
            requestAnimationFrame(scanLoop);
        } catch (error) {
            stopScan();
            const toast = new Toast();
            toast.create({ message: 'Unable to access camera.', position: "bottom-right", type: "error", duration: 5000 });
        }
    };

    const submitScan = async (payload) => {
        const toast = new Toast();
        try {
            const requestId = ++latestRequestId;
            lastPayload = normalizeQrPayload(payload);
            activeOutpassId = null;
            setLoadingState();
            if (!lastPayload) {
                toast.create({ message: 'Invalid QR code.', position: "bottom-right", type: "error", duration: 4000 });
                hideResultPanel();
                return;
            }
            const response = await Ajax.post('/api/web/verifier/scan', {
                qr_payload: lastPayload
            });

            if (response.ok && response.data?.status && response.data?.data) {
                if (requestId !== latestRequestId) return;
                renderResult(response.data.data);
            } else {
                toast.create({ message: response.data?.message || 'Invalid QR code.', position: "bottom-right", type: "error", duration: 5000 });
                hideResultPanel();
            }
        } catch (error) {
            toast.create({ message: 'An error occurred while scanning the QR.', position: "bottom-right", type: "error", duration: 5000 });
            hideResultPanel();
        }
    };

    const submitManualId = async (id) => {
        const toast = new Toast();
        try {
            const requestId = ++latestRequestId;
            lastPayload = null;
            activeOutpassId = null;
            setLoadingState();
            const response = await Ajax.post('/api/web/verifier/scan', {
                outpass_id: id
            });

            if (response.ok && response.data?.status && response.data?.data) {
                if (requestId !== latestRequestId) return;
                renderResult(response.data.data);
            } else {
                toast.create({ message: response.data?.message || 'Outpass not found.', position: "bottom-right", type: "error", duration: 5000 });
                hideResultPanel();
            }
        } catch (error) {
            toast.create({ message: 'Unable to load outpass details.', position: "bottom-right", type: "error", duration: 5000 });
            hideResultPanel();
        }
    };

    if (scanButton) {
        scanButton.addEventListener('click', () => {
            startScan();
        });
    }

    if (manualUseBtn) {
        manualUseBtn.addEventListener('click', () => {
            const toast = new Toast();
            const raw = manualIdInput?.value?.trim();
            const id = raw ? Number(raw) : 0;
            if (!id || Number.isNaN(id)) {
                toast.create({ message: 'Enter a valid outpass ID.', position: "bottom-right", type: "warning", duration: 4000 });
                return;
            }
            lastManualLookupId = id;
            submitManualId(id);
        });
    }

    if (manualIdInput) {
        const scheduleManualLookup = () => {
            if (manualLookupTimer) {
                clearTimeout(manualLookupTimer);
                manualLookupTimer = null;
            }
            const raw = manualIdInput.value.trim();
            const id = raw ? Number(raw) : 0;
            if (!id || Number.isNaN(id)) return;
            if (id === lastManualLookupId) return;

            manualLookupTimer = setTimeout(() => {
                lastManualLookupId = id;
                submitManualId(id);
            }, 350);
        };

        // Spinner (+/-) and typing should not trigger duplicate calls; debounce it.
        manualIdInput.addEventListener('input', scheduleManualLookup);
        manualIdInput.addEventListener('change', scheduleManualLookup);

        manualIdInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const raw = manualIdInput.value.trim();
                const id = raw ? Number(raw) : 0;
                if (id && !Number.isNaN(id)) {
                    lastManualLookupId = id;
                    submitManualId(id);
                }
            }
        });
    }

    if (stopBtn) {
        stopBtn.addEventListener('click', stopScan);
    }

    // Disable stop button until a scan session starts.
    if (stopBtn) {
        stopBtn.disabled = true;
        stopBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    const submitAction = async (action) => {
        if (!activeOutpassId) {
            const toast = new Toast();
            toast.create({ message: 'Scan a valid QR code first.', position: "bottom-right", type: "warning", duration: 4000 });
            return;
        }
        if (actionInFlight) return;
        actionInFlight = true;
        setLoadingState();
        const toast = new Toast();
        try {
            setButtonState(checkoutBtn, false);
            setButtonState(checkinBtn, false);
            const response = await Ajax.post('/api/web/verifier/log', {
                outpass_id: activeOutpassId,
                action
            });

            if (response.ok && response.data?.status) {
                toast.create({ message: response.data.message || 'Verification successful.', position: "bottom-right", type: "success", duration: 4000 });
                if (lastPayload) {
                    await submitScan(lastPayload);
                } else if (activeOutpassId) {
                    await submitManualId(activeOutpassId);
                }
            } else {
                toast.create({ message: response.data?.message || 'Verification failed.', position: "bottom-right", type: "error", duration: 5000 });
                hideResultPanel();
            }
        } catch (error) {
            toast.create({ message: 'An error occurred while verifying the outpass.', position: "bottom-right", type: "error", duration: 5000 });
            hideResultPanel();
        } finally {
            actionInFlight = false;
        }
    };

    if (checkoutBtn) checkoutBtn.addEventListener('click', () => submitAction('checkout'));
    if (checkinBtn) checkinBtn.addEventListener('click', () => submitAction('checkin'));
});
