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

    const resetResult = () => {
        if (studentName) studentName.textContent = '';
        if (outpassType) outpassType.textContent = '';
        if (destination) destination.textContent = '';
        if (checkoutStatus) checkoutStatus.textContent = '';
        if (checkinStatus) checkinStatus.textContent = '';
        if (depart) depart.textContent = '';
        if (ret) ret.textContent = '';
        if (statusBadge) {
            statusBadge.textContent = '';
            statusBadge.className = 'px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600';
        }
        if (checkoutBtn) checkoutBtn.classList.add('hidden');
        if (checkinBtn) checkinBtn.classList.add('hidden');
        if (resultPanel) resultPanel.classList.add('hidden');
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

    const stopScan = () => {
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
        activeScan = null;
    };

    const startScan = async () => {
        try {
            activeScan = true;
            resetResult();
            activeOutpassId = null;
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

            const detector = new BarcodeDetector({ formats: ['qr_code'] });
            const scanLoop = async () => {
                if (!video || !activeStream || !activeScan) return;
                try {
                    const barcodes = await detector.detect(video);
                    if (barcodes.length > 0) {
                        const payload = barcodes[0].rawValue;
                        stopScan();
                        await submitScan(payload);
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
            lastPayload = payload;
            activeOutpassId = null;
            resetResult();
            const response = await Ajax.post('/api/web/verifier/scan', {
                qr_payload: payload
            });

            if (response.ok && response.data?.status && response.data?.data) {
                const data = response.data.data;
                activeOutpassId = data.id;
                if (studentName) studentName.textContent = data.student_name || '';
                if (outpassType) outpassType.textContent = data.type ? `Type: ${data.type}` : '';
                if (destination) destination.textContent = data.destination ? `Destination: ${data.destination}` : '';
                if (checkoutStatus) checkoutStatus.textContent = data.checkout_time || 'Pending';
                if (checkinStatus) checkinStatus.textContent = data.checkin_time || 'Pending';
                if (depart) depart.textContent = `${data.depart_date || '—'} ${data.depart_time || ''}`.trim();
                if (ret) ret.textContent = `${data.return_date || '—'} ${data.return_time || ''}`.trim();

                const statusVariant = (() => {
                    switch (data.status_color) {
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

                if (data.has_checkin) {
                    setStatusBadge('Expired', 'danger');
                    if (checkoutBtn) checkoutBtn.classList.add('hidden');
                    if (checkinBtn) checkinBtn.classList.add('hidden');
                } else if (data.has_checkout) {
                    setStatusBadge('Checked Out', 'warning');
                    if (checkoutBtn) checkoutBtn.classList.add('hidden');
                    if (checkinBtn) checkinBtn.classList.remove('hidden');
                } else {
                    setStatusBadge(data.status || 'Ready', statusVariant);
                    if (checkoutBtn) checkoutBtn.classList.remove('hidden');
                    if (checkinBtn) checkinBtn.classList.add('hidden');
                }

                if (resultPanel) resultPanel.classList.remove('hidden');
            } else {
                toast.create({ message: response.data?.message || 'Invalid QR code.', position: "bottom-right", type: "error", duration: 5000 });
            }
        } catch (error) {
            toast.create({ message: 'An error occurred while scanning the QR.', position: "bottom-right", type: "error", duration: 5000 });
        }
    };

    const submitManualId = async (id) => {
        const toast = new Toast();
        try {
            lastPayload = null;
            activeOutpassId = null;
            resetResult();
            const response = await Ajax.post('/api/web/verifier/scan', {
                outpass_id: id
            });

            if (response.ok && response.data?.status && response.data?.data) {
                const data = response.data.data;
                activeOutpassId = data.id;
                if (studentName) studentName.textContent = data.student_name || '';
                if (outpassType) outpassType.textContent = data.type ? `Type: ${data.type}` : '';
                if (destination) destination.textContent = data.destination ? `Destination: ${data.destination}` : '';
                if (checkoutStatus) checkoutStatus.textContent = data.checkout_time || 'Pending';
                if (checkinStatus) checkinStatus.textContent = data.checkin_time || 'Pending';
                if (depart) depart.textContent = `${data.depart_date || '—'} ${data.depart_time || ''}`.trim();
                if (ret) ret.textContent = `${data.return_date || '—'} ${data.return_time || ''}`.trim();

                const statusVariant = (() => {
                    switch (data.status_color) {
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

                if (data.has_checkin) {
                    setStatusBadge('Expired', 'danger');
                    if (checkoutBtn) checkoutBtn.classList.add('hidden');
                    if (checkinBtn) checkinBtn.classList.add('hidden');
                } else if (data.has_checkout) {
                    setStatusBadge('Checked Out', 'warning');
                    if (checkoutBtn) checkoutBtn.classList.add('hidden');
                    if (checkinBtn) checkinBtn.classList.remove('hidden');
                } else {
                    setStatusBadge(data.status || 'Ready', statusVariant);
                    if (checkoutBtn) checkoutBtn.classList.remove('hidden');
                    if (checkinBtn) checkinBtn.classList.add('hidden');
                }

                if (resultPanel) resultPanel.classList.remove('hidden');
            } else {
                toast.create({ message: response.data?.message || 'Outpass not found.', position: "bottom-right", type: "error", duration: 5000 });
            }
        } catch (error) {
            toast.create({ message: 'Unable to load outpass details.', position: "bottom-right", type: "error", duration: 5000 });
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
            submitManualId(id);
        });
    }

    if (manualIdInput) {
        manualIdInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const raw = manualIdInput.value.trim();
                const id = raw ? Number(raw) : 0;
                if (id && !Number.isNaN(id)) {
                    submitManualId(id);
                }
            }
        });
        manualIdInput.addEventListener('blur', () => {
            const raw = manualIdInput.value.trim();
            const id = raw ? Number(raw) : 0;
            if (id && !Number.isNaN(id)) {
                submitManualId(id);
            }
        });
    }

    if (stopBtn) {
        stopBtn.addEventListener('click', stopScan);
    }

    const submitAction = async (action) => {
        if (!activeOutpassId) {
            const toast = new Toast();
            toast.create({ message: 'Scan a valid QR code first.', position: "bottom-right", type: "warning", duration: 4000 });
            return;
        }
        const toast = new Toast();
        try {
            const response = await Ajax.post('/api/web/verifier/log', {
                outpass_id: activeOutpassId,
                action
            });

            if (response.ok && response.data?.status) {
                toast.create({ message: response.data.message || 'Verification successful.', position: "bottom-right", type: "success", duration: 4000 });
                if (lastPayload) {
                    await submitScan(lastPayload);
                }
            } else {
                toast.create({ message: response.data?.message || 'Verification failed.', position: "bottom-right", type: "error", duration: 5000 });
            }
        } catch (error) {
            toast.create({ message: 'An error occurred while verifying the outpass.', position: "bottom-right", type: "error", duration: 5000 });
        }
    };

    if (checkoutBtn) checkoutBtn.addEventListener('click', () => submitAction('checkout'));
    if (checkinBtn) checkinBtn.addEventListener('click', () => submitAction('checkin'));
});
