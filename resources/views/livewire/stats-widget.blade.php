<!-- <div class="relative rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid gap-y-2">
        <div class="flex items-center gap-x-2">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment</h2>
        </div>

        <div class="grid gap-2 md:grid-cols-2">
            <p class="text-lg font-semibold">Due Date: <span class="text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ $stats['DueDates'] }}</span></p>
        </div>
    </div>
</div> -->


<div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid gap-y-2">
        <div class="flex items-center gap-x-2">
            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <span class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                Due Dates Today
            </span>
        </div>

        <div class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
        {{ $stats['DueDates'] }}
        </div>

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>







