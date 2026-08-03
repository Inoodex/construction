@props(['items'])

<div
    x-data="{
        open: false,
        top: 0,
        left: 0,
        toggle(el) {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    const rect = el.getBoundingClientRect();
                    const menu = this.$refs.menu;
                    const menuHeight = menu ? menu.offsetHeight : 160;
                    const menuWidth = menu ? menu.offsetWidth : 160;
                    
                    // Vertical position (dropup if near bottom)
                    if (window.innerHeight - rect.bottom < menuHeight + 10) {
                        this.top = rect.top - menuHeight;
                    } else {
                        this.top = rect.bottom + 4;
                    }

                    // Horizontal position (align right edge to button right edge)
                    this.left = rect.right - menuWidth;
                });
            }
        }
    }"
    @click.away="open = false"
    @scroll.window="open = false"
    class="inline-block"
>
    {{-- 3-dot trigger --}}
    <button
        @click.stop="toggle($el)"
        type="button"
        class="flex h-8 w-8 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-700 dark:hover:text-white"
        title="Actions"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
        </svg>
    </button>

    {{-- Dropdown panel (Fixed positioning to bypass overflow:hidden/auto on table wrappers) --}}
    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        :style="`top: ${top}px; left: ${left}px;`"
        class="action-dropdown fixed z-[99999] min-w-[160px] rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-[#1b2e4b]"
    >
        <ul class="py-1 text-sm" @click="open = false">
            {{ $items }}
        </ul>
    </div>
</div>

@once
@push('styles')
<style>
    .action-dropdown li a,
    .action-dropdown li button {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 7px 14px;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #515365;
        background: none;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.15s, color 0.15s;
    }
    :is(.dark) .action-dropdown li a,
    :is(.dark) .action-dropdown li button {
        color: #888ea8;
    }
    .action-dropdown li a:hover,
    .action-dropdown li button:hover {
        background: #f1f2f3;
        color: #4361ee;
    }
    :is(.dark) .action-dropdown li a:hover,
    :is(.dark) .action-dropdown li button:hover {
        background: #253a5e;
        color: #4361ee;
    }
    .action-dropdown li.danger a,
    .action-dropdown li.danger button {
        color: #e7515a;
    }
    .action-dropdown li.danger a:hover,
    .action-dropdown li.danger button:hover {
        background: #fff5f5;
        color: #e7515a;
    }
    :is(.dark) .action-dropdown li.danger a:hover,
    :is(.dark) .action-dropdown li.danger button:hover {
        background: #3b1f1f;
    }
    .action-dropdown li.divider {
        border-top: 1px solid #e9ecef;
        margin: 4px 0;
    }
    :is(.dark) .action-dropdown li.divider {
        border-color: #253a5e;
    }
</style>
@endpush
@endonce
