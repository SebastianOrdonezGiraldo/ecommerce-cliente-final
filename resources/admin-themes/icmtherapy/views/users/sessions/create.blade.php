<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.sessions.title')
    </x-slot>

    <div class="flex min-h-[100vh] items-center justify-center bg-[#f5fafb] px-4">
        <div class="flex w-full max-w-sm flex-col items-center gap-6">
            <img
                class="h-auto w-[220px] max-w-full"
                src="{{ asset('themes/shop/default/images/LogoIMC.webp') }}"
                alt="ICM Therapy"
            />

            <div class="box-shadow flex w-full flex-col overflow-hidden rounded-2xl border border-[#d9eef0] bg-white shadow-sm dark:bg-gray-900">
                <x-admin::form :action="route('admin.session.store')">
                    <p class="border-b border-[#d9eef0] p-5 text-xl font-bold text-[#293238] dark:text-white">
                        @lang('admin::app.users.sessions.title')
                    </p>

                    <div class="space-y-4 p-5 dark:border-gray-800">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.users.sessions.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                class="w-full"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.users.sessions.email')"
                                :placeholder="trans('admin::app.users.sessions.email')"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="relative w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.users.sessions.password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                class="w-full ltr:pr-10 rtl:pl-10"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                :label="trans('admin::app.users.sessions.password')"
                                :placeholder="trans('admin::app.users.sessions.password')"
                            />

                            <span
                                class="icon-view absolute top-[42px] -translate-y-2/4 cursor-pointer text-2xl ltr:right-2 rtl:left-2"
                                onclick="switchVisibility()"
                                id="visibilityIcon"
                                role="presentation"
                                tabindex="0"
                            ></span>

                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="flex items-center justify-between border-t border-[#d9eef0] p-5">
                        <a
                            class="cursor-pointer text-xs font-semibold leading-6 text-[#128d99]"
                            href="{{ route('admin.forget_password.create') }}"
                        >
                            @lang('admin::app.users.sessions.forget-password-link')
                        </a>

                        <button
                            class="cursor-pointer rounded-xl bg-[#1aa6b2] px-4 py-2 font-semibold text-white transition hover:bg-[#128d99]"
                            aria-label="{{ trans('admin::app.users.sessions.submit-btn') }}"
                        >
                            @lang('admin::app.users.sessions.submit-btn')
                        </button>
                    </div>
                </x-admin::form>
            </div>

            <p class="text-center text-sm text-[#52676c]">
                Administración segura de ICM Therapy
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            function switchVisibility() {
                const passwordField = document.getElementById('password');
                const visibilityIcon = document.getElementById('visibilityIcon');

                passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
                visibilityIcon.classList.toggle('icon-view-close');
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
