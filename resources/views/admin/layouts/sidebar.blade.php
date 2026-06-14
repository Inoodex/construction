<!-- start sidebar section -->
<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[220px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
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
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: 'dashboard' }">

                <!-- Dashboard -->
                {{-- <li class="menu nav-item">
                    <a href="{{ route('tyro-dashboard.index') }}">
                        <button type="button" class="nav-link group"
                            :class="{'active' : activeDropdown === 'dashboard'}"
                            @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                        fill="currentColor" />
                                    <path
                                        d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                        fill="currentColor" />
                                </svg>
                                <span
                                    class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                            </div>
                        </button>
                    </a>
                </li> --}}

                @if(auth()->user()?->hasRole('super-admin'))
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
                        </ul>
                    </li>
                @endif

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
                        <li class="nav-item">
                            <a href="{{ route('admin.core.sites.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="currentColor" />
                                        <circle cx="12" cy="9" r="2.5" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Sites</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.tasks.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Tasks</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.phases.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="currentColor" />
                                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Phases</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.milestones.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                                        <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.9142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Milestones</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.site-logs.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M3 7.5C3 5.29086 4.79086 3.5 7 3.5H17C19.2091 3.5 21 5.29086 21 7.5V16.5C21 18.7091 19.2091 20.5 17 20.5H7C4.79086 20.5 3 18.7091 3 16.5V7.5Z" fill="currentColor" />
                                        <path d="M7 8.25C6.58579 8.25 6.25 8.58579 6.25 9C6.25 9.41421 6.58579 9.75 7 9.75H9C9.41421 9.75 9.75 9.41421 9.75 9C9.75 8.58579 9.41421 8.25 9 8.25H7ZM7 11.25C6.58579 11.25 6.25 11.5858 6.25 12C6.25 12.4142 6.58579 12.75 7 12.75H13C13.4142 12.75 13.75 12.4142 13.75 12C13.75 11.5858 13.4142 11.25 13 11.25H7Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Site Logs</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.site-photos.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M2 15.5V12.5C2 9.90029 2 8.60043 2.46183 7.58148C2.89289 6.62989 3.62989 5.89289 4.58148 5.46183C5.60043 5 6.90029 5 9.5 5H14.5C17.0997 5 18.3996 5 19.4185 5.46183C20.3701 5.89289 21.1071 6.62989 21.5382 7.58148C22 8.60043 22 9.90029 22 12.5V15.5C22 18.0997 22 19.3996 21.5382 20.4185C21.1071 21.3701 20.3701 22.1071 19.4185 22.5382C18.3996 23 17.0997 23 14.5 23H9.5C6.90029 23 5.60043 23 4.58148 22.5382C3.62989 22.1071 2.89289 21.3701 2.46183 20.4185C2 19.3996 2 18.0997 2 15.5Z" fill="currentColor" />
                                        <circle cx="18" cy="9" r="2" fill="currentColor" />
                                        <circle cx="19" cy="7" r="2" fill="currentColor" />
                                        <path d="M2 14.2801L3.77781 12.5023C4.77189 11.5082 5.26892 11.0112 5.83795 10.8322C6.40699 10.6533 7.00963 10.7569 8.21491 10.9642L9.47225 11.1811C10.069 11.287 10.3674 11.34 10.6043 11.4574C10.8411 11.5748 11.0343 11.7558 11.4208 12.1178L14.2135 14.7374C14.4722 14.9807 14.6016 15.1023 14.7142 15.1091C14.8268 15.116 14.9617 15.0091 15.2315 14.7954L15.4379 14.6306C16.301 13.9566 16.7326 13.6196 17.2146 13.4526C17.6966 13.2856 18.2123 13.2993 19.2437 13.3267L20.3103 13.3548C21.2791 13.3806 21.7634 13.3935 22.0508 13.7052C22.3618 14.042 22.3292 14.5961 21.9745 14.8911C21.7091 15.1112 21.2261 15.1112 20.2601 15.1112H16" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Site Photos</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.resources.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" fill="currentColor" />
                                        <path d="M12.75 9C12.75 8.58579 12.4142 8.25 12 8.25C11.5858 8.25 11.25 8.58579 11.25 9L11.25 11.25L9 11.25C8.58579 11.25 8.25 11.5858 8.25 12C8.25 12.4142 8.58579 12.75 9 12.75L11.25 12.75L11.25 15C11.25 15.4142 11.5858 15.75 12 15.75C12.4142 15.75 12.75 15.4142 12.75 15L12.75 12.75L15 12.75C15.4142 12.75 15.75 12.4142 15.75 12C15.75 11.5858 15.4142 11.25 15 11.25L12.75 11.25L12.75 9Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Resources</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.work-orders.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" fill="currentColor" />
                                        <path d="M9 14l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Work Orders</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.core.inspection-checklists.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M3 7.5C3 5.29086 4.79086 3.5 7 3.5H17C19.2091 3.5 21 5.29086 21 7.5V16.5C21 18.7091 19.2091 20.5 17 20.5H7C4.79086 20.5 3 18.7091 3 16.5V7.5Z" fill="currentColor" />
                                        <path d="M8.25 9.75C8.25 9.33579 8.58579 9 9 9H15C15.4142 9 15.75 9.33579 15.75 9.75C15.75 10.1642 15.4142 10.5 15 10.5H9C8.58579 10.5 8.25 10.1642 8.25 9.75ZM8.25 13.25C8.25 12.8358 8.58579 12.5 9 12.5H12C12.4142 12.5 12.75 12.8358 12.75 13.25C12.75 13.6642 12.4142 14 12 14H9C8.58579 14 8.25 13.6642 8.25 13.25Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Inspections</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Approvals -->
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
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
                </li>

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
                    <ul x-cloak x-show="activeDropdown === 'procurement'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.procurement.vendors.index') }}">Vendors</a></li>
                        <li><a href="{{ route('admin.procurement.materials.index') }}">Materials</a></li>
                        <li><a href="{{ route('admin.procurement.requisitions.index') }}">Requisitions</a></li>
                        <li><a href="{{ route('admin.procurement.purchase-orders.index') }}">Purchase Orders</a></li>
                        <li><a href="{{ route('admin.procurement.goods-received-notes.index') }}">Goods Received</a></li>
                        <li><a href="{{ route('admin.procurement.warehouses.index') }}">Warehouses</a></li>
                        <li><a href="{{ route('admin.procurement.stocks.index') }}">Stocks</a></li>
                        <li><a href="{{ route('admin.procurement.material-transfers.index') }}">Material Transfers</a></li>
                        <li><a href="{{ route('admin.procurement.material-issue-slips.index') }}">Issue Slips</a></li>
                        <li><a href="{{ route('admin.procurement.material-wastages.index') }}">Material Wastage</a></li>
                    </ul>
                </li>

                <!-- Reports -->
                {{-- <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Reports</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.report-templates.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434Z" fill="currentColor" />
                                        <path d="M7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943ZM7.25 15.1943C7.25 14.7801 7.58579 14.4443 8 14.4443H13.5C13.9142 14.4443 14.25 14.7801 14.25 15.1943C14.25 15.6085 13.9142 15.9443 13.5 15.9443H8C7.58579 15.9443 7.25 15.6085 7.25 15.1943Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Report Templates</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.scheduled-reports.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                                        <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.4142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Scheduled Reports</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Finance -->
                {{-- <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Finance</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.budgets.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" />
                                        <path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.4142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Budgets</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.boqs.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434Z" fill="currentColor" />
                                        <path d="M7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943ZM7.25 15.1943C7.25 14.7801 7.58579 14.4443 8 14.4443H13.5C13.9142 14.4443 14.25 14.7801 14.25 15.1943C14.25 15.6085 13.9142 15.9443 13.5 15.9443H8C7.58579 15.9443 7.25 15.6085 7.25 15.1943Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Bill of Quantities</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.tenders.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" fill="currentColor" />
                                        <path d="M15.59 7.41L9 14L7 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Tenders</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.invoices.index') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" fill="currentColor" />
                                        <path d="M15.59 7.41L9 14L7 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Invoices</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li> --}}

            </ul>
        </div>
    </nav>
</div>