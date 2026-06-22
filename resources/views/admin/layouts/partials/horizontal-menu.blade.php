<ul class="horizontal-menu border-t border-[#ebedf2] bg-white px-6 py-1.5 font-semibold text-black rtl:space-x-reverse dark:border-[#191e3a] dark:bg-[#0e1726] dark:text-white-dark lg:space-x-1.5 xl:space-x-8"
    x-show="$store.app.menu === 'horizontal'">
    <!-- Dashboard -->
    {{-- <li class="menu nav-item relative">
        <a href="{{ route('tyro-dashboard.index') }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5"
                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                        fill="currentColor" />
                    <path
                        d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                        fill="currentColor" />
                </svg>
                <span class="px-1">Dashboard</span>
            </div>
        </a>
    </li> --}}

    <!-- Administration -->
    @if(auth()->user()?->hasRole('super-admin'))
        <li class="menu nav-item relative">
            <a href="javascript:;" class="nav-link">
                <div class="flex items-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                        <path opacity="0.5" d="M12 15a3 3 0 100-6 3 3 0 000 6z" fill="currentColor" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M18.121 17.659c.032.085.097.158.18.194l.003.002c.198.088.435-.004.529-.204.03-.065.062-.132.094-.197.105-.209.346-.312.569-.245.068.02.137.04.205.063.228.077.375.31.344.548-.009.071-.02.144-.032.215-.04.241.104.475.34.55a4.342 4.342 0 01.705.315c.218.122.316.395.231.624l-.025.07c-.085.23-.339.351-.568.27-.07-.024-.138-.05-.208-.072-.225-.073-.473.023-.585.228-.035.064-.07.13-.108.194-.123.212-.046.48.167.603.064.037.129.071.193.109.215.126.31.398.225.626-.145.394-.33.766-.554 1.111-.137.211-.407.284-.63.17l-.066-.034c-.218-.11-.49-.057-.643.125-.047.056-.093.113-.143.167-.163.178-.186.446-.057.653l.044.07c.143.232.083.535-.135.698a4.33 4.33 0 01-.84.484c-.233.1-.515-.004-.634-.233l-.037-.073c-.116-.226-.395-.316-.624-.213l-.208.094c-.22.1-.336.353-.274.587l.02.075c.063.242-.083.491-.324.557a4.343 4.343 0 01-.767.121c-.25.016-.474-.165-.52-.413l-.014-.076c-.042-.243-.278-.403-.523-.357l-.226.042c-.244.045-.42.274-.393.52l.008.077c.026.252-.15.484-.403.534a4.343 4.343 0 01-.775-.028c-.25-.034-.43-.257-.406-.508l.006-.077c.022-.247-.14-.475-.386-.54l-.22-.058c-.24-.065-.487.058-.584.29l-.022.054c-.114.283-.43.415-.705.298a4.333 4.333 0 01-.803-.45c-.244-.176-.328-.496-.188-.74l.03-.053c.125-.231.066-.52-.138-.684l-.167-.134c-.201-.161-.26-.445-.143-.675l.035-.069c.127-.249.034-.559-.21-.692a4.34 4.34 0 01-.697-.478c-.218-.184-.257-.5-.091-.73l.047-.066c.15-.21-.082-.52-.279-.652l-.183-.122c-.217-.145-.295-.436-.183-.67l.034-.07c.112-.236.4-.334.643-.22l.205.097c.228.106.505.01.62-.218l.094-.188c.114-.23.41-.303.626-.145l.056.04c.223.16.544.11.706-.112a4.337 4.337 0 01.32-.387c.189-.2.15-.52-.086-.685l-.066-.046c-.22-.153-.306-.449-.2-.686l.03-.067c.105-.239.387-.354.63-.259l.186.072c.23.09.5-.02.603-.245l.078-.17c.128-.278.44-.383.717-.245a4.33 4.33 0 01.605.353c.22.153.525.101.684-.117l.033-.044c.162-.216.444-.291.683-.133l.03.02z"
                            fill="currentColor" />
                    </svg>
                    <span class="px-1">Administration</span>
                </div>
                <div class="right_arrow">
                    <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                <li><a href="{{ route('tyro-dashboard.users.index') }}">Users</a></li>
                <li><a href="{{ route('tyro-dashboard.roles.index') }}">Roles</a></li>
                <li><a href="{{ route('tyro-dashboard.privileges.index') }}">Privileges</a></li>
                <li><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
            </ul>
        </li>
    @endif

    <!-- Core -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor" />
                </svg>
                <span class="px-1">Core</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li><a href="{{ route('admin.core.projects.index') }}">Projects</a></li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Sites</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.core.sites.index') }}">All Sites</a></li>
                    <li><a href="{{ route('admin.core.site-logs.index') }}">Site Logs</a></li>
                    <li><a href="{{ route('admin.core.site-photos.index') }}">Site Photos</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Planning</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.core.tasks.index') }}">Tasks</a></li>
                    <li><a href="{{ route('admin.core.phases.index') }}">Phases</a></li>
                    <li><a href="{{ route('admin.core.milestones.index') }}">Milestones</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Execution</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.core.resources.index') }}">Resources</a></li>
                    <li><a href="{{ route('admin.core.resource-gantt.index') }}">Allocation Chart</a></li>
                    <li><a href="{{ route('admin.core.work-orders.index') }}">Work Orders</a></li>
                    <li><a href="{{ route('admin.core.inspection-checklists.index') }}">Inspections</a></li>
                </ul>
            </li>
        </ul>
    </li>

    <!-- Approvals -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" fill="currentColor" />
                </svg>
                <span class="px-1">Approvals</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li><a href="{{ route('admin.approvals.index') }}">Pending Approvals</a></li>
            @if(auth()->user()?->hasRole('super-admin'))
                <li><a href="{{ route('admin.approvals.workflows.index') }}">Approval Workflows</a></li>
            @endif
        </ul>
    </li>

    <!-- Procurement -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" fill="currentColor" />
                    <path d="M11.28 7.78L11.28 11.28L7.78 11.28C7.36598 11.28 7.03001 11.616 7.03001 12.03C7.03001 12.444 7.36598 12.78 7.78 12.78L11.28 12.78L11.28 16.28C11.28 16.694 11.616 17.03 12.03 17.03C12.444 17.03 12.78 16.694 12.78 16.28L12.78 12.78L16.28 12.78C16.694 12.78 17.03 12.444 17.03 12.03C17.03 11.616 16.694 11.28 16.28 11.28L12.78 11.28L12.78 7.78C12.78 7.36598 12.444 7.03 12.03 7.03C11.616 7.03 11.28 7.36598 11.28 7.78Z" fill="currentColor" />
                </svg>
                <span class="px-1">Procurement</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span class="font-semibold">Reference Data</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.procurement.vendors.index') }}">Vendors</a></li>
                    <li><a href="{{ route('admin.procurement.materials.index') }}">Materials</a></li>
                    <li><a href="{{ route('admin.procurement.material-submittals.index') }}">Material Submittals</a></li>
                    <li><a href="{{ route('admin.procurement.warehouses.index') }}">Warehouses</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span class="font-semibold">Procurement</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.procurement.rfqs.index') }}">Request for Quotation</a></li>
                    <li><a href="{{ route('admin.procurement.requisitions.index') }}">Purchase Requisitions</a></li>
                    <li><a href="{{ route('admin.procurement.purchase-orders.index') }}">Purchase Orders</a></li>
                    <li><a href="{{ route('admin.procurement.goods-received-notes.index') }}">Goods Received</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span class="font-semibold">Inventory</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.procurement.stocks.index') }}">Stocks</a></li>
                    <li><a href="{{ route('admin.procurement.material-transfers.index') }}">Material Transfers</a></li>
                    <li><a href="{{ route('admin.procurement.material-issue-slips.index') }}">Issue Slips</a></li>
                    <li><a href="{{ route('admin.procurement.material-wastages.index') }}">Material Wastage</a></li>
                    <li><a href="{{ route('admin.procurement.material-reconciliation.index') }}">Material Reconciliation</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span class="font-semibold">Subcontractors</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.procurement.subcontractors.index') }}">All Subcontractors</a></li>
                    <li><a href="{{ route('admin.procurement.subcontract-agreements.index') }}">Subcontract Agreements</a></li>
                    <li><a href="{{ route('admin.procurement.subcontract-progress-payments.index') }}">Progress Payments</a></li>
                </ul>
            </li>
        </ul>
    </li>

    <!-- HR -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" fill="currentColor"/>
                </svg>
                <span class="px-1">HR</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <!-- People -->
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>People</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.hr.employees.index') }}">All Employees</a></li>
                    {{-- <li><a href="{{ route('admin.hr.employees.create') }}">Add Employee</a></li> --}}
                    <li><a href="{{ route('admin.hr.attendance.index') }}">Daily Register</a></li>
                    {{-- <li><a href="{{ route('admin.hr.attendance.create') }}">Mark Attendance</a></li> --}}
                    <li><a href="{{ route('admin.hr.attendance.summary') }}">Monthly Summary</a></li>
                    <li><a href="{{ route('admin.hr.timesheets.index') }}">Timesheets</a></li>
                    <li><a href="{{ route('admin.hr.leaves.index') }}">Leave Requests</a></li>
                </ul>
            </li>
            <!-- Payroll -->
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Payroll</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.hr.wage-slips.index') }}">Wage Slips</a></li>
                </ul>
            </li>
            <!-- Equipment & Assets -->
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Equipment & Assets</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.hr.equipment.index') }}">Equipment</a></li>
                    <li><a href="{{ route('admin.hr.fuel-logs.index') }}">Fuel Logs</a></li>
                    <li><a href="{{ route('admin.hr.ppe-issuances.index') }}">PPE Issuance</a></li>
                </ul>
            </li>
            <!-- Safety & Compliance -->
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Safety & Compliance</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.hr.incident-reports.index') }}">Incident Reports</a></li>
                    <li><a href="{{ route('admin.hr.hse-checklists.index') }}">HSE Checklists</a></li>
                    <li><a href="{{ route('admin.hr.toolbox-talks.index') }}">Toolbox Talks</a></li>
                </ul>
            </li>
            <!-- Training -->
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Training</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.hr.training-records.index') }}">Training Records</a></li>
                    <li><a href="{{ route('admin.hr.certifications.index') }}">Certifications & Licences</a></li>
                </ul>
            </li>
        </ul>
    </li>

    <!-- Finance -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                    <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.4142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                </svg>
                <span class="px-1">Finance</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li class="relative">
                <a href="javascript:;">Cost Control <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.finance.budgets.index') }}">Budgets</a></li>
                    <li><a href="{{ route('admin.finance.budgets.forecasting') }}">Forecasting (EVM)</a></li>
                    <li><a href="{{ route('admin.finance.cost-overrun-alerts.index') }}">Cost Alerts</a></li>
                    <li><a href="{{ route('admin.finance.labour-entries.index') }}">Labour Cost</a></li>
                    <li><a href="{{ route('admin.finance.material-takeoffs.index') }}">Material Takeoffs</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;">Estimating Analysis <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.finance.boqs.index') }}">Bill of Quantities</a></li>
                    <li><a href="{{ route('admin.finance.rate-analysis.index') }}">Rate Analysis</a></li>
                    {{-- <li><a href="{{ route('admin.finance.tenders.index') }}">Tenders</a></li> --}}
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;">Billing & Payables <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.finance.invoices.index') }}">Invoices</a></li>
                    <li><a href="{{ route('admin.finance.ipas.index') }}">IPAs</a></li>
                    <li><a href="{{ route('admin.finance.bills.index') }}">Bills Payable</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;">Accounting <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.finance.chart-of-accounts.index') }}">Chart of Accounts</a></li>
                    <li><a href="{{ route('admin.finance.journal-entries.index') }}">Journal Vouchers</a></li>
                    <li><a href="{{ route('admin.finance.general-ledger.index') }}">General Ledger</a></li>
                    <li><a href="{{ route('admin.finance.trial-balance.index') }}">Trial Balance</a></li>
                    <li><a href="{{ route('admin.finance.receivables.index') }}">Accounts Receivable</a></li>
                    {{-- <li><a href="{{ route('admin.finance.bank-guarantees.index') }}">Bank Guarantees</a></li> --}}
                    <li><a href="{{ route('admin.finance.balance-sheet.index') }}">Balance Sheet</a></li>
                    <li><a href="{{ route('admin.finance.income-statement.index') }}">Income Statement</a></li>
                </ul>
            </li>
            
        </ul>
    </li>

    <!-- Reports -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434Z" fill="currentColor" />
                    <path d="M7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943ZM7.25 15.1943C7.25 14.7801 7.58579 14.4443 8 14.4443H13.5C13.9142 14.4443 14.25 14.7801 14.25 15.1943C14.25 15.6085 13.9142 15.9443 13.5 15.9443H8C7.58579 15.9443 7.25 15.6085 7.25 15.1943Z" fill="currentColor" />
                </svg>
                <span class="px-1">Reports</span>
            </div>
            <div class="right_arrow">
                <svg class="h-4 w-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            {{-- Report Templates and Scheduled Reports hidden --}}
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Cost & Budgeting</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.reports.financial.budget-vs-actual') }}">Budget vs Actual</a></li>
                    <li><a href="{{ route('admin.reports.financial.project-cost-summary') }}">Project Cost Summary</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Financial Status</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.reports.financial.invoice-status') }}">Invoice Status</a></li>
                    <li><a href="{{ route('admin.reports.financial.cash-flow') }}">Cash Flow</a></li>
                    <li><a href="{{ route('admin.reports.financial.retention-tracker') }}">Retention Tracker</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;" class="flex items-center justify-between">
                    <span>Progress Reports</span>
                    <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.reports.financial.progress-schedule') }}">Progress S-Curve</a></li>
                    <li><a href="{{ route('admin.reports.financial.resource-utilisation') }}">Labour & Equipment</a></li>
                    <li><a href="{{ route('admin.reports.financial.procurement-spend') }}">Procurement Spend</a></li>
                </ul>
            </li>
            <li class="relative">
                <a href="javascript:;">Aging Reports <svg class="h-3 w-3 rtl:rotate-180" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>
                <ul class="sub-sub-menu">
                    <li><a href="{{ route('admin.finance.aging.ar') }}">Amount Receivable</a></li>
                    <li><a href="{{ route('admin.finance.aging.ap') }}">Amount Payable</a></li>
                </ul>
            </li>
        </ul>
    </li>

</ul>