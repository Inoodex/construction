<!-- start sidebar section -->
<style>
    .sidebar .sub-menu a.active,
    .sidebar a.active {
        color: rgb(var(--primary)) !important;
        font-weight: 700;
    }

    .sidebar .sub-menu li.active>a {
        color: rgb(var(--primary)) !important;
        font-weight: 700;
    }
</style>
<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="{{ route('tyro-dashboard.index') }}" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none"
                        src="{{ get_setting('app_logo') ? asset('storage/' . get_setting('app_logo')) : asset('assets/images/logo.svg') }}"
                        alt="logo" />
                    <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">{{ get_setting('app_name', config('app.name')) }}</span>
                </a>
                <!-- <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a> -->
            </div>
            @php
            $route = request()->route();
            $routeName = $route ? $route->getName() : '';
            $activeDropdown = '';
            if (str_starts_with($routeName, 'admin.core.sites.') || str_starts_with($routeName, 'admin.core.site-logs.') || str_starts_with($routeName, 'admin.core.site-photos.')) $activeDropdown = 'site';
            elseif (str_starts_with($routeName, 'admin.core.tasks.') || str_starts_with($routeName, 'admin.core.phases.') || str_starts_with($routeName, 'admin.core.milestones.')) $activeDropdown = 'planning';
            elseif (str_starts_with($routeName, 'admin.core.resources.') || str_starts_with($routeName, 'admin.core.resource-gantt.') || str_starts_with($routeName, 'admin.core.work-orders.') || str_starts_with($routeName, 'admin.core.inspection-checklists.')) $activeDropdown = 'execution';
            elseif (str_starts_with($routeName, 'admin.core.documents.')) $activeDropdown = 'documents';
            elseif (str_starts_with($routeName, 'admin.core.contracts.') || str_starts_with($routeName, 'admin.core.contract-amendments.') || str_starts_with($routeName, 'admin.core.contract-claims.') || str_starts_with($routeName, 'admin.core.contract-closeout.')) $activeDropdown = 'contracts';
            elseif (str_starts_with($routeName, 'admin.procurement.vendors.') || str_starts_with($routeName, 'admin.procurement.materials.') || str_starts_with($routeName, 'admin.procurement.material-submittals.') || str_starts_with($routeName, 'admin.procurement.warehouses.') || str_starts_with($routeName, 'admin.procurement.rfqs.')) $activeDropdown = 'procurement-ref';
            elseif (str_starts_with($routeName, 'admin.procurement.purchase-requisitions.') || str_starts_with($routeName, 'admin.procurement.purchase-orders.') || str_starts_with($routeName, 'admin.procurement.goods-received-notes.')) $activeDropdown = 'procurement-buy';
            elseif (str_starts_with($routeName, 'admin.procurement.stocks.') || str_starts_with($routeName, 'admin.procurement.material-transfers.') || str_starts_with($routeName, 'admin.procurement.material-issue-slips.') || str_starts_with($routeName, 'admin.procurement.material-wastages.') || str_starts_with($routeName, 'admin.procurement.material-reconciliations.')) $activeDropdown = 'inventory';
            elseif (str_starts_with($routeName, 'admin.procurement.subcontractors.') || str_starts_with($routeName, 'admin.procurement.subcontract-agreements.') || str_starts_with($routeName, 'admin.procurement.subcontract-progress-payments.')) $activeDropdown = 'subcontractors';
            elseif (str_starts_with($routeName, 'admin.hr.employees.') || str_starts_with($routeName, 'admin.hr.attendance.') || str_starts_with($routeName, 'admin.hr.timesheets.') || str_starts_with($routeName, 'admin.hr.leaves.')) $activeDropdown = 'people';
            elseif (str_starts_with($routeName, 'admin.hr.wage-slips.')) $activeDropdown = 'payroll';
            elseif (str_starts_with($routeName, 'admin.hr.equipment.') || str_starts_with($routeName, 'admin.hr.fuel-logs.') || str_starts_with($routeName, 'admin.hr.ppe-issuances.')) $activeDropdown = 'equipment';
            elseif (str_starts_with($routeName, 'admin.hr.incident-reports.') || str_starts_with($routeName, 'admin.hr.hse-checklists.') || str_starts_with($routeName, 'admin.hr.toolbox-talks.') || str_starts_with($routeName, 'admin.hr.permits-to-work.') || str_starts_with($routeName, 'admin.hr.safety-audits.')) $activeDropdown = 'safety';
            elseif (str_starts_with($routeName, 'admin.hr.training-records.') || str_starts_with($routeName, 'admin.hr.certifications.')) $activeDropdown = 'training';
            elseif (str_starts_with($routeName, 'admin.crm.')) $activeDropdown = 'crm';
            elseif (str_starts_with($routeName, 'admin.quality.ncrs.') || str_starts_with($routeName, 'admin.quality.corrective-actions.')) $activeDropdown = 'ncr';
            elseif (str_starts_with($routeName, 'admin.quality.itps.') || str_starts_with($routeName, 'admin.quality.punch-lists.')) $activeDropdown = 'inspections';
            elseif (str_starts_with($routeName, 'admin.finance.budgets.') || str_starts_with($routeName, 'admin.finance.forecasting.') || str_starts_with($routeName, 'admin.finance.cost-overrun-alerts.') || str_starts_with($routeName, 'admin.finance.labour-entries.') || str_starts_with($routeName, 'admin.finance.material-takeoffs.')) $activeDropdown = 'cost-control';
            elseif (str_starts_with($routeName, 'admin.finance.chart-of-accounts.') || str_starts_with($routeName, 'admin.finance.journal-entries.') || str_starts_with($routeName, 'admin.finance.general-ledger.') || str_starts_with($routeName, 'admin.finance.trial-balance.') || str_starts_with($routeName, 'admin.finance.reconciliation.') || str_starts_with($routeName, 'admin.finance.receivables.') || str_starts_with($routeName, 'admin.finance.bank-guarantees.') || str_starts_with($routeName, 'admin.finance.balance-sheet.') || str_starts_with($routeName, 'admin.finance.income-statement.')) $activeDropdown = 'accounting';
            elseif (str_starts_with($routeName, 'admin.finance.boqs.') || str_starts_with($routeName, 'admin.finance.rate-analysis.') || str_starts_with($routeName, 'admin.finance.tenders.')) $activeDropdown = 'estimating';
            elseif (str_starts_with($routeName, 'admin.finance.invoices.') || str_starts_with($routeName, 'admin.finance.ipas.') || str_starts_with($routeName, 'admin.finance.bills.') || str_starts_with($routeName, 'admin.finance.expenses.')) $activeDropdown = 'billing';
            elseif (str_starts_with($routeName, 'admin.finance.payment-accounts.')) $activeDropdown = 'payment-accounts';
            elseif (str_starts_with($routeName, 'admin.reports.')) $activeDropdown = 'reports';
            elseif (str_starts_with($routeName, 'admin.approvals.')) $activeDropdown = 'approvals';
            elseif (str_starts_with($routeName, 'admin.settings.') || str_starts_with($routeName, 'admin.categories.')) $activeDropdown = 'admin';
            @endphp
            @php
            $currentPath = request()->path();
            @endphp
            <ul class="relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: '{{ $activeDropdown }}' }"
                x-init="$nextTick(() => { const a = $el.querySelector('a.active'); if (a) a.scrollIntoView({ block: 'center', behavior: 'smooth' }); })">

                @hasRole('client')
                <!-- Client Portal -->
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Client Portal</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.projects.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">My Projects</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.invoices.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434Z" fill="currentColor" />
                                        <path d="M7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">My Invoices</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @else
                @hasPrivilege('admin.view')
                <!-- Administration -->
                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Administration</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('tyro-dashboard.users.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Users</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tyro-dashboard.roles.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Roles</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Privileges</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 15a3 3 0 100-6 3 3 0 000 6z" fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M18.121 17.659c.032.085.097.158.18.194l.003.002c.198.088.435-.004.529-.204.03-.065.062-.132.094-.197.105-.209.346-.312.569-.245.068.02.137.04.205.063.228.077.375.31.344.548-.009.071-.02.144-.032.215-.04.241.104.475.34.55a4.342 4.342 0 01.705.315c.218.122.316.395.231.624l-.025.07c-.085.23-.339.351-.568.27-.07-.024-.138-.05-.208-.072-.225-.073-.473.023-.585.228-.035.064-.07.13-.108.194-.123.212-.046.48.167.603.064.037.129.071.193.109.215.126.31.398.225.626-.145.394-.33.766-.554 1.111-.137.211-.407.284-.63.17l-.066-.034c-.218-.11-.49-.057-.643.125-.047.056-.093.113-.143.167-.163.178-.186.446-.057.653l.044.07c.143.232.083.535-.135.698a4.33 4.33 0 01-.84.484c-.233.1-.515-.004-.634-.233l-.037-.073c-.116-.226-.395-.316-.624-.213l-.208.094c-.22.1-.336.353-.274.587l.02.075c.063.242-.083.491-.324.557a4.343 4.343 0 01-.767.121c-.25.016-.474-.165-.52-.413l-.014-.076c-.042-.243-.278-.403-.523-.357l-.226.042c-.244.045-.42.274-.393.52l.008.077c.026.252-.15.484-.403.534a4.343 4.343 0 01-.775-.028c-.25-.034-.43-.257-.406-.508l.006-.077c.022-.247-.14-.475-.386-.54l-.22-.058c-.24-.065-.487.058-.584.29l-.022.054c-.114.283-.43.415-.705.298a4.333 4.333 0 01-.803-.45c-.244-.176-.328-.496-.188-.74l.03-.053c.125-.231.066-.52-.138-.684l-.167-.134c-.201-.161-.26-.445-.143-.675l.035-.069c.127-.249.034-.559-.21-.692a4.34 4.34 0 01-.697-.478c-.218-.184-.257-.5-.091-.73l.047-.066c.15-.21-.082-.52-.279-.652l-.183-.122c-.217-.145-.295-.436-.183-.67l.034-.07c.112-.236.4-.334.643-.22l.205.097c.228.106.505.01.62-.218l.094-.188c.114-.23.41-.303.626-.145l.056.04c.223.16.544.11.706-.112a4.337 4.337 0 01.32-.387c.189-.2.15-.52-.086-.685l-.066-.046c-.22-.153-.306-.449-.2-.686l.03-.067c.105-.239.387-.354.63-.259l.186.072c.23.09.5-.02.603-.245l.078-.17c.128-.278.44-.383.717-.245a4.33 4.33 0 01.605.353c.22.153.525.101.684-.117l.033-.044c.162-.216.444-.291.683-.133l.03.02z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Settings</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" fill="currentColor" opacity="0.7" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Categories</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasPrivilege

                @hasPrivilege('core.view')
                <!-- Core -->
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Core</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.projects.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Projects</span>
                                </div>
                            </a>
                        </li>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'site'}" @click="activeDropdown === 'site' ? activeDropdown = null : activeDropdown = 'site'">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="currentColor" />
                                        <circle cx="12" cy="9" r="2.5" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Sites</span>
                                </div>
                                <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'site'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'site'" x-collapse class="sub-menu text-gray-500">
                                <li><a href="{{ route('admin.core.sites.index') }}">All Sites</a></li>
                                <li><a href="{{ route('admin.core.site-logs.index') }}">Site Logs</a></li>
                                <li><a href="{{ route('admin.core.site-photos.index') }}">Site Photos</a></li>
                            </ul>
                        </li>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'planning'}" @click="activeDropdown === 'planning' ? activeDropdown = null : activeDropdown = 'planning'">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="currentColor" />
                                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Planning</span>
                                </div>
                                <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'planning'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'planning'" x-collapse class="sub-menu text-gray-500">
                                <li><a href="{{ route('admin.core.tasks.index') }}">Tasks</a></li>
                                <li><a href="{{ route('admin.core.phases.index') }}">Phases</a></li>
                                <li><a href="{{ route('admin.core.milestones.index') }}">Milestones</a></li>
                            </ul>
                        </li>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'execution'}" @click="activeDropdown === 'execution' ? activeDropdown = null : activeDropdown = 'execution'">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" fill="currentColor" />
                                        <path d="M9 14l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Execution</span>
                                </div>
                                <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'execution'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'execution'" x-collapse class="sub-menu text-gray-500">
                                <li><a href="{{ route('admin.core.resources.index') }}">Resources</a></li>
                                <li><a href="{{ route('admin.core.resource-gantt.index') }}">Allocation Chart</a></li>
                                <li><a href="{{ route('admin.core.work-orders.index') }}">Work Orders</a></li>
                                <li><a href="{{ route('admin.core.inspection-checklists.index') }}">Inspections</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Document Management -->
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Document</span>
                </h2>
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'documents'}" @click="activeDropdown === 'documents' ? activeDropdown = null : activeDropdown = 'documents'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M7 4V20H17C18.1046 20 19 19.1046 19 18V6C19 4.89543 18.1046 4 17 4H7Z" fill="currentColor" />
                                <path d="M5 4C5 2.89543 5.89543 2 7 2H14L19 7V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V4Z" fill="currentColor" />
                            </svg>
                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Documents</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'documents'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'documents'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.core.documents.drawings.index') }}">Drawings</a></li>
                        <li><a href="{{ route('admin.core.documents.rfis.index') }}">RFIs</a></li>
                        <li><a href="{{ route('admin.core.documents.change-orders.index') }}">Change Orders</a></li>
                        <li><a href="{{ route('admin.core.documents.transmittals.index') }}">Transmittals</a></li>
                    </ul>
                </li>

                <!-- Approvals -->
                {{-- <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Approvals</span>
                </h2>
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'approvals'}" @click="activeDropdown === 'approvals' ? activeDropdown = null : activeDropdown = 'approvals'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" fill="currentColor" />
                            </svg>
                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Approvals</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'approvals'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'approvals'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.approvals.index') }}">Pending Approvals</a></li>
                @if(auth()->user()?->hasRole('super-admin'))
                <li><a href="{{ route('admin.approvals.workflows.index') }}">Approval Workflows</a></li>
                @endif
            </ul>
            </li> --}}

            @endhasPrivilege
            @hasPrivilege('procurement.view')
            <!-- Procurement -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>Procurement</span>
            </h2>
            <li class="menu nav-item">
                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'procurement'}" @click="activeDropdown === 'procurement' ? activeDropdown = null : activeDropdown = 'procurement'">
                    <div class="flex items-center">
                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" fill="currentColor" />
                            <path d="M11.28 7.78L11.28 11.28L7.78 11.28C7.36598 11.28 7.03001 11.616 7.03001 12.03C7.03001 12.444 7.36598 12.78 7.78 12.78L11.28 12.78L11.28 16.28C11.28 16.694 11.616 17.03 12.03 17.03C12.444 17.03 12.78 16.694 12.78 16.28L12.78 12.78L16.28 12.78C16.694 12.78 17.03 12.444 17.03 12.03C17.03 11.616 16.694 11.28 16.28 11.28L12.78 11.28L12.78 7.78C12.78 7.36598 12.444 7.03 12.03 7.03C11.616 7.03 11.28 7.36598 11.28 7.78Z" fill="currentColor" />
                        </svg>
                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Procurement</span>
                    </div>
                    <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'procurement'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
                <ul x-cloak x-show="activeDropdown === 'procurement'" x-collapse class="sub-menu text-gray-500 list-none">
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important;  width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Reference Data</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.procurement.vendors.index') }}" class="block">Vendors</a></li>
                            <li><a href="{{ route('admin.procurement.materials.index') }}" class="block">Materials</a></li>
                            <li><a href="{{ route('admin.procurement.material-submittals.index') }}" class="block">Material Submittals</a></li>
                            <li><a href="{{ route('admin.procurement.warehouses.index') }}" class="block">Warehouses</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important;  width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Procurement</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.procurement.rfqs.index') }}" class="block">Request for Quotation</a></li>
                            <li><a href="{{ route('admin.procurement.requisitions.index') }}" class="block">Purchase Requisitions</a></li>
                            <li><a href="{{ route('admin.procurement.purchase-orders.index') }}" class="block">Purchase Orders</a></li>
                            <li><a href="{{ route('admin.procurement.goods-received-notes.index') }}" class="block">Goods Received</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important;  width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Inventory</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.procurement.stocks.index') }}" class="block">Stocks</a></li>
                            <li><a href="{{ route('admin.procurement.material-transfers.index') }}" class="block">Material Transfers</a></li>
                            <li><a href="{{ route('admin.procurement.material-issue-slips.index') }}" class="block">Issue Slips</a></li>
                            <li><a href="{{ route('admin.procurement.material-wastages.index') }}" class="block">Material Wastage</a></li>
                            <li><a href="{{ route('admin.procurement.material-reconciliation.index') }}" class="block">Material Reconciliation</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important;  width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Subcontractors</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.procurement.subcontractors.index') }}" class="block">All Subcontractors</a></li>
                            <li><a href="{{ route('admin.procurement.subcontract-agreements.index') }}" class="block">Subcontract Agreements</a></li>
                            <li><a href="{{ route('admin.procurement.subcontract-progress-payments.index') }}" class="block">Progress Payments</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            @endhasPrivilege
            @hasPrivilege('hr.view')
            <!-- HR -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>HR & Payroll</span>
            </h2>
            <li class="menu nav-item">
                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'hr'}" @click="activeDropdown === 'hr' ? activeDropdown = null : activeDropdown = 'hr'">
                    <div class="flex items-center">
                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor" />
                        </svg>
                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">HR & Payroll</span>
                    </div>
                    <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'hr'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
                <ul x-cloak x-show="activeDropdown === 'hr'" x-collapse class="sub-menu text-gray-500">
                    <!-- People -->
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">People</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.hr.employees.index') }}">All Employees</a></li>
                            {{-- <li><a href="{{ route('admin.hr.employees.create') }}">Add Employee</a>
                    </li> --}}
                    <li><a href="{{ route('admin.hr.attendance.index') }}">Daily Register</a></li>
                    {{-- <li><a href="{{ route('admin.hr.attendance.create') }}">Mark Attendance</a>
            </li> --}}
            <li><a href="{{ route('admin.hr.attendance.summary') }}">Monthly Summary</a></li>
            <li><a href="{{ route('admin.hr.timesheets.index') }}">Timesheets</a></li>
            <li><a href="{{ route('admin.hr.leaves.index') }}">Leave Requests</a></li>
            </ul>
            </li>
            <!-- Payroll -->
            <li x-data="{ open: false }">
                <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                    <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Payroll</span>
                    <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                    <li><a href="{{ route('admin.hr.wage-slips.index') }}">Wage Slips</a></li>
                </ul>
            </li>
            <!-- Equipment & Assets -->
            <li x-data="{ open: false }">
                <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                    <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Equipment & Assets</span>
                    <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                    <li><a href="{{ route('admin.hr.equipment.index') }}">Equipment</a></li>
                    <li><a href="{{ route('admin.hr.fuel-logs.index') }}">Fuel Logs</a></li>
                    <li><a href="{{ route('admin.hr.ppe-issuances.index') }}">PPE Issuance</a></li>
                </ul>
            </li>
            <!-- Safety & Compliance -->
            <li x-data="{ open: false }">
                <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                    <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Safety & Compliance</span>
                    <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                    <li><a href="{{ route('admin.hr.incident-reports.index') }}">Incident Reports</a></li>
                    <li><a href="{{ route('admin.hr.hse-checklists.index') }}">HSE Checklists</a></li>
                    <li><a href="{{ route('admin.hr.toolbox-talks.index') }}">Toolbox Talks</a></li>
                    <li><a href="{{ route('admin.hr.permits-to-work.index') }}">Permits to Work</a></li>
                    <li><a href="{{ route('admin.hr.safety-audits.index') }}">Safety Audits</a></li>
                </ul>
            </li>
            <!-- Training -->
            <li x-data="{ open: false }">
                <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                    <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Training</span>
                    <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                    <li><a href="{{ route('admin.hr.training-records.index') }}">Training Records</a></li>
                    <li><a href="{{ route('admin.hr.certifications.index') }}">Certifications & Licences</a></li>
                </ul>
            </li>
            </ul>
            </li>

            @endhasPrivilege
            @hasPrivilege('crm.view')
            <!-- CRM -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>CRM</span>
            </h2>
            <li class="nav-item">
                <ul>
                    <li class="nav-item">
                        <a href="{{ route('admin.crm.leads.index') }}" class="group">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="currentColor" />
                                    <path d="M12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z" fill="currentColor" />
                                </svg>
                                <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Leads</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.crm.clients.index') }}" class="group">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor" />
                                </svg>
                                <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Clients</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.crm.proposals.index') }}" class="group">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" fill="currentColor" />
                                    <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    <path d="M12 18v-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    <path d="M9 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                </svg>
                                <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Proposals</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>

            @endhasPrivilege
            @hasPrivilege('quality.view')
            <!-- Quality Control -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>Quality Control</span>
            </h2>
            <li class="menu nav-item">
                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'quality'}" @click="activeDropdown === 'quality' ? activeDropdown = null : activeDropdown = 'quality'">
                    <div class="flex items-center">
                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" fill="currentColor" />
                        </svg>
                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Quality Control</span>
                    </div>
                    <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'quality'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
                <ul x-cloak x-show="activeDropdown === 'quality'" x-collapse class="sub-menu text-gray-500 list-none">
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Non-Conformance</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.quality.ncrs.index') }}" class="block">Non-Conformance Reports</a></li>
                            <li><a href="{{ route('admin.quality.corrective-actions.index') }}" class="block">Corrective Actions (CAR)</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left" style="text-align: left !important;">Inspections</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.quality.itps.index') }}" class="block">Inspection & Test Plans</a></li>
                            <li><a href="{{ route('admin.quality.punch-lists.index') }}" class="block">Punch Lists (Snagging)</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('admin.quality.material-test-certificates.index') }}">Material Test Certificates</a></li>
                    <li><a href="{{ route('admin.quality.risks.index') }}">Risk Register</a></li>
                </ul>
            </li>

            <!-- Contract Management -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>Contract</span>
            </h2>
            <li class="menu nav-item">
                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'contracts'}" @click="activeDropdown === 'contracts' ? activeDropdown = null : activeDropdown = 'contracts'">
                    <div class="flex items-center">
                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" fill="currentColor" />
                            <path d="M9 7h6m-6 4h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Contracts</span>
                    </div>
                    <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'contracts'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
                <ul x-cloak x-show="activeDropdown === 'contracts'" x-collapse class="sub-menu text-gray-500">
                    <li><a href="{{ route('admin.core.contracts.index') }}">Contracts</a></li>
                    <li><a href="{{ route('admin.core.contract-amendments.index') }}">Amendments</a></li>
                    <li><a href="{{ route('admin.core.contract-claims.index') }}">Claims</a></li>
                    <li><a href="{{ route('admin.core.contract-closeout.index') }}">Closeout Checklists</a></li>
                </ul>
            </li>
            @endhasPrivilege

            @hasPrivilege('finance.view')
            <!-- Finance -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>Finance</span>
            </h2>
            <li class="menu nav-item">
                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'finance'}" @click="activeDropdown === 'finance' ? activeDropdown = null : activeDropdown = 'finance'">
                    <div class="flex items-center">
                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                            <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.4142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                        </svg>
                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Finance</span>
                    </div>
                    <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'finance'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
                <ul x-cloak x-show="activeDropdown === 'finance'" x-collapse class="sub-menu text-gray-500">
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Cost Control</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.budgets.index') }}">Budgets</a></li>
                            <li><a href="{{ route('admin.finance.budgets.forecasting') }}">Forecasting</a></li>
                            <li><a href="{{ route('admin.finance.cost-overrun-alerts.index') }}">Cost Alerts</a></li>
                            <li><a href="{{ route('admin.finance.labour-entries.index') }}">Labour Cost</a></li>
                            <li><a href="{{ route('admin.finance.material-takeoffs.index') }}">Material Takeoffs</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Payment Accounts</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.payment-accounts.index') }}">All Accounts</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Accounting</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.chart-of-accounts.index') }}">Chart of Accounts</a></li>
                            <li><a href="{{ route('admin.finance.journal-entries.index') }}">Journal Vouchers</a></li>
                            <li><a href="{{ route('admin.finance.general-ledger.index') }}">General Ledger</a></li>
                            <li><a href="{{ route('admin.finance.trial-balance.index') }}">Trial Balance</a></li>
                            <li><a href="{{ route('admin.finance.reconciliation.index') }}">Reconciliation</a></li>
                            <li><a href="{{ route('admin.finance.receivables.index') }}">Accounts Receivable</a></li>
                            <li><a href="{{ route('admin.finance.bank-guarantees.index') }}">Bank Guarantees</a></li>
                            <li><a href="{{ route('admin.finance.balance-sheet.index') }}">Balance Sheet</a></li>
                            <li><a href="{{ route('admin.finance.income-statement.index') }}">Income Statement</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Estimating Analysis</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.boqs.index') }}">Bill of Quantities</a></li>
                            <li><a href="{{ route('admin.finance.rate-analysis.index') }}">Rate Analysis</a></li>
                            <li><a href="{{ route('admin.finance.tenders.index') }}">Tenders</a></li>
                            <li><a href="{{ route('admin.finance.rod-calculations.index') }}">Rod Calculations</a></li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <a href="javascript:;" class="group block font-semibold" style="display: flex !important; width: 100%;" @click="open = !open">
                            <span class="text-left text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300" style="text-align: left !important;">Billing & Payables</span>
                            <svg class="h-3 w-3 transition-transform shrink-0" :class="{ 'rotate-90': open }" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.invoices.index') }}">Invoices</a></li>
                            <li><a href="{{ route('admin.finance.ipas.index') }}">Interim Payment</a></li>
                            <li><a href="{{ route('admin.finance.bills.index') }}">Bills Payable</a></li>
                            <li><a href="{{ route('admin.finance.expenses.index') }}">Expenses</a></li>
                        </ul>
                    </li>

                </ul>
            </li>
            @endhasPrivilege

            @hasPrivilege('reports.view')
            <!-- Reports -->
            <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                <span>Reports</span>
            </h2>
            <li class="nav-item">
                <ul>
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'cost-budgeting'}" @click="activeDropdown === 'cost-budgeting' ? activeDropdown = null : activeDropdown = 'cost-budgeting'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                                    <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.4142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Cost & Budgeting</span>
                            </div>
                            <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'cost-budgeting'}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'cost-budgeting'" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.reports.financial.budget-vs-actual') }}">Budget vs Actual</a></li>
                            <li><a href="{{ route('admin.reports.financial.project-cost-summary') }}">Project Cost Summary</a></li>
                        </ul>
                    </li>
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'financial-status'}" @click="activeDropdown === 'financial-status' ? activeDropdown = null : activeDropdown = 'financial-status'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434Z" fill="currentColor" />
                                    <path d="M7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943ZM7.25 15.1943C7.25 14.7801 7.58579 14.4443 8 14.4443H13.5C13.9142 14.4443 14.25 14.7801 14.25 15.1943C14.25 15.6085 13.9142 15.9443 13.5 15.9443H8C7.58579 15.9443 7.25 15.6085 7.25 15.1943Z" fill="currentColor" />
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Financial Status</span>
                            </div>
                            <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'financial-status'}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'financial-status'" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.reports.financial.invoice-status') }}">Invoice Status</a></li>
                            <li><a href="{{ route('admin.reports.financial.cash-flow') }}">Cash Flow</a></li>
                            <li><a href="{{ route('admin.reports.financial.retention-tracker') }}">Retention Tracker</a></li>
                        </ul>
                    </li>
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'progress-procurement'}" @click="activeDropdown === 'progress-procurement' ? activeDropdown = null : activeDropdown = 'progress-procurement'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 3V21H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 16L10 11L13 14L17 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M21 7V11H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Progress Reports</span>
                            </div>
                            <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'progress-procurement'}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'progress-procurement'" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.reports.financial.progress-schedule') }}">Progress S-Curve</a></li>
                            <li><a href="{{ route('admin.reports.financial.resource-utilisation') }}">Labour & Equipment</a></li>
                            <li><a href="{{ route('admin.reports.financial.procurement-spend') }}">Procurement Spend</a></li>
                        </ul>
                    </li>

                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'aging.reports'}" @click="activeDropdown === 'aging.reports' ? activeDropdown = null : activeDropdown = 'aging.reports'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                                    <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Aging Reports</span>
                            </div>
                            <div class="rtl:rotate-180 transition-transform duration-300" :class="{'rotate-90' : activeDropdown === 'aging.reports'}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'aging.reports'" x-collapse class="sub-menu text-gray-500 list-none ltr:pl-4 rtl:pr-4">
                            <li><a href="{{ route('admin.finance.aging.ar') }}">Amount Receivable</a></li>
                            <li><a href="{{ route('admin.finance.aging.ap') }}">Amount Payable</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endhasPrivilege

            </ul>
            @endhasRole
        </div>
    </nav>
    <script>
        (function() {
            function normalize(p) {
                if (!p) return '';
                return p.replace(/\/+$/, '') || '/';
            }

            function findActiveLink(sidebar) {
                var path = normalize(window.location.pathname);
                var links = sidebar.querySelectorAll('a[href]');
                var exact = null,
                    prefix = null,
                    prefixLen = -1;
                links.forEach(function(a) {
                    var href = a.getAttribute('href');
                    if (!href || href === 'javascript:;' || href.charAt(0) === '#') return;
                    var linkPath;
                    try {
                        linkPath = normalize(new URL(a.href, location.origin).pathname);
                    } catch (e) {
                        return;
                    }
                    if (linkPath === path) {
                        if (!exact) exact = a;
                    } else if (path.indexOf(linkPath + '/') === 0 && linkPath.length > prefixLen) {
                        // longest matching parent path (e.g. detail/edit pages under an index route)
                        prefix = a;
                        prefixLen = linkPath.length;
                    }
                });
                return exact || prefix;
            }

            // Open every ancestor menu of the active link by setting the relevant
            // Alpine reactive state (top-level "activeDropdown" and nested "open").
            //
            // Order matters: the top-level dropdown must open FIRST so its sub-menu
            // becomes visible, then the nested "open" groups are toggled on a later
            // tick. Otherwise x-collapse measures a nested group while its ancestor
            // is still display:none, reads a height of 0, and the child menu never
            // expands even after the parent opens.
            function openAncestors(sidebar, active) {
                if (!window.Alpine) return false;

                // Top-level dropdown: the scrolling <ul> owns activeDropdown state.
                var topData = Alpine.$data(sidebar);

                // Collect the ancestor chain from the active link up to the sidebar.
                var chain = [];
                var node = active;
                while (node && node !== sidebar) {
                    chain.push(node);
                    node = node.parentElement;
                }

                // 1) Open the top-level menu(s). A top-level <li class="menu"> holds a
                //    button whose @click assigns activeDropdown; read and apply it.
                chain.forEach(function(node) {
                    if (node.tagName === 'LI' && node.classList.contains('menu')) {
                        var btn = node.querySelector(':scope > button');
                        if (btn && topData) {
                            var click = btn.getAttribute('@click') || btn.getAttribute('x-on:click') || '';
                            var m = click.match(/activeDropdown\s*=\s*'([^']+)'/);
                            if (m) topData.activeDropdown = m[1];
                        }
                    }
                });

                // 2) Open the nested collapsible groups (<li x-data="{ open: false }">)
                //    AFTER the parent sub-menu's x-collapse transition has finished.
                //    x-collapse reads scrollHeight the moment "open" flips to true; if
                //    the parent is still height:0 / mid-animation it measures 0, locks
                //    the child at 0px and never re-measures, so the child rows stay
                //    hidden. A timeout longer than the ~300ms collapse guarantees the
                //    parent is fully laid out before we measure the child.
                var openNested = function() {
                    chain.forEach(function(node) {
                        if (node.tagName === 'LI' && node.hasAttribute('x-data')) {
                            var d = Alpine.$data(node);
                            if (d && typeof d.open !== 'undefined') d.open = true;
                        }
                    });
                };
                setTimeout(openNested, 350);
                return true;
            }

            function run() {
                var sidebar = document.querySelector('.sidebar ul.overflow-y-auto');
                if (!sidebar) return;

                var active = findActiveLink(sidebar);
                if (!active) return;
                active.classList.add('active');

                openAncestors(sidebar, active);

                // Wait for both the top-level and nested x-collapse transitions
                // to finish (nested group opens at ~350ms + ~300ms animation)
                // before scrolling, so the active link is at its final position.
                setTimeout(function() {
                    active.scrollIntoView({
                        block: 'center',
                        behavior: 'smooth'
                    });
                }, 750);
            }

            function boot() {
                if (window.Alpine) {
                    // Alpine already started – run on next tick so components are initialised.
                    (window.Alpine.nextTick ? window.Alpine.nextTick(run) : setTimeout(run, 0));
                } else {
                    document.addEventListener('alpine:initialized', function() {
                        setTimeout(run, 0);
                    });
                    // Fallback in case the event was missed.
                    setTimeout(run, 600);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot);
            } else {
                boot();
            }
        })();
    </script>
</div>