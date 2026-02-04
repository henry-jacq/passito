<!-- Main Container -->
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Setup Box -->
    <div id="setup-container" class="w-full max-w-4xl p-10 bg-white shadow-xl rounded-2xl md:p-16">
        <!-- Welcome Screen -->
        <div id="welcome-screen" class="max-w-3xl text-center space-y-14">
            <!-- Logo and Title -->
            <div class="flex items-center justify-center space-x-3">
                <svg class="text-gray-800 w-9 h-9" viewBox="0 0 92.105 92.1">
                    <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                        <!-- Icon Content -->
                        <g xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z">
                            </path>
                            <path
                                d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z">
                            </path>
                        </g>
                    </g>
                </svg>
                <span class="text-3xl font-medium text-gray-800 font-heading">Passito</span>
            </div>
            <div class="space-y-6">
                <h1 class="text-2xl font-medium text-gray-800 md:text-3xl">Welcome to the Setup Wizard</h1>
                <p class="text-sm text-gray-500 md:text-md text-start">
                    We're excited to have you on board! This setup wizard will guide you through a few simple steps to
                    configure
                    your application and get everything up and running in no time.
                </p>
                <div class="p-4 border-l-4 border-blue-400 rounded-md bg-blue-50 text-start">
                    <p class="text-sm text-blue-700 md:text-md">
                        Make sure you have the necessary details like institution information and user roles ready. If
                        you need
                        assistance, check out our <a href="#" class="text-blue-600 underline">documentation</a> or
                        contact
                        <a href="#" class="text-blue-600 underline">support</a>.
                    </p>
                </div>
            </div>

            <!-- Start Button -->
            <div>
                <button onclick="startSetup()"
                    class="px-8 py-3 text-lg font-medium text-white transition duration-300 ease-in-out bg-blue-600 shadow-lg rounded-3xl hover:bg-blue-700 hover:shadow-xl">
                    Get Started
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Setup Steps -->
        <div id="setup-steps" class="flex items-center justify-center hidden w-full h-full">
            <div class="w-full max-w-3xl mx-auto space-y-10">
                <!-- Progress Bar -->
                <div class="relative mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span id="progress-start" class="w-4 h-4 bg-gray-300 rounded-full"></span>
                        </div>
                        <div class="flex-1 h-2 mx-2 bg-gray-300 rounded-full">
                            <div id="progress-bar" class="h-2 transition-all bg-blue-600 rounded-full"
                                style="width: 0%;"></div>
                        </div>
                        <div class="flex items-center">
                            <span id="progress-end" class="w-4 h-4 bg-gray-300 rounded-full"></span>
                        </div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-between text-sm text-gray-600">
                        <button type="button" data-step="1" id="step1-icon" class="flex items-center justify-center w-8 h-8 p-0 text-sm font-bold text-gray-600 bg-gray-300 border-0 rounded-full appearance-none cursor-pointer step-nav focus:outline-none">1</button>
                        <button type="button" data-step="2" id="step2-icon" class="flex items-center justify-center w-8 h-8 p-0 text-sm font-bold text-gray-600 bg-gray-300 border-0 rounded-full appearance-none cursor-pointer step-nav focus:outline-none">2</button>
                        <button type="button" data-step="3" id="step3-icon" class="flex items-center justify-center w-8 h-8 p-0 text-sm font-bold text-gray-600 bg-gray-300 border-0 rounded-full appearance-none cursor-pointer step-nav focus:outline-none">3</button>
                        <button type="button" data-step="4" id="step4-icon" class="flex items-center justify-center w-8 h-8 p-0 text-sm font-bold text-gray-600 bg-gray-300 border-0 rounded-full appearance-none cursor-pointer step-nav focus:outline-none">4</button>
                    </div>
                </div>

                <div id="setup-errors" class="hidden p-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50"></div>

                <!-- Step Content -->
                <div id="step-content">
                    <!-- Step 1: Create Chief Wardens -->
                    <div id="step1" class="hidden w-full p-2 space-y-4 min-h-[420px]">
                        <h2 class="text-3xl font-medium text-gray-800">1. Create Chief Warden(s)</h2>
                        <p class="text-sm text-gray-500">Add one or more chief wardens to lead the system. This is the only required step.</p>
                        <form onsubmit="submitSuperAdmins(); return false;" class="w-full space-y-4">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <div id="super-admin-list" class="space-y-6"></div>
                            <div class="flex items-center justify-between">
                                <button type="button" onclick="addSuperAdminRow()" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Add another chief warden
                                </button>
                                <button type="submit" class="px-6 py-3 text-white transition duration-300 bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 2: Create Institution -->
                    <div id="step2" class="hidden w-full p-2 space-y-4 min-h-[420px]">
                        <h2 class="text-3xl font-medium text-gray-800">2. Create Institution</h2>
                        <p class="text-sm text-gray-500">Enter the institution details. This step is optional and can be completed later.</p>
                        <form onsubmit="submitInstitution(); return false;" class="w-full space-y-4">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <input id="institution-name" type="text" placeholder="Institution Name" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <input id="institution-address" type="text" placeholder="Institution Address" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <select id="institution-type" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="college">College</option>
                                <option value="university">University</option>
                            </select>
                            <div class="flex items-center justify-between">
                                <button type="button" onclick="skipInstitution()" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                                    Skip for now
                                </button>
                                <button type="submit" class="px-6 py-3 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 3: Create Wardens -->
                    <div id="step3" class="hidden w-full p-2 space-y-4 min-h-[420px]">
                        <h2 class="text-3xl font-medium text-gray-800">3. Create Wardens</h2>
                        <p class="text-sm text-gray-500">Add one or more wardens who will manage hostels. This step is optional.</p>
                        <form onsubmit="submitWardens(); return false;" class="w-full space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <div id="warden-list" class="space-y-6"></div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="addWardenRow()" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Add another warden
                                    </button>
                                    <button type="button" onclick="skipWardens()" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                                        Skip for now
                                    </button>
                                </div>
                                <button type="submit" class="px-6 py-3 text-white transition duration-300 bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 4: Create Hostels -->
                    <div id="step4" class="hidden w-full p-2 space-y-4 min-h-[420px]">
                        <h2 class="text-3xl font-medium text-gray-800">4. Create Hostels</h2>
                        <p class="text-sm text-gray-500">Add hostels and assign wardens. This step is optional.</p>
                        <form onsubmit="finishSetup(); return false;" class="w-full space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <div id="hostel-list" class="space-y-6"></div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="addHostelRow()" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Add another hostel
                                    </button>
                                    <button type="button" onclick="skipHostels()" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                                        Skip for now
                                    </button>
                                </div>
                                <button type="submit" class="px-6 py-3 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Finish Setup
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="setup-complete" class="hidden w-full max-w-3xl mx-auto min-h-[520px] p-0 space-y-8 text-center flex items-center justify-center">
            <div class="w-full p-8 py-16">
                <div class="space-y-8">
                    <div class="space-y-4 text-center">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center justify-center w-12 h-12 text-white bg-green-600 rounded-full shadow-sm">
                                <span class="text-xl font-semibold">✓</span>
                            </div>
                        </div>
                        <h2 class="text-3xl font-semibold text-gray-800 md:text-4xl">Setup completed</h2>
                        <p class="text-base text-gray-600 md:text-md">You’re ready to go.</p>
                    </div>
                    <p class="text-sm text-gray-500 md:text-md text-start">
                        Your workspace is ready. From the dashboard add students, manage academics, hostels, wardens, assignments and system settings, then handle outpass requests. Configure verifiers for automated check-in/out logs.
                    </p>
                    <div class="p-4 border-l-4 border-blue-400 rounded-md bg-blue-50 text-start">
                        <p class="text-sm text-blue-700 md:text-md">
                            Next step: Sign in to add students, configure verifiers and complete optional setup in Settings for outpass rules, residence (hostels/wardens/assignments) and academics (years/institutions/programs).
                        </p>
                    </div>
                    <div class="flex items-center justify-center">
                        <a href="<?= $this->urlFor('auth.login') ?>" class="inline-flex items-center justify-center px-6 py-3 text-white transition duration-300 bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700">
                            Proceed to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<template id="super-admin-template">
    <div class="p-4 space-y-4 border border-gray-200 rounded-lg super-admin-row">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Chief Warden</h3>
            <button type="button" class="text-xs text-red-500 remove-row hover:text-red-700">Remove</button>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <input data-field="name" type="text" placeholder="Full Name" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="email" type="email" placeholder="Email" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="phone" type="text" placeholder="Phone Number" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="password" type="password" placeholder="Password" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <select data-field="gender" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option disabled selected>Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
    </div>
</template>

<template id="warden-template">
    <div class="p-4 space-y-4 border border-gray-200 rounded-lg warden-row">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Warden</h3>
            <button type="button" class="text-xs text-red-500 remove-row hover:text-red-700">Remove</button>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <input data-field="name" type="text" placeholder="Warden Name" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="email" type="email" placeholder="Warden Email" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="phone" type="text" placeholder="Phone Number" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <select data-field="gender" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option disabled selected>Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
    </div>
</template>

<template id="hostel-template">
    <div class="p-4 space-y-4 border border-gray-200 rounded-lg hostel-row">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Hostel</h3>
            <button type="button" class="text-xs text-red-500 remove-row hover:text-red-700">Remove</button>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <input data-field="name" type="text" placeholder="Hostel Name" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input data-field="category" type="text" placeholder="Hostel Category" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <select data-field="type" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Hostel Type</option>
                <option value="gents">Gents Hostel</option>
                <option value="ladies">Ladies Hostel</option>
            </select>
            <select data-field="assigned_warden" class="w-full px-4 py-3 overflow-hidden transition border border-gray-300 rounded-lg warden-select focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Assign Warden</option>
            </select>
        </div>
    </div>
</template>

<script>
    const steps = [1, 2, 3, 4];
    const setupData = {
        super_admins: [],
        institution: {},
        wardens: [],
        hostels: []
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
        institutionType: byId('institution-type')
    };

    function showBlock(element) {
        if (!element) {
            return;
        }
        element.classList.remove('hidden');
    }

    function hideBlock(element) {
        if (!element) {
            return;
        }
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
        if (!dom.setupErrors) {
            return;
        }
        dom.setupErrors.innerHTML = errors.map((err) => `<div>${err}</div>`).join('');
        dom.setupErrors.classList.remove('hidden');
    }

    function hideErrors() {
        if (!dom.setupErrors) {
            return;
        }
        dom.setupErrors.classList.add('hidden');
        dom.setupErrors.innerHTML = '';
    }

    function addRow(list, template, rowClass) {
        if (!list || !template) {
            return;
        }
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
        if (!removeBtn) {
            return;
        }
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
            'hostel-row': 'Hostel'
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
                const field = row.querySelector(`[data-field="${key}"]`);
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
            password: (admin.password ?? '').toString()
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
            type: dom.institutionType ? dom.institutionType.value : ''
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
            const response = await fetch('<?= $this->urlFor('setup.update') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= $this->csrfToken() ?>'
                },
                body: JSON.stringify(setupData)
            });

            const result = await response.json();

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
        if (!dom.setupContainer) {
            return;
        }
        dom.setupContainer.classList.remove('p-10', 'md:p-16');
        dom.setupContainer.classList.add('p-0', 'md:p-0', 'overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const complete = dom.setupComplete;
        if (complete && !complete.classList.contains('hidden')) {
            setCompleteLayout();
            showBlock(complete);
        }
    });

    function refreshWardenSelects() {
        const selects = qsAll('.warden-select');
        selects.forEach((select) => {
            const current = select.value;
            select.innerHTML = '<option value="" disabled selected>Assign Warden</option>';
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
</script>
