import Ajax from './libs/ajax';

document.addEventListener('DOMContentLoaded', () => {
    const steps = [1, 2, 3, 4];
    const setupData = {
        super_admins: [],
        institution: {},
        wardens: [],
        hostels: [],
    };
    let currentStep = 1;

    const byId = (id) => document.getElementById(id);
    const qsAll = (selector, root = document) => Array.from(root.querySelectorAll(selector));

    const dom = {
        welcomeScreen: byId('welcome-screen'),
        setupSteps: byId('setup-steps'),
        setupComplete: byId('setup-complete'),
        setupErrors: byId('setup-errors'),
        progressBar: byId('progress-bar'),
        setupContainer: byId('setup-container'),
        superAdminList: byId('super-admin-list'),
        wardenList: byId('warden-list'),
        hostelList: byId('hostel-list'),
        superAdminTemplate: byId('super-admin-template'),
        wardenTemplate: byId('warden-template'),
        hostelTemplate: byId('hostel-template'),
        institutionName: byId('institution-name'),
        institutionAddress: byId('institution-address'),
        institutionType: byId('institution-type'),
    };

    function showBlock(element) {
        if (!element) return;
        element.classList.remove('hidden');
    }

    function hideBlock(element) {
        if (!element) return;
        element.classList.add('hidden');
    }

    function startSetup() {
        hideBlock(dom.welcomeScreen);
        showBlock(dom.setupSteps);
        addSuperAdminRow();
        addWardenRow();
        addHostelRow();
        bindStepNavigation();
        goToStep(1);
    }

    function goToStep(step) {
        const currentEl = byId(`step${currentStep}`);
        const nextEl = byId(`step${step}`);

        if (currentEl && currentStep !== step) {
            hideBlock(currentEl);
        }

        if (nextEl) {
            showBlock(nextEl);
        }

        steps.forEach((s) => {
            const stepIcon = byId(`step${s}-icon`);
            if (!stepIcon) return;
            stepIcon.classList.toggle('bg-blue-600', s <= step);
            stepIcon.classList.toggle('text-white', s <= step);
            stepIcon.classList.toggle('bg-gray-300', s > step);
            stepIcon.classList.toggle('text-gray-600', s > step);
        });

        if (dom.progressBar) {
            dom.progressBar.style.width = `${((step - 1) / (steps.length - 1)) * 100}%`;
        }

        hideErrors();
        currentStep = step;
    }

    function bindStepNavigation() {
        qsAll('.step-nav').forEach((button) => {
            button.addEventListener('click', () => {
                const target = Number(button.dataset.step || 1);
                if (target >= 1 && target <= 4) {
                    goToStep(target);
                }
            });
        });
    }

    function showErrors(errors) {
        if (!dom.setupErrors) return;
        dom.setupErrors.innerHTML = errors.map((err) => `<div>${err}</div>`).join('');
        dom.setupErrors.classList.remove('hidden');
    }

    function hideErrors() {
        if (!dom.setupErrors) return;
        dom.setupErrors.classList.add('hidden');
        dom.setupErrors.innerHTML = '';
    }

    function addRow(list, template, rowClass) {
        if (!list || !template) return;
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector(`.${rowClass}`);
        if (row) {
            attachRemoveHandler(row, list, rowClass);
        }
        list.appendChild(clone);
        updateRemoveVisibility(list, rowClass);
    }

    function addSuperAdminRow() {
        addRow(dom.superAdminList, dom.superAdminTemplate, 'super-admin-row');
    }

    function addWardenRow() {
        addRow(dom.wardenList, dom.wardenTemplate, 'warden-row');
    }

    function addHostelRow() {
        addRow(dom.hostelList, dom.hostelTemplate, 'hostel-row');
        refreshWardenSelects();
    }

    function attachRemoveHandler(row, list, rowClass) {
        const removeBtn = row.querySelector('.remove-row');
        if (!removeBtn) return;
        removeBtn.addEventListener('click', () => {
            row.remove();
            updateRemoveVisibility(list, rowClass);
            if (rowClass === 'hostel-row') {
                refreshWardenSelects();
            }
        });
    }

    function updateRemoveVisibility(list, rowClass) {
        const rows = qsAll(`.${rowClass}`, list);
        const titleMap = {
            'super-admin-row': 'Chief Warden',
            'warden-row': 'Warden',
            'hostel-row': 'Hostel',
        };
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-row');
            if (removeBtn) {
                removeBtn.style.display = rows.length > 1 ? 'inline-flex' : 'none';
            }
            const title = row.querySelector('h3');
            if (title && titleMap[rowClass]) {
                title.textContent = `${titleMap[rowClass]} ${index + 1}`;
            }
        });
    }

    function collectRows(rowClass, fieldKeys, rawFields = []) {
        return qsAll(`.${rowClass}`).map((row) => {
            const data = {};
            fieldKeys.forEach((key) => {
                const field = row.querySelector(`[data-field=\"${key}\"]`);
                if (!field) {
                    data[key] = '';
                    return;
                }
                data[key] = rawFields.includes(key) ? field.value : field.value.trim();
            });
            return data;
        });
    }

    function submitSuperAdmins() {
        const admins = collectRows('super-admin-row', ['name', 'email', 'phone', 'password', 'gender'], ['password']).map((admin) => ({
            ...admin,
            password: (admin.password ?? '').toString(),
        }));

        const errors = validateAdmins(admins);
        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        setupData.super_admins = admins;
        goToStep(2);
    }

    function submitInstitution() {
        const institution = {
            name: dom.institutionName ? dom.institutionName.value.trim() : '',
            address: dom.institutionAddress ? dom.institutionAddress.value.trim() : '',
            type: dom.institutionType ? dom.institutionType.value : '',
        };

        const errors = [];
        if (!institution.name || !institution.address) {
            errors.push('Institution name and address are required.');
        }
        if (!institution.type) {
            errors.push('Institution type is required.');
        }

        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        setupData.institution = institution;
        goToStep(3);
    }

    function skipInstitution() {
        setupData.institution = null;
        goToStep(3);
    }

    function submitWardens() {
        const wardens = collectRows('warden-row', ['name', 'email', 'phone', 'gender']);

        const errors = validateWardens(wardens);
        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        setupData.wardens = wardens;
        refreshWardenSelects();
        goToStep(4);
    }

    function skipWardens() {
        setupData.wardens = [];
        refreshWardenSelects();
        goToStep(4);
    }

    function finishSetup() {
        const hostels = collectRows('hostel-row', ['name', 'category', 'type', 'assigned_warden']);

        const errors = validateHostels(hostels);
        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        setupData.hostels = hostels;
        submitSetup();
    }

    function skipHostels() {
        setupData.hostels = [];
        submitSetup();
    }

    async function submitSetup() {
        hideErrors();

        try {
            const response = await Ajax.post('/setup/update', setupData);
            const result = response.data;

            if (!response.ok || !result.status) {
                const errors = result.errors || [result.message || 'Setup failed.'];
                showErrors(errors);
                return;
            }

            hideBlock(dom.setupSteps);
            showBlock(dom.setupComplete);
            setCompleteLayout();
        } catch (error) {
            showErrors(['An unexpected error occurred. Please try again.']);
        }
    }

    function setCompleteLayout() {
        if (!dom.setupContainer) return;
        dom.setupContainer.classList.remove('p-10', 'md:p-16');
        dom.setupContainer.classList.add('p-0', 'md:p-0', 'overflow-hidden');
    }

    function refreshWardenSelects() {
        const selects = qsAll('.warden-select');
        selects.forEach((select) => {
            const current = select.value;
            select.innerHTML = '<option value=\"\" disabled selected>Assign Warden</option>';
            setupData.wardens.forEach((warden) => {
                const option = document.createElement('option');
                option.value = warden.email;
                option.textContent = `${warden.name} (${warden.email})`;
                select.appendChild(option);
            });
            if (current) {
                select.value = current;
            }
        });
    }

    function validateAdmins(admins) {
        const errors = [];
        if (!admins.length) {
            errors.push('At least one chief warden is required.');
        }
        admins.forEach((admin, index) => {
            const label = `Chief Warden ${index + 1}`;
            if (!admin.name || !admin.email || !admin.phone || !admin.password) {
                errors.push(`${label} details are incomplete.`);
            }
            if (!admin.gender) {
                errors.push(`${label} gender is required.`);
            }
            if (admin.password && admin.password.length < 8) {
                errors.push(`${label} password must be at least 8 characters.`);
            }
        });
        return errors;
    }

    function validateWardens(wardens) {
        const errors = [];
        if (!wardens.length) {
            return errors;
        }
        wardens.forEach((warden, index) => {
            const label = `Warden ${index + 1}`;
            if (!warden.name || !warden.email || !warden.phone) {
                errors.push(`${label} details are incomplete.`);
            }
            if (!warden.gender) {
                errors.push(`${label} gender is required.`);
            }
        });
        return errors;
    }

    function validateHostels(hostels) {
        const errors = [];
        if (!hostels.length) {
            return errors;
        }
        hostels.forEach((hostel, index) => {
            const label = `Hostel ${index + 1}`;
            if (!hostel.name || !hostel.category || !hostel.type) {
                errors.push(`${label} details are incomplete.`);
            }
            if (!hostel.assigned_warden) {
                errors.push(`${label} must have an assigned warden.`);
            }
        });
        return errors;
    }

    window.startSetup = startSetup;
    window.addSuperAdminRow = addSuperAdminRow;
    window.addWardenRow = addWardenRow;
    window.addHostelRow = addHostelRow;
    window.skipInstitution = skipInstitution;
    window.skipWardens = skipWardens;
    window.skipHostels = skipHostels;
    window.submitSuperAdmins = submitSuperAdmins;
    window.submitInstitution = submitInstitution;
    window.submitWardens = submitWardens;
    window.finishSetup = finishSetup;

    const complete = dom.setupComplete;
    if (complete && !complete.classList.contains('hidden')) {
        setCompleteLayout();
        showBlock(complete);
    }
});
