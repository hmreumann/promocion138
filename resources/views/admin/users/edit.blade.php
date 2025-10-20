<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password (leave blank to keep current)')" />
                            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Active -->
                        <div class="mb-4">
                            <label for="active" class="inline-flex items-center">
                                <input type="hidden" name="active" value="0">
                                <input id="active" type="checkbox" name="active" value="1" {{ old('active', $user->active) ? 'checked' : '' }} class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('active')" class="mt-2" />
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select --</option>
                                <option value="smsv" {{ old('payment_method', $user->payment_method) === 'smsv' ? 'selected' : '' }}>SMSV</option>
                                <option value="transfer" {{ old('payment_method', $user->payment_method) === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <!-- Plan -->
                        <div class="mb-4">
                            <x-input-label for="plan" :value="__('Plan')" />
                            <select id="plan" name="plan" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select --</option>
                                <option value="full" {{ old('plan', $user->plan) === 'full' ? 'selected' : '' }}>Full</option>
                                <option value="basic" {{ old('plan', $user->plan) === 'basic' ? 'selected' : '' }}>Basic</option>
                            </select>
                            <x-input-error :messages="$errors->get('plan')" class="mt-2" />
                        </div>

                        <!-- Cents -->
                        <div class="mb-4">
                            <x-input-label for="cents" :value="__('Cents')" />
                            <x-text-input id="cents" class="block w-full mt-1" type="number" name="cents" :value="old('cents', $user->cents ?? 0)" min="0" />
                            <x-input-error :messages="$errors->get('cents')" class="mt-2" />
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
